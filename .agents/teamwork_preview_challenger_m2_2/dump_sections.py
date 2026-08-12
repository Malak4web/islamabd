import sqlite3
import sys
import json

sys.stdout.reconfigure(encoding='utf-8')
conn = sqlite3.connect(r"c:\xampp\htdocs\islamabd\database\database.sqlite")
cursor = conn.cursor()
cursor.execute("PRAGMA table_info(sections);")
cols = [c[1] for c in cursor.fetchall()]
print("Sections columns:", cols)

cursor.execute(f"SELECT * FROM sections;")
rows = cursor.fetchall()
for r in rows:
    row_dict = {cols[i]: r[i] for i in range(len(cols))}
    print(f"\nID: {row_dict.get('id')} | Page: {row_dict.get('page_id')} | Section: {row_dict.get('section_name', row_dict.get('key'))}")
    if 'content' in row_dict and row_dict['content']:
        try:
            content_json = json.loads(row_dict['content'])
            print(json.dumps(content_json, ensure_ascii=False, indent=2))
        except Exception:
            print(row_dict['content'])
