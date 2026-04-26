import { readFileSync, writeFileSync } from 'fs';
const content = readFileSync('C:/xampp/htdocs/indesign/New folder/u469743545_PwsbI.sql', 'utf8');
const regex = /https:\/\/indesign-co\.com\/wp-content\/uploads\/[^\s"'\\]+\.(jpg|jpeg|png|gif|webp)/gi;
const matches = [...new Set(content.match(regex) || [])].sort();
writeFileSync('C:/xampp/htdocs/indesign/New folder/all_images.txt', matches.join('\n'), 'utf8');
console.log('Found', matches.length, 'unique images');
// Show sample
matches.slice(0, 20).forEach(m => console.log(m));
