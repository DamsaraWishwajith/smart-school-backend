const fs = require('fs');
const html = fs.readFileSync('rendered_admin.html', 'utf8');

// Find all elements with class modal or containing modal in id
const matches = html.match(/id="[^"]*modal[^"]*"/gi);
console.log('All modal IDs:', matches);
