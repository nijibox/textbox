var hljs = require('highlight.js'); // https://highlightjs.org/


var highlightSyntax = (str, lang) =>
{
    if (lang && hljs.getLanguage(lang)) {
        try {
            return '<pre class="hljs"><code>' +
                hljs.highlight(lang, str, true).value +
                '</code></pre>';
        } catch (__) {}
    }
    return '<pre class="hljs"><code>' + md.utils.escapeHtml(str) + '</code></pre>';
}

var encodeMultibytechars = (str) =>
{
    return encodeURI(str).replace(/%/g, "").toLowerCase()
}


// Actual default values
var md = require('markdown-it')({
    html: true,
    xhtmlOut: false,
    breaks: true,
    langPrefix: 'language-',
    linkify: true,
    typographer: true,
    highlight: highlightSyntax,
});
md.use(require("markdown-it-anchor")); 
md.use(require("markdown-it-table-of-contents"), {slugify: encodeMultibytechars});


window.md = md;
