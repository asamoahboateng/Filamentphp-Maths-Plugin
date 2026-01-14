import { Node, mergeAttributes } from '@tiptap/core'
import { InputRule } from '@tiptap/core'
import katex from 'katex'

export const MathNode = Node.create({
    name: 'mathNode',
    group: 'inline',
    inline: true,
    selectable: true,
    draggable: true,
    atom: true,

    addAttributes() {
        return {
            latex: {
                default: '',
            },
            displayMode: {
                default: false,
            },
        }
    },

    parseHTML() {
        return [
            {
                tag: 'span[data-latex]',
                getAttrs: (element) => ({
                    latex: element.getAttribute('data-latex'),
                    displayMode: element.getAttribute('data-display-mode') === 'true',
                }),
            },
        ]
    },

    renderHTML({ HTMLAttributes }) {
        return ['span', mergeAttributes(HTMLAttributes, { class: 'math-node' }), 0]
    },

    addKeyboardShortcuts() {
        return {
            'Mod-Shift-m': () => {
                // Trigger the math modal
                const event = new CustomEvent('open-math-modal', {
                    detail: { latex: '', displayMode: false }
                });
                window.dispatchEvent(event);
                return true;
            },
        }
    },

    addInputRules() {
        return [
            // Inline math: $...$
            new InputRule({
                find: /\$([^$]+)\$\s$/,
                handler: ({ state, range, match }) => {
                    const { tr } = state;
                    const latex = match[1];

                    tr.replaceWith(
                        range.from,
                        range.to,
                        this.type.create({
                            latex,
                            displayMode: false,
                        })
                    );
                },
            }),
            // Display math: $$...$$
            new InputRule({
                find: /\$\$([^$]+)\$\$\s$/,
                handler: ({ state, range, match }) => {
                    const { tr } = state;
                    const latex = match[1];

                    tr.replaceWith(
                        range.from,
                        range.to,
                        this.type.create({
                            latex,
                            displayMode: true,
                        })
                    );
                },
            }),
        ]
    },

    addNodeView() {
        return ({ node, HTMLAttributes, getPos, editor }) => {
            const dom = document.createElement('span')
            dom.classList.add('math-node')
            dom.setAttribute('data-latex', node.attrs.latex)
            dom.setAttribute('data-display-mode', node.attrs.displayMode)

            const render = () => {
                try {
                    katex.render(node.attrs.latex || '?', dom, {
                        throwOnError: false,
                        displayMode: node.attrs.displayMode,
                    })
                } catch (e) {
                    dom.textContent = node.attrs.latex
                }
            }

            render()

            dom.addEventListener('click', () => {
                if (editor.isEditable) {
                    // Signal to Alpine handler that we want to edit this node
                    const event = new CustomEvent('edit-math-node', {
                        detail: {
                            pos: getPos(),
                            latex: node.attrs.latex,
                            displayMode: node.attrs.displayMode
                        }
                    });
                    window.dispatchEvent(event);
                }
            })

            return {
                dom,
                update: (updatedNode) => {
                    if (updatedNode.type !== node.type) return false
                    if (updatedNode.attrs.latex !== node.attrs.latex || updatedNode.attrs.displayMode !== node.attrs.displayMode) {
                        node.attrs = updatedNode.attrs
                        render()
                    }
                    return true
                },
            }
        }
    },
})
