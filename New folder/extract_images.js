const fs = require('fs');
const content = fs.readFileSync('C:\\xampp\\htdocs\\indesign\\New folder\\u469743545_PwsbI.sql', 'utf8');
const regex = /https:\/\/indesign-co\.com\/wp-content\/uploads\/[^\s"'\\]+\.(jpg|jpeg|png|gif|webp)/gi;
const matches = [...new Set(content.match(regex) || [])].sort();
fs.writeFileSync('C:\\xampp\\htdocs\\indesign\\New folder\\all_images.txt', matches.join('\n'), 'utf8');
console.log('Found', matches.length, 'unique images');
// Also extract by page/post context
// Look for page titles with their image galleries
const pageRegex = /post_title.*?'([^']+)'.*?post_type.*?'page'/gi;
let pm;
while((pm = pageRegex.exec(content)) !== null) {
    console.log('Page:', pm[1]);
}
