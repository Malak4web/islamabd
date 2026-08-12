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

console.log(`Found ${vueFiles.length} Vue files.`);

let issueCount = 0;
const results = [];

// Regular expressions to catch icon usage, svgs, lucide components, decorative lines, and color usages
vueFiles.forEach(file => {
  const content = fs.readFileSync(file, 'utf8');
  const relPath = path.relative('c:/xampp/htdocs/islamabd', file);
  
  const lines = content.split('\n');
  lines.forEach((line, idx) => {
    const lineNum = idx + 1;

    // Check for inline SVG tags
    if (line.includes('<svg') || line.includes('<path') || line.includes('<circle') || line.includes('<rect') || line.includes('<line') || line.includes('<polyline')) {
      // Check for non-harmonized colors in inline SVG attributes or classes
      const darkOrDisallowed = line.match(/(fill|stroke|text)-(#141414|#333333|#333|#888888|#888|black|gray-[0-9]+|blue-[0-9]+|red-[0-9]+)/i);
      if (darkOrDisallowed) {
        results.push({ file: relPath, line: lineNum, type: 'SVG', content: line.trim(), issue: `Disallowed color in SVG: ${darkOrDisallowed[0]}` });
        issueCount++;
      }
    }

    // Check for Lucide icons or Icon components
    if (line.match(/<(Icon|[A-Z][a-zA-Z0-9]*Icon|Chevron|Arrow|Check|X|Plus|Edit|Trash|Eye|Search|Filter|Mail|Phone|Map|Grid|List|Image|Upload|Settings|User|Lock|Log|Menu|Sparkles|Globe|Share|Instagram|Facebook|Linkedin|Twitter|Youtube|Clock|Calendar|Folder|Tag|Dollar|Award|Layers|BarChart)/)) {
      // Check if icon has unharmonized color classes
      const darkOrDisallowed = line.match(/(text|fill|stroke)-(#141414|#333333|#333|#888888|#888|gray-[0-9]+|blue-[0-9]+|red-[0-9]+)/i);
      if (darkOrDisallowed) {
        results.push({ file: relPath, line: lineNum, type: 'Lucide Icon', content: line.trim(), issue: `Disallowed color in Icon: ${darkOrDisallowed[0]}` });
        issueCount++;
      }
    }

    // Check for explicit dark mode classes or deprecated colors in any template line
    const darkHexMatches = line.match(/(#141414|#1a1a1a|#2a2a2a|#333333|#333|#888888|#888|#000000)/ig);
    if (darkHexMatches) {
      results.push({ file: relPath, line: lineNum, type: 'Hex Color', content: line.trim(), issue: `Found legacy/dark hex: ${darkHexMatches.join(', ')}` });
      issueCount++;
    }
  });
});

console.log(`\nTotal potential issues found: ${issueCount}`);
console.log(JSON.stringify(results, null, 2));
