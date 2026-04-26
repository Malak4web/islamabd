import fs from 'fs';

const sqlData = fs.readFileSync('C:\\xampp\\htdocs\\indesign\\New folder\\u469743545_PwsbI.sql', 'utf8');

const regex = /\((\d+),\s*\d+,\s*'[^']*',\s*'[^']*',\s*'([^']*)',\s*'([^']*)',\s*'([^']*)',\s*'[^']*',\s*'[^']*',\s*'[^']*',\s*'[^']*',\s*'[^']*',\s*'[^']*',\s*'[^']*',\s*'[^']*',\s*'[^']*',\s*'[^']*',\s*\d+,\s*'[^']*',\s*'[^']*',\s*'page'/g;

let matches;
let found = 0;
while ((matches = regex.exec(sqlData)) !== null) {
    const id = matches[1];
    const content = matches[2];
    const title = matches[3];
    const excerpt = matches[4];
    
    fs.appendFileSync('C:\\xampp\\htdocs\\indesign\\New folder\\pages_dump.txt', `--- PAGE ID: ${id} | TITLE: ${title} ---\n${content.substring(0, 500)}...\n\n`);
    found++;
}
console.log(`Found ${found} pages.`);
