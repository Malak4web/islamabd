import json
import sys
import os

sys.stdout.reconfigure(encoding='utf-8')

def check_keys(d1, d2, path=""):
    diffs = []
    keys1 = set(d1.keys()) if isinstance(d1, dict) else set()
    keys2 = set(d2.keys()) if isinstance(d2, dict) else set()
    
    only_in_1 = keys1 - keys2
    only_in_2 = keys2 - keys1
    
    for k in only_in_1:
        diffs.append(f"Key '{path}{k}' present in EN but missing in AR")
    for k in only_in_2:
        diffs.append(f"Key '{path}{k}' present in AR but missing in EN")
        
    for k in keys1.intersection(keys2):
        if isinstance(d1[k], dict) and isinstance(d2[k], dict):
            diffs.extend(check_keys(d1[k], d2[k], path=f"{path}{k}."))
        elif isinstance(d1[k], dict) != isinstance(d2[k], dict):
            diffs.append(f"Type mismatch for key '{path}{k}': dict vs non-dict")
            
    return diffs

def find_forbidden_terms(d, terms, path=""):
    findings = []
    if isinstance(d, dict):
        for k, v in d.items():
            findings.extend(find_forbidden_terms(v, terms, path=f"{path}{k}."))
    elif isinstance(d, str):
        for t in terms:
            if t.lower() in d.lower():
                findings.append(f"Forbidden term '{t}' found at '{path[:-1]}': {d}")
    return findings

def check_arabic_escaping(d, path=""):
    issues = []
    if isinstance(d, dict):
        for k, v in d.items():
            issues.extend(check_arabic_escaping(v, path=f"{path}{k}."))
    elif isinstance(d, str):
        if "\ufffd" in d:
            issues.append(f"Unicode replacement character found at '{path[:-1]}': {d}")
    return issues

def main():
    en_path = r"c:\xampp\htdocs\islamabd\resources\js\i18n\en.json"
    ar_path = r"c:\xampp\htdocs\islamabd\resources\js\i18n\ar.json"
    
    print("--- Testing JSON Parsing ---")
    try:
        with open(en_path, "r", encoding="utf-8") as f:
            en_data = json.load(f)
        print("en.json is valid JSON.")
    except Exception as e:
        print(f"ERROR: en.json is invalid: {e}")
        sys.exit(1)
        
    try:
        with open(ar_path, "r", encoding="utf-8") as f:
            ar_data = json.load(f)
        print("ar.json is valid JSON.")
    except Exception as e:
        print(f"ERROR: ar.json is invalid: {e}")
        sys.exit(1)

    print("\n--- Testing Key Parity ---")
    key_diffs = check_keys(en_data, ar_data)
    if key_diffs:
        print(f"FAILED: Found {len(key_diffs)} key parity mismatch(es):")
        for d in key_diffs:
            print(f"  - {d}")
    else:
        print("PASSED: Key parity is 100% complete between en.json and ar.json.")

    print("\n--- Testing Forbidden Branding Terms ---")
    forbidden = ["InDesign", "In Design", "إن ديزاين", "ان ديزين", "indesign"]
    en_forbidden = find_forbidden_terms(en_data, forbidden)
    ar_forbidden = find_forbidden_terms(ar_data, forbidden)
    
    if en_forbidden or ar_forbidden:
        print("FAILED: Forbidden old branding terms found!")
        for f in en_forbidden:
            print(f"  - [EN] {f}")
        for f in ar_forbidden:
            print(f"  - [AR] {f}")
    else:
        print("PASSED: Zero forbidden old branding terms in en.json and ar.json.")

    print("\n--- Checking New Branding Terms ---")
    en_str = json.dumps(en_data, ensure_ascii=False)
    ar_str = json.dumps(ar_data, ensure_ascii=False)
    print(f"Occurrences of 'Eslam Abdulghani Designs' in en.json: {en_str.count('Eslam Abdulghani Designs')}")
    print(f"Occurrences of 'إسلام عبد الغني ديزاينز' in ar.json: {ar_str.count('إسلام عبد الغني ديزاينز')}")

    print("\n--- Checking Arabic Escaping & Encoding Issues ---")
    ar_issues = check_arabic_escaping(ar_data)
    if ar_issues:
        print("FAILED: Encoding/escaping issues found in AR:")
        for i in ar_issues:
            print(f"  - {i}")
    else:
        print("PASSED: No Unicode replacement or escaping errors found in ar.json.")

if __name__ == "__main__":
    main()
