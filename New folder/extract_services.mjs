import { readFileSync, writeFileSync } from 'fs';

const sql = readFileSync('C:/xampp/htdocs/indesign/New folder/u469743545_PwsbI.sql', 'utf8');
const imgUrlRegex = /https:\/\/indesign-co\.com\/wp-content\/uploads\/[\w\/\-\.]+\.(?:jpg|jpeg|png|gif|webp)/gi;

// Known service slugs on the WP site
const services = {
    'administrative': { slug: 'administrative', title_en: 'Administrative', title_ar: 'إداري' },
    'commercial':     { slug: 'commercial',     title_en: 'Commercial',     title_ar: 'تجاري' },
    'residential':    { slug: 'residential',    title_en: 'Residential',    title_ar: 'سكني' },
    'exterior':       { slug: 'exterior',       title_en: 'Exterior',       title_ar: 'خارجي' },
};

// Find rows by slug proximity
const rows = sql.split(/(?=\(\d+,\s*\d+,\s*'20)/);

const result = {};
for (const [key, info] of Object.entries(services)) {
    const pattern = new RegExp(`'${key}'`, 'i');
    const matching = rows.filter(r => pattern.test(r));
    const allImgs = [];
    for (const row of matching) {
        const imgs = (row.match(imgUrlRegex) || []).filter(u => !/-\d+x\d+\./.test(u));
        allImgs.push(...imgs);
    }
    result[key] = { ...info, images: [...new Set(allImgs)] };
    console.log(`${key}: ${result[key].images.length} images`);
}

writeFileSync('C:/xampp/htdocs/indesign/New folder/services_from_db.json', JSON.stringify(result, null, 2), 'utf8');
console.log('\nDone. Saved to services_from_db.json');
