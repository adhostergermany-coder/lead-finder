<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class OsmService
{
    protected string $pythonPath = 'python';
    protected string $photonScript;
    protected string $googleScript;

    public function __construct()
    {
        $this->photonScript = base_path('scripts/osm_fetch.py');
        $this->googleScript = base_path('scripts/google_places_fetch.py');
    }

    public function searchAndSave(string $area, string $bizType, ?string $apiKey = null): array
    {
        $outputFile = storage_path('app/temp_leads_' . uniqid() . '.json');

        if ($apiKey) {
            $script = $this->googleScript;
            $escapedKey = escapeshellarg($apiKey);
            $extraArgs = "--api-key {$escapedKey}";
        } else {
            $script = $this->photonScript;
            $extraArgs = "";
        }

        $escapedArea = escapeshellarg($area);
        $escapedType = escapeshellarg($bizType);
        $escapedOut = escapeshellarg($outputFile);
        $escapedScript = escapeshellarg($script);

        $command = "{$this->pythonPath} {$escapedScript} --area {$escapedArea} --type {$escapedType} --out {$escapedOut} {$extraArgs}";

        Log::info("Running: {$command}");

        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);

        $outputText = implode("\n", $output);
        Log::info("Python output: {$outputText}");

        if ($returnCode !== 0) {
            throw new \Exception("Script failed: {$outputText}");
        }

        if (!file_exists($outputFile)) {
            throw new \Exception("Output file not created. Output: {$outputText}");
        }

        $json = file_get_contents($outputFile);
        $leads = json_decode($json, true);

        @unlink($outputFile);

        return $leads ?? [];
    }
}
