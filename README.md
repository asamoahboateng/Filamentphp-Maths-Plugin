# MathRichEditor for Filament v4

A powerful math editor plugin for Laravel Filament v4 that seamlessly integrates Tiptap with KaTeX for beautiful mathematical typesetting.

![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Filament](https://img.shields.io/badge/Filament-v4-orange.svg)

## ✨ Features

- **🎯 Tiptap-Native**: Built on the same editor foundation as Filament's RichEditor
- **📐 KaTeX Integration**: Fast, beautiful mathematical rendering
- **⌨️ Keyboard Shortcuts**: `Cmd/Ctrl + Shift + M` to insert math
- **🔄 Input Rules**: Type `$x^2$` followed by space to auto-convert to math nodes
- **👁️ Live Preview**: Real-time LaTeX rendering in the insertion modal
- **📦 Offline Support**: All assets bundled locally—no CDN required
- **💾 JSON Storage**: Preserves math nodes as structured data
- **✏️ Inline Editing**: Click any math node to edit it

## 📦 Installation

### 1. Install via Composer

```bash
composer require kwame-boateng/filament-math-rich-editor
```

### 2. Install NPM Dependencies & Build Assets

Navigate to the plugin directory and build the frontend assets:

```bash
cd vendor/kwame-boateng/filament-math-rich-editor
npm install
npm run build
```

### 3. Publish Assets (Optional)

If you want to customize the KaTeX configuration:

```bash
php artisan vendor:publish --tag="math-rich-editor-config"
```

### 4. Clear Filament Cache

```bash
php artisan filament:cache-components
```

## 🚀 Usage

### In a Filament Resource

```php
use KwameBoateng\MathRichEditor\MathRichEditor;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            MathRichEditor::make('content')
                ->label('Mathematical Content')
                ->placeholder('Start typing or press Cmd+Shift+M to insert math...')
                ->required(),
        ]);
}
```

### Quick Tips

- **Insert Math**: Click the `∑` button in the toolbar or press `Cmd/Ctrl + Shift + M`
- **Auto-Convert**: Type `$E = mc^2$` and press space to auto-create a math node
- **Display Mode**: Check "Display Mode" in the modal for centered, larger equations
- **Edit Existing**: Click any rendered math equation to edit it

## 🎨 Frontend Rendering

The editor saves data as JSON. To render the math on your public-facing pages, you'll need to:

### 1. Install KaTeX in Your Main App

```bash
npm install katex
```

### 2. Include KaTeX CSS

In your main layout file:

```html
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
```

Or if bundling locally:

```javascript
// In your app.js
import 'katex/dist/katex.min.css';
```

### 3. Render the Content

Create a Blade component to render the JSON content:

```blade
{{-- resources/views/components/math-content.blade.php --}}
@props(['content'])

<div class="prose max-w-none" x-data="mathContentRenderer(@js($content))">
    <div x-html="renderedContent"></div>
</div>

@push('scripts')
<script type="module">
import katex from 'katex';

window.mathContentRenderer = (content) => {
    return {
        renderedContent: '',
        init() {
            this.renderContent(content);
        },
        renderContent(json) {
            // Convert Tiptap JSON to HTML with KaTeX rendering
            const renderNode = (node) => {
                if (node.type === 'mathNode') {
                    const span = document.createElement('span');
                    katex.render(node.attrs.latex, span, {
                        throwOnError: false,
                        displayMode: node.attrs.displayMode
                    });
                    return span.outerHTML;
                }
                
                if (node.type === 'text') {
                    return node.text;
                }
                
                let html = '';
                if (node.content) {
                    html = node.content.map(renderNode).join('');
                }
                
                const tag = node.type === 'paragraph' ? 'p' : 'div';
                return `<${tag}>${html}</${tag}>`;
            };
            
            this.renderedContent = json.content?.map(renderNode).join('') || '';
        }
    };
};
</script>
@endpush
```

Usage in your views:

```blade
<x-math-content :content="$post->content" />
```

## 📸 Screenshots

### Editor View
<!-- Add screenshot of the editor with toolbar -->
![Editor View](docs/screenshots/editor-view.png)

### Math Modal
<!-- Add screenshot of the math insertion modal -->
![Math Modal](docs/screenshots/math-modal.png)

## ⚙️ Configuration

After publishing the config file, you can customize KaTeX settings:

```php
// config/math-rich-editor.php

return [
    'katex' => [
        'throwOnError' => false,
        'errorColor' => '#cc0000',
        'macros' => [
            "\\RR": "\\mathbb{R}",
        ],
    ],
];
```

## 🧪 Examples

### Inline Math
Type: `The equation $x^2 + y^2 = r^2$ represents a circle.`

### Display Math
Type: `$$\int_0^\infty e^{-x^2} dx = \frac{\sqrt{\pi}}{2}$$`

### Complex Equations
```latex
$$
\frac{\partial^2 u}{\partial t^2} = c^2 \nabla^2 u
$$
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## 🙏 Credits

- Built with [Filament](https://filamentphp.com)
- Powered by [Tiptap](https://tiptap.dev)
- Math rendering by [KaTeX](https://katex.org)

## 💬 Support

If you encounter any issues or have questions, please [open an issue](https://github.com/kwame-boateng/filament-math-rich-editor/issues).
