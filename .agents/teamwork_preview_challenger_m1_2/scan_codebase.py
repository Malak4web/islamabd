import os
import re

terms = [
    r"InDesign",
    r"In Design",
    r"إن ديزاين",
    r"ان ديزين",
    r"indesign",
    r"indesign-co",
    r"indesign\.com"
]

pattern = re.compile("|".join(terms), re.IGNORECASE)

dirs_to_check = [
    r"c:\xampp\htdocs\islamabd\resources\js",
    r"c:\xampp\htdocs\islamabd\resources\views"
]

matches = []

for d in dirs_to_check:
    for root, _, files in os.walk(d):
        for f in files:
            if f.endswith(('.vue', '.js', '.json', '.blade.php')):
                path = os.path.join(root, f)
                with open(path, 'r', encoding='utf-8', errors='ignore') as file:
                    for line_num, line in enumerate(file, 1):
                        found = pattern.findall(line)
                        if found:
                            matches.append((path, line_num, line.strip(), found))

print(f"Total old branding occurrences found: {len(matches)}")
for path, line_num, line, found in matches:
    print(f"{path}:{line_num} -> {found} in: {line}")
