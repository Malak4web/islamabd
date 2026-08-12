import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')
conn = sqlite3.connect(r"c:\xampp\htdocs\islamabd\database\database.sqlite")
cursor = conn.cursor()
cursor.execute("SELECT id, `key`, `value`, `group` FROM settings;")
rows = cursor.fetchall()
for r in rows:
    print(f"ID: {r[0]} | Key: {r[1]} | Group: {r[3]} | Value: {r[2]}")
