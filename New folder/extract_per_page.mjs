import { readFileSync, writeFileSync } from 'fs';

const sql = readFileSync('C:/xampp/htdocs/indesign/New folder/u469743545_PwsbI.sql', 'utf8');

// Extract wp_posts rows - focus on pages and attachments
// Pages we care about: administrative, commercial, residential, exterior, and project pages
const pageContentRegex = /\((\d+),\s*\d+,\s*'[^']*',\s*'[^']*',\s*'([\s\S]*?)',\s*'([^']*)',\s*'[^']*',\s*'(publish|inherit)[^']*',.*?'(page|attachment|post)'.*?\)/g;

const imgUrlRegex = /https:\/\/indesign-co\.com\/wp-content\/uploads\/[\w\/\-\.]+\.(?:jpg|jpeg|png|gif|webp)/gi;

const pages = {};
let m;

// Simpler approach: extract rows per known page slugs
const knownSlugs = {
    'administrative': 'Administrative',
    'commercial': 'Commercial', 
    'residential': 'Residential',
    'exterior': 'Exterior',
    'la-vida-salon': 'La Vida Salon',
    'al-abdali-farm': 'Al Abdali Farm',
    'al-seef-hospital': 'Al Seef Hospital',
    'alhadi-hospital': 'Alhadi Hospital',
    'international-hospital': 'International Hospital',
    'mountain-view-2': 'Mountain View',
    'mountain-view': 'Mountain View'
};

// Split by INSERT statements and process per row
const rows = sql.split(/\),\s*\n\s*\(/);

for (const [slug, name] of Object.entries(knownSlugs)) {
    const slugPattern = new RegExp(`'${slug}'`, 'i');
    const matchingRows = rows.filter(r => slugPattern.test(r));
    
    if (matchingRows.length > 0) {
        const allImgs = [];
        for (const row of matchingRows) {
            const imgs = row.match(imgUrlRegex) || [];
            // Only keep original (non-resized) images
            const originals = imgs.filter(img => !/-\d+x\d+\./.test(img));
            allImgs.push(...originals);
        }
        const unique = [...new Set(allImgs)].sort();
        pages[name] = unique;
        console.log(`${name}: ${unique.length} images`);
        unique.forEach(u => console.log('  ', u));
    }
}

writeFileSync('C:/xampp/htdocs/indesign/New folder/page_images.json', JSON.stringify(pages, null, 2), 'utf8');
console.log('\nSaved to page_images.json');
