var hljs = require('highlight.js'); // https://highlightjs.org/


// Actual default values
var md = require('markdown-it')({
    html: true,
    xhtmlOut: false,
    breaks: true,
    langPrefix: 'language-',
    linkify: true,
    typographer: true,
    highlight: function (str, lang) {
        if (lang && hljs.getLanguage(lang)) {
            try {
                return '<pre class="hljs"><code>' +
                    hljs.highlight(lang, str, true).value +
                    '</code></pre>';
            } catch (__) {}
        }
        return '<pre class="hljs"><code>' + md.utils.escapeHtml(str) + '</code></pre>';
    }
});
md.use(require("markdown-it-anchor")); 
md.use(require("markdown-it-table-of-contents"));


window.md = md;
