const fs = require('fs');
const path = require('path');

function getAllVueFiles(dir, fileList = []) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    const filePath = path.join(dir, file);
    if (fs.statSync(filePath).isDirectory()) {
      getAllVueFiles(filePath, fileList);
    } else if (file.endsWith('.vue')) {
      fileList.push(filePath);
    }
  }
  return fileList;
}

const vueFiles = getAllVueFiles('c:/xampp/htdocs/islamabd/resources/js');

console.log(`=== DEEP ICON & DECORATIVE ELEMENT HARMONIZATION AUDIT ===\n`);

let totalIconsFound = 0;
let suspectIcons = [];
let allIconsList = [];

vueFiles.forEach(file => {
  const content = fs.readFileSync(file, 'utf8');
  const relPath = path.relative('c:/xampp/htdocs/islamabd', file);
  
  // Extract template section
  const templateMatch = content.match(/<template>([\s\S]*)<\/template>/);
  if (!templateMatch) return;

  const template = templateMatch[1];
  const lines = template.split('\n');

  lines.forEach((line, idx) => {
    const lineNum = idx + 1;
    
    // Check if line contains an SVG, Lucide icon or icon-like element
    const isSvg = line.includes('<svg') || line.includes('</svg>') || line.includes('<path');
    const isIconComp = line.match(/<([A-Z][a-zA-Z0-9]*Icon|[A-Z][a-zA-Z0-9]*Lucide|Chevron|Arrow|Check|XMark|Plus|Trash|Edit|Eye|Search|Filter|Mail|Phone|MapPin|Grid|List|Image|Upload|Settings|User|Lock|Log|Menu|Sparkles|Globe|Share|Instagram|Facebook|Linkedin|Twitter|Youtube|Clock|Calendar|Folder|Tag|Dollar|Award|Layers|BarChart)/);
    const isDecorativeLine = line.match(/h-\[1px\]|w-\[1px\]|h-[0-9]+|w-[0-9]+/);

    if (isSvg || isIconComp) {
      totalIconsFound++;
      allIconsList.push({
        file: relPath,
        line: lineNum,
        code: line.trim()
      });

      // Check for suspicious colors (legacy dark colors, Tailwind default grays on icons, blues, etc.)
      const hasGray = line.match(/text-gray-[0-9]+|bg-gray-[0-9]+|border-gray-[0-9]+/);
      const hasBlack = line.match(/text-black|bg-black\/[0-9]+|from-black|via-black|to-black/);
      const hasDarkHex = line.match(/#[0-9a-fA-F]{3,6}/g);

      let hexIssues = [];
      if (hasDarkHex) {
        hasDarkHex.forEach(hex => {
          const upper = hex.toUpperCase();
          // Allowed hexes: #F7F5F0, #F0ECE1, #E0DACE, #111111, #222222, #C5A880, #444444, #666666, #555555, #FFFFFF, #FFF
          if (!['#F7F5F0', '#F0ECE1', '#E0DACE', '#111111', '#222222', '#C5A880', '#444444', '#666666', '#555555', '#FFFFFF', '#FFF', '#F3F4F6'].includes(upper)) {
            hexIssues.push(hex);
          }
        });
      }

      if (hasGray || hasBlack || hexIssues.length > 0) {
        suspectIcons.push({
          file: relPath,
          line: lineNum,
          code: line.trim(),
          reasons: [
            hasGray ? `Tailwind gray class: ${hasGray[0]}` : null,
            hasBlack ? `Black class: ${hasBlack[0]}` : null,
            hexIssues.length > 0 ? `Unapproved hex: ${hexIssues.join(', ')}` : null
          ].filter(Boolean)
        });
      }
    }
  });
});

console.log(`Total Icon / SVG elements inspected: ${totalIconsFound}`);
console.log(`Suspect Icon / SVG elements found: ${suspectIcons.length}\n`);

if (suspectIcons.length > 0) {
  console.log('SUSPECT ELEMENTS:');
  console.log(JSON.stringify(suspectIcons, null, 2));
} else {
  console.log('No suspect icon elements found matching quick criteria.');
}
