/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './src/**/*.php',
    ],
    safelist: [
        // KaTeX classes
        { pattern: /^katex/ },
        'math-display',
        'math-node',
        'math-preview',
        // Preserve common KaTeX elements
        'mord',
        'mbin',
        'mrel',
        'mop',
        'mopen',
        'mclose',
        'mpunct',
        'minner',
        'mspace',
        'base',
        'strut',
        'frac-line',
        'vlist-t',
        'vlist-r',
        'vlist',
        'pstrut',
        'sizing',
        'reset-size',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
    important: '.math-rich-editor',
}
