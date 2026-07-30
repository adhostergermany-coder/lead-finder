<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OsmService
{
    protected const NOMINATIM_URL = 'https://nominatim.openstreetmap.org/search';

    protected const PHOTON_URL = 'https://photon.komoot.io/api/';

    protected const GOOGLE_SEARCH_URL = 'https://places.googleapis.com/v1/places:searchText';

    protected const USER_AGENT = 'lead-finder-laravel/1.0 (lead-finder-app)';

    protected const EXCLUDE_KEYWORDS = [
        'police', 'fire station', 'fire department', 'laboratory', 'lab ',
        'parking', 'tunnel', 'constituency', 'depot', 'university', 'lot ',
        'service centre', 'service center', 'auto service', 'auto parts',
        'service road', 'service building', 'service tunnel', 'church',
        'church of', 'presbyterian', 'financial', 'credit union', 'savings',
        'film studio', 'movie', 'studio', 'paint protection', 'upholstery',
        'vacuum store', 'messenger', 'tax service', 'real estate',
        'crescent', 'boulevard', 'avenue', 'drive', 'road', 'close',
        'way', 'gate', 'trail', 'ridge', 'crest', 'court',
    ];

    protected const GENERIC_NAMES = [
        'lawyer office', "lawyer's office", 'office', 'service',
        'law office', 'legal office',
    ];

    public function searchAndSave(string $area, string $bizType, ?string $apiKey = null): array
    {
        if ($apiKey) {
            return $this->searchGooglePlaces($area, $bizType, $apiKey);
        }

        return $this->searchPhoton($area, $bizType);
    }

    protected function searchPhoton(string $area, string $bizType): array
    {
        $bbox = $this->geocodeArea($area);
        Log::info("Photon area: {$bbox['display']}");

        $city = explode(',', $area)[0];
        $queries = [
            "{$bizType} {$area}",
            "{$bizType} office {$city}",
            "{$bizType} firm {$city}",
        ];

        $allFeatures = [];
        foreach ($queries as $query) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders(['User-Agent' => self::USER_AGENT])
                    ->get(self::PHOTON_URL, [
                        'q' => $query,
                        'limit' => 50,
                        'lang' => 'en',
                    ]);

                if ($response->successful()) {
                    $features = $response->json()['features'] ?? [];
                    $allFeatures = array_merge($allFeatures, $features);
                    Log::info("Query '{$query}': ".count($features).' results');
                }

                usleep(500000);
            } catch (\Exception $e) {
                Log::warning("Query '{$query}' failed: ".$e->getMessage());
            }
        }

        $uniqueFeatures = $this->filterPhotonFeatures($allFeatures, $city, $bizType);

        $leads = [];
        foreach ($uniqueFeatures as $feature) {
            $lead = $this->extractPhotonLead($feature, $area, $bizType);
            if (! empty($lead['company_name'])) {
                $leads[] = $lead;
            }
        }

        Log::info('Found '.count($leads).' leads from Photon');

        return $leads;
    }

    protected function geocodeArea(string $area): array
    {
        $response = Http::timeout(30)
            ->withHeaders(['User-Agent' => self::USER_AGENT])
            ->get(self::NOMINATIM_URL, [
                'q' => $area,
                'format' => 'json',
                'limit' => 1,
            ]);

        $response->throw();
        $results = $response->json();

        if (empty($results)) {
            throw new \Exception("'{$area}' not found");
        }

        $bbox = $results[0]['boundingbox'];

        return [
            'west' => (float) $bbox[2],
            'south' => (float) $bbox[0],
            'east' => (float) $bbox[3],
            'north' => (float) $bbox[1],
            'display' => $results[0]['display_name'] ?? $area,
        ];
    }

    protected function filterPhotonFeatures(array $allFeatures, string $city, string $bizType): array
    {
        $seenIds = [];
        $unique = [];
        $cityLower = strtolower($city);

        foreach ($allFeatures as $feature) {
            $props = $feature['properties'] ?? [];
            $osmId = $props['osm_id'] ?? null;
            $name = $props['name'] ?? '';

            if (! $osmId || isset($seenIds[$osmId]) || ! $name) {
                continue;
            }
            $seenIds[$osmId] = true;

            $featureCity = strtolower($props['city'] ?? '');
            if (! str_contains($featureCity, $cityLower)) {
                continue;
            }

            $nameLower = strtolower(trim($name));

            if (in_array($nameLower, array_map('strtolower', self::GENERIC_NAMES))) {
                continue;
            }

            foreach (self::EXCLUDE_KEYWORDS as $kw) {
                if (str_contains($nameLower, $kw)) {
                    continue 2;
                }
            }

            $osmValue = $props['osm_value'] ?? '';
            if (! str_contains($nameLower, strtolower($bizType)) && ! in_array($osmValue, ['office', 'lawyer'])) {
                continue;
            }

            $unique[] = $feature;
        }

        return $unique;
    }

    protected function extractPhotonLead(array $feature, string $area, string $bizType): array
    {
        $props = $feature['properties'] ?? [];
        $coords = $feature['geometry']['coordinates'] ?? [];

        $addressParts = array_filter([
            $props['housenumber'] ?? '',
            $props['street'] ?? '',
            $props['city'] ?? '',
            $props['state'] ?? '',
            $props['postcode'] ?? '',
        ]);

        return [
            'company_name' => $props['name'] ?? '',
            'category' => $props['osm_value'] ?? $bizType,
            'email' => $props['email'] ?? $props['contact:email'] ?? '',
            'phone' => $props['phone'] ?? $props['contact:phone'] ?? '',
            'website' => $props['website'] ?? $props['contact:website'] ?? '',
            'address' => implode(' ', $addressParts),
            'area' => $area,
            'lat' => $coords[1] ?? null,
            'lon' => $coords[0] ?? null,
        ];
    }

    protected function searchGooglePlaces(string $area, string $bizType, string $apiKey): array
    {
        $queries = [
            "{$bizType} in {$area}",
            "{$bizType} near {$area}",
            "best {$bizType} in {$area}",
        ];

        $allPlaces = [];
        $fieldMask = 'places.displayName,places.formattedAddress,places.nationalPhoneNumber,places.websiteUri,places.rating,places.userRatingCount,places.location,places.primaryType,places.businessStatus,nextPageToken';

        foreach ($queries as $query) {
            Log::info("Google search: {$query}");

            try {
                $pageToken = null;
                $page = 0;

                do {
                    $body = [
                        'textQuery' => $query,
                        'maxResultCount' => 20,
                    ];

                    if ($pageToken) {
                        $body['pageToken'] = $pageToken;
                    }

                    $response = Http::timeout(30)
                        ->withHeaders([
                            'Content-Type' => 'application/json',
                            'X-Goog-Api-Key' => $apiKey,
                            'X-Goog-FieldMask' => $fieldMask,
                        ])
                        ->post(self::GOOGLE_SEARCH_URL, $body);

                    $response->throw();
                    $result = $response->json();
                    $places = $result['places'] ?? [];
                    $allPlaces = array_merge($allPlaces, $places);
                    Log::info('  Found '.count($places).' places');

                    $pageToken = $result['nextPageToken'] ?? null;
                    $page++;

                    if ($pageToken) {
                        usleep(1000000);
                    }
                } while ($pageToken && $page < 3);

                usleep(1000000);
            } catch (\Exception $e) {
                Log::warning("Google search '{$query}' failed: ".$e->getMessage());
            }
        }

        $unique = [];
        foreach ($allPlaces as $place) {
            $name = $place['displayName']['text'] ?? '';
            if ($name && ! isset($unique[$name])) {
                $unique[$name] = $place;
            }
        }

        $leads = [];
        foreach ($unique as $place) {
            $lead = $this->extractGoogleLead($place, $area, $bizType);
            if (! empty($lead['company_name'])) {
                $leads[] = $lead;
            }
        }

        Log::info('Found '.count($leads).' leads from Google Places');

        return $leads;
    }

    protected function extractGoogleLead(array $place, string $area, string $bizType): array
    {
        $location = $place['location'] ?? [];

        return [
            'company_name' => $place['displayName']['text'] ?? '',
            'category' => $place['primaryType'] ?? $bizType,
            'email' => '',
            'phone' => $place['nationalPhoneNumber'] ?? '',
            'website' => $place['websiteUri'] ?? '',
            'address' => $place['formattedAddress'] ?? '',
            'area' => $area,
            'lat' => $location['latitude'] ?? null,
            'lon' => $location['longitude'] ?? null,
            'rating' => $place['rating'] ?? null,
            'total_ratings' => $place['userRatingCount'] ?? null,
        ];
    }
}
