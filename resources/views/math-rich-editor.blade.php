<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="MathRichEditor({
            state: $wire.entangle('{{ $getStatePath() }}'),
        })" class="math-rich-editor">
        <div
            class="flex flex-col border border-gray-300 rounded-lg overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-primary-500">
            <!-- Toolbar -->
            <div class="flex items-center gap-1 p-1 bg-gray-50 border-b border-gray-200">
                <button type="button" @click="editor.chain().focus().toggleBold().run()"
                    :class="editor && editor.isActive('bold') ? 'bg-gray-200' : ''"
                    class="p-1.5 rounded hover:bg-gray-200" title="Bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 12h8a4 4 0 100-8H6v8zm0 0h9a4 4 0 110 8H6v-8z" />
                    </svg>
                </button>
                <button type="button" @click="editor.chain().focus().toggleItalic().run()"
                    :class="editor && editor.isActive('italic') ? 'bg-gray-200' : ''"
                    class="p-1.5 rounded hover:bg-gray-200" title="Italic">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l-4 16M6 4l4 16" />
                    </svg>
                </button>
                <div class="w-px h-6 bg-gray-300 mx-1"></div>
                <button type="button" @click="openMathModal()"
                    class="p-1.5 rounded hover:bg-gray-200 text-primary-600 font-bold" title="Insert Math">
                    <span class="text-lg">∑</span>
                </button>
            </div>

            <!-- Editor Content -->
            <div x-ref="editor" class="p-4 min-h-[150px] bg-white tiptap"></div>
        </div>

        <!-- Math Modal -->
        <template x-teleport="body">
            <div x-show="showModal"
                class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm"
                x-cloak @keydown.escape.window="closeModal()">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-200"
                    @click.away="closeModal()">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Insert Equation</h3>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">LaTeX Input</label>
                            <textarea x-ref="mathInput" x-model="mathInput" @input="renderPreview()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 font-mono text-sm"
                                rows="3" placeholder="e.g. x^2 + y^2 = r^2"></textarea>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" x-model="isDisplayMode" @change="renderPreview()" id="display-mode"
                                class="rounded text-primary-600 focus:ring-primary-500">
                            <label for="display-mode" class="text-sm text-gray-700">Display Mode (Centered)</label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preview</label>
                            <div x-ref="preview" class="math-preview"></div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                        <button @click="closeModal()" type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            Cancel
                        </button>
                        <button @click="insertMath()" type="button"
                            class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors shadow-sm">
                            Insert
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-dynamic-component>