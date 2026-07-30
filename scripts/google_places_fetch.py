import argparse
import json
import sys
import time
import requests

GOOGLE_SEARCH_URL = "https://places.googleapis.com/v1/places:searchText"
GOOGLE_DETAILS_URL = "https://places.googleapis.com/v1/places/"
HEADERS = {"User-Agent": "lead-finder-laravel/1.0"}


def search_google_places(query, api_key, page_token=None):
    """Search Google Places API (New)"""
    headers = {
        "Content-Type": "application/json",
        "X-Goog-Api-Key": api_key,
        "X-Goog-FieldMask": "places.displayName,places.formattedAddress,places.nationalPhoneNumber,places.websiteUri,places.rating,places.userRatingCount,places.location,places.primaryType,places.businessStatus,nextPageToken",
    }
    
    body = {
        "textQuery": query,
        "maxResultCount": 20,
    }
    
    if page_token:
        body["pageToken"] = page_token
    
    resp = requests.post(GOOGLE_SEARCH_URL, headers=headers, json=body, timeout=30)
    resp.raise_for_status()
    return resp.json()


def search_google(area, biz_type, api_key):
    """Search Google Places and get full details"""
    all_results = []
    
    queries = [
        f"{biz_type} in {area}",
        f"{biz_type} near {area}",
        f"best {biz_type} in {area}",
    ]
    
    for query in queries:
        print(f"Google search: {query}", file=sys.stderr)
        
        try:
            result = search_google_places(query, api_key)
            
            places = result.get("places", [])
            all_results.extend(places)
            print(f"  Found {len(places)} places", file=sys.stderr)
            
            next_token = result.get("nextPageToken")
            page = 1
            while next_token and page < 3:
                page += 1
                print(f"  Getting page {page}...", file=sys.stderr)
                result = search_google_places(query, api_key, next_token)
                places = result.get("places", [])
                all_results.extend(places)
                next_token = result.get("nextPageToken")
                time.sleep(1)
            
            time.sleep(1)
            
        except requests.exceptions.HTTPError as e:
            error_body = e.response.json() if e.response else {}
            print(f"  HTTP Error: {e.response.status_code} - {error_body.get('error', {}).get('message', str(e))}", file=sys.stderr)
            continue
        except Exception as e:
            print(f"  Search failed: {e}", file=sys.stderr)
            continue
    
    unique = {}
    for p in all_results:
        name = p.get("displayName", {}).get("text", "")
        if name and name not in unique:
            unique[name] = p
    
    return list(unique.values())


def extract_lead(place, area):
    """Extract lead data from Google Places result"""
    name = place.get("displayName", {}).get("text", "")
    address = place.get("formattedAddress", "")
    phone = place.get("nationalPhoneNumber", "")
    website = place.get("websiteUri", "")
    rating = place.get("rating", "")
    total_ratings = place.get("userRatingCount", "")
    location = place.get("location", {})
    primary_type = place.get("primaryType", "")
    
    return {
        "company_name": name,
        "category": biz_type_global if not primary_type else primary_type,
        "email": "",
        "phone": phone or "",
        "website": website or "",
        "address": address,
        "area": area,
        "lat": location.get("latitude"),
        "lon": location.get("longitude"),
        "rating": rating if rating else None,
        "total_ratings": total_ratings if total_ratings else None,
    }


biz_type_global = ""

def main():
    global biz_type_global
    
    parser = argparse.ArgumentParser()
    parser.add_argument("--area", required=True)
    parser.add_argument("--type", required=True)
    parser.add_argument("--out", required=True)
    parser.add_argument("--api-key", required=True, help="Google Places API key")
    args = parser.parse_args()
    
    biz_type_global = args.type

    print(f"Searching Google Places (New API): {args.type} in {args.area}", file=sys.stderr)
    places = search_google(args.area, args.type, args.api_key)
    
    leads = []
    for place in places:
        lead = extract_lead(place, args.area)
        if lead["company_name"]:
            leads.append(lead)
    
    print(f"Found {len(leads)} leads", file=sys.stderr)
    
    with open(args.out, "w", encoding="utf-8") as f:
        json.dump(leads, f, ensure_ascii=False, indent=2)
    
    print(f"Saved to {args.out}", file=sys.stderr)


if __name__ == "__main__":
    main()
