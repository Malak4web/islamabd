import sqlite3
import os
import json
import re

db_path = r"c:\xampp\htdocs\islamabd\database\database.sqlite"
output_report = {}

search_patterns = [
    r"InDesign",
    r"In Design",
    r"INDESIGN",
    r"indesign",
    r"إن ديزاين",
    r"ان ديزاين",
    r"ان ديزين",
    r"indesign-co\.com",
    r"indesign\.com",
    r"info@indesign\.com",
    r"contact@indesign\.com"
]
regex_combined = re.compile("|".join(search_patterns), re.IGNORECASE)

print(f"Connecting to database at {db_path}...")
conn = sqlite3.connect(db_path)
cursor = conn.cursor()

# Get all table names
cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
tables = [row[0] for row in cursor.fetchall()]

db_results = {}
total_matches = 0

for table in tables:
    if table.startswith("sqlite_"):
        continue
    cursor.execute(f"PRAGMA table_info({table});")
    columns = [col[1] for col in cursor.fetchall()]
    
    table_matches = []
    cursor.execute(f"SELECT * FROM {table};")
    rows = cursor.fetchall()
    
    for row_idx, row in enumerate(rows):
        for col_idx, val in enumerate(row):
            if val is not None:
                str_val = str(val)
                matches = regex_combined.findall(str_val)
                if matches:
                    col_name = columns[col_idx]
                    table_matches.append({
                        "row_index": row_idx,
                        "column": col_name,
                        "value": str_val,
                        "matches": matches
                    })
                    total_matches += len(matches)
    
    db_results[table] = {
        "column_count": len(columns),
        "row_count": len(rows),
        "columns": columns,
        "match_count": len(table_matches),
        "matches": table_matches
    }

print(f"Total DB matches across all tables: {total_matches}")

# Inspect key tables specifically
key_tables = ['settings', 'sections', 'pages', 'admins', 'services', 'projects']
key_tables_summary = {}

for kt in key_tables:
    if kt in tables:
        cursor.execute(f"SELECT * FROM {kt};")
        rows = cursor.fetchall()
        cursor.execute(f"PRAGMA table_info({kt});")
        columns = [col[1] for col in cursor.fetchall()]
        
        row_dicts = []
        for r in rows:
            row_dict = {}
            for col_i, col_n in enumerate(columns):
                row_dict[col_n] = r[col_i]
            row_dicts.append(row_dict)
            
        key_tables_summary[kt] = row_dicts

# Now check seeders
seeders_dir = r"c:\xampp\htdocs\islamabd\database\seeders"
seeder_files = [f for f in os.listdir(seeders_dir) if f.endswith(".php")]
seeder_results = {}

for sf in seeder_files:
    file_path = os.path.join(seeders_dir, sf)
    with open(file_path, "r", encoding="utf-8", errors="ignore") as f:
        content = f.read()
    matches = regex_combined.findall(content)
    seeder_results[sf] = {
        "matches_count": len(matches),
        "matches": matches
    }

# Now check Api Controllers
controllers_dir = r"c:\xampp\htdocs\islamabd\app\Http\Controllers"
controller_results = {}

for root, dirs, files in os.walk(controllers_dir):
    for file in files:
        if file.endswith(".php"):
            file_path = os.path.join(root, file)
            rel_path = os.path.relpath(file_path, controllers_dir)
            with open(file_path, "r", encoding="utf-8", errors="ignore") as f:
                content = f.read()
            matches = regex_combined.findall(content)
            controller_results[rel_path] = {
                "matches_count": len(matches),
                "matches": matches
            }

report = {
    "total_db_matches": total_matches,
    "db_tables": db_results,
    "key_tables_contents": key_tables_summary,
    "seeders": seeder_results,
    "controllers": controller_results
}

with open(r"c:\xampp\htdocs\islamabd\.agents\teamwork_preview_challenger_m2_2\verification_results.json", "w", encoding="utf-8") as f:
    json.dump(report, f, ensure_ascii=False, indent=2)

print("Verification completed. Output saved to verification_results.json")
