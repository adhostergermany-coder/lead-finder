import argparse
import json
import sys
import time
import requests

NOMINATIM_URL = "https://nominatim.openstreetmap.org/search"
PHOTON_URL = "https://photon.komoot.io/api/"
HEADERS = {"User-Agent": "lead-finder-laravel/1.0 (lead-finder-app)"}

EXCLUDE_KEYWORDS = [
    "police", "fire station", "fire department", "laboratory", "lab ",
    "parking", "tunnel", "constituency", "depot", "university", "lot ",
    "service centre", "service center", "auto service", "auto parts",
    "service road", "service building", "service tunnel", "church",
    "church of", "presbyterian", "financial", "credit union", "savings",
    "film studio", "movie", "studio", "paint protection", "upholstery",
    "vacuum store", "messenger", "tax service", "real estate",
    "crescent", "boulevard", "avenue", "drive", "road", "close",
    "way", "gate", "trail", "ridge", "crest", "court",
]

GENERIC_NAMES = [
    "lawyer office", "lawyer's office", "office", "service",
    "law office", "legal office",
]


def get_bounding_box(area_name):
    params = {"q": area_name, "format": "json", "limit": 1}
    resp = requests.get(NOMINATIM_URL, params=params, headers=HEADERS, timeout=30)
    resp.raise_for_status()
    results = resp.json()
    
    if not results:
        raise ValueError(f"'{area_name}' not found")
    
    bbox = results[0]["boundingbox"]
    return {
        "south": float(bbox[0]),
        "west": float(bbox[2]),
        "north": float(bbox[1]),
        "east": float(bbox[3]),
        "display": results[0].get("display_name", area_name),
    }


def search_businesses(bbox, biz_type, area_name):
    all_features = []
    city = area_name.split(",")[0].strip()
    
    queries = [
        f"{biz_type} {area_name}",
        f"{biz_type} office {city}",
        f"{biz_type} firm {city}",
    ]
    
    for query in queries:
        try:
            params = {"q": query, "limit": 50, "lang": "en"}
            resp = requests.get(PHOTON_URL, params=params, headers=HEADERS, timeout=30)
            resp.raise_for_status()
            data = resp.json()
            features = data.get("features", [])
            all_features.extend(features)
            print(f"Query '{query}': {len(features)} results", file=sys.stderr)
            time.sleep(0.5)
        except Exception as e:
            print(f"Query '{query}' failed: {e}", file=sys.stderr)
            continue
    
    seen_ids = set()
    unique_features = []
    city_lower = city.lower()
    
    for f in all_features:
        props = f.get("properties", {})
        osm_id = props.get("osm_id")
        name = props.get("name", "")
        osm_value = props.get("osm_value", "")
        
        if not osm_id or osm_id in seen_ids or not name:
            continue
        seen_ids.add(osm_id)
        
        feature_city = (props.get("city") or "").lower()
        if city_lower not in feature_city:
            continue
        
        name_lower = name.lower().strip()
        
        if name_lower in [g.lower() for g in GENERIC_NAMES]:
            continue
        
        if any(kw in name_lower for kw in EXCLUDE_KEYWORDS):
            continue
        
        if biz_type.lower() not in name_lower and osm_value not in ["office", "lawyer"]:
            continue
        
        unique_features.append(f)
    
    return unique_features


def extract_lead(feature, area):
    props = feature.get("properties", {})
    coords = feature.get("geometry", {}).get("coordinates", [])
    
    address_parts = [
        props.get("housenumber", ""),
        props.get("street", ""),
        props.get("city", ""),
        props.get("state", ""),
        props.get("postcode", ""),
    ]
    
    return {
        "company_name": props.get("name", ""),
        "category": props.get("osm_value") or biz_type_global,
        "email": props.get("email") or props.get("contact:email", ""),
        "phone": props.get("phone") or props.get("contact:phone", ""),
        "website": props.get("website") or props.get("contact:website", ""),
        "address": " ".join(p for p in address_parts if p).strip(),
        "area": area,
        "lat": coords[1] if len(coords) > 1 else None,
        "lon": coords[0] if len(coords) > 0 else None,
    }


biz_type_global = ""

def main():
    global biz_type_global
    
    parser = argparse.ArgumentParser()
    parser.add_argument("--area", required=True)
    parser.add_argument("--type", required=True)
    parser.add_argument("--out", required=True)
    args = parser.parse_args()
    
    biz_type_global = args.type

    print(f"Geocoding: {args.area}", file=sys.stderr)
    bbox = get_bounding_box(args.area)
    print(f"Area: {bbox['display']}", file=sys.stderr)
    
    time.sleep(1)
    
    print(f"Searching: {args.type}", file=sys.stderr)
    features = search_businesses(bbox, args.type, args.area)
    
    leads = []
    for feature in features:
        lead = extract_lead(feature, args.area)
        if lead["company_name"]:
            leads.append(lead)
    
    print(f"Found {len(leads)} leads", file=sys.stderr)
    
    with open(args.out, "w", encoding="utf-8") as f:
        json.dump(leads, f, ensure_ascii=False, indent=2)
    
    print(f"Saved to {args.out}", file=sys.stderr)


if __name__ == "__main__":
    main()
