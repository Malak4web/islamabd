import json
import sys

# Force UTF-8 output
sys.stdout.reconfigure(encoding='utf-8')

with open(r"c:\xampp\htdocs\islamabd\.agents\teamwork_preview_challenger_m2_2\verification_results.json", "r", encoding="utf-8") as f:
    data = json.load(f)

print("--- DB MATCH SUMMARY BY TABLE ---")
for table_name, table_info in data["db_tables"].items():
    if table_info["match_count"] > 0:
        print(f"\nTable '{table_name}': {table_info['match_count']} matches")
        for m in table_info["matches"]:
            val_snippet = m['value'][:120].replace('\n', ' ')
            print(f"  Row {m['row_index']} | Col: {m['column']} | Matches: {m['matches']}")
            print(f"    Val snippet: {val_snippet}...")

print("\n--- SEEDERS MATCH SUMMARY ---")
for seeder, s_info in data["seeders"].items():
    print(f"Seeder '{seeder}': {s_info['matches_count']} matches")
    if s_info['matches_count'] > 0:
        print(f"  Matches: {s_info['matches']}")

print("\n--- CONTROLLERS MATCH SUMMARY ---")
for ctrl, c_info in data["controllers"].items():
    print(f"Controller '{ctrl}': {c_info['matches_count']} matches")
    if c_info['matches_count'] > 0:
        print(f"  Matches: {c_info['matches']}")
