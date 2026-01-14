import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import { MathNode } from './math-node-extension'
import katex from 'katex'

window.MathRichEditor = (config) => {
    return {
        state: config.state,
        editor: null,
        showModal: false,
        mathInput: '',
        isDisplayMode: false,
        editingPos: null,

        init() {
            this.editor = new Editor({
                element: this.$refs.editor,
                extensions: [
                    StarterKit,
                    MathNode,
                ],
                content: this.state,
                onUpdate: ({ editor }) => {
                    this.state = editor.getJSON()
                },
            })

            // Listen for edit-math-node events (from clicking existing nodes)
            window.addEventListener('edit-math-node', (e) => {
                if (this.$el.contains(e.target) || true) { // simple check for demo
                    this.openMathModal(e.detail.latex, e.detail.displayMode, e.detail.pos)
                }
            })

            // Listen for open-math-modal events (from keyboard shortcut)
            window.addEventListener('open-math-modal', (e) => {
                this.openMathModal(e.detail.latex, e.detail.displayMode)
            })

            this.$watch('state', (value) => {
                if (JSON.stringify(value) !== JSON.stringify(this.editor.getJSON())) {
                    this.editor.commands.setContent(value, false)
                }
            })
        },

        openMathModal(latex = '', displayMode = false, pos = null) {
            this.mathInput = latex
            this.isDisplayMode = displayMode
            this.editingPos = pos
            this.showModal = true

            this.$nextTick(() => {
                this.$refs.mathInput.focus()
                this.renderPreview()
            })
        },

        renderPreview() {
            try {
                katex.render(this.mathInput || '?', this.$refs.preview, {
                    throwOnError: false,
                    displayMode: this.isDisplayMode,
                })
            } catch (e) {
                this.$refs.preview.textContent = this.mathInput
            }
        },

        insertMath() {
            if (this.editingPos !== null) {
                this.editor.commands.insertContentAt(this.editingPos, {
                    type: 'mathNode',
                    attrs: {
                        latex: this.mathInput,
                        displayMode: this.isDisplayMode,
                    },
                })
            } else {
                this.editor.chain().focus().insertContent({
                    type: 'mathNode',
                    attrs: {
                        latex: this.mathInput,
                        displayMode: this.isDisplayMode,
                    },
                }).run()
            }
            this.closeModal()
        },

        closeModal() {
            this.showModal = false
            this.mathInput = ''
            this.editingPos = null
            this.editor.commands.focus()
        }
    }
}
