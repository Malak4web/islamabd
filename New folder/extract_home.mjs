import fs from 'fs';

const sqlData = fs.readFileSync('C:\\xampp\\htdocs\\indesign\\New folder\\u469743545_PwsbI.sql', 'utf8');
const lines = sqlData.split('\n');

for (const line of lines) {
    if (line.includes('INSERT INTO `wp_posts`')) {
        // Look for 'Home' or 'الرئيسية' post titles. In SQL dump they might be part of large INSERTS
        if (line.includes("'Home'") || line.includes('الرئيسية') || line.includes('Home Page')) {
            fs.appendFileSync('C:\\xampp\\htdocs\\indesign\\New folder\\home_lines.txt', line + '\n\n');
        }
    }
}
console.log("Done.");
