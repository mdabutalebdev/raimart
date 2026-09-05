@csrf

<div class="space-y-5 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Page Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $page->title) }}" required
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">URL Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="auto-generated from the title"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            <p class="mt-1 text-xs text-gray-500">The page will live at <code>/page/{slug}</code>.</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Meta Description (SEO)</label>
        <input type="text" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}"
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
    </div>

    <div wire:ignore>
        <label class="mb-1 block text-sm font-medium text-gray-700">Page Content</label>
        {{-- Quill writes its HTML into this hidden input on submit --}}
        <input type="hidden" name="content" id="content-input" value="{{ old('content', $page->content) }}">
        <div id="quill-editor" class="rounded-b-md border-gray-300 bg-white" style="min-height: 320px;">
            {!! old('content', $page->content) !!}
        </div>
    </div>

    <div class="grid gap-5 sm:grid-cols-3">
        <label class="flex cursor-pointer items-center gap-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $page->is_active))
                class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
            <span class="text-sm font-medium">Active (published)</span>
        </label>

        <label class="flex cursor-pointer items-center gap-3">
            <input type="hidden" name="show_in_footer" value="0">
            <input type="checkbox" name="show_in_footer" value="1" @checked(old('show_in_footer', $page->show_in_footer))
                class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
            <span class="text-sm font-medium">Show in footer</span>
        </label>

        <div>
            <label class="block text-sm font-medium text-gray-700">Sort Order</label>
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $page->sort_order ?? 0) }}"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        </div>
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit"
            @click="if (window.quill) { document.getElementById('content-input').value = quill.root.innerHTML; }"
            class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">
            {{ $submitLabel ?? 'Save Page' }}
        </button>
        <a href="{{ route('admin.pages.index') }}" class="text-sm font-medium text-brand-navy/60 hover:text-brand-navy">Cancel</a>
    </div>
</div>

<script>
    // Rich text editor for the page body. Mirrors the product form's Quill setup.
    var quill;
    document.addEventListener('livewire:navigated', function () {
        if (typeof Quill !== 'undefined' && document.getElementById('quill-editor')) {
            quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Write your page content here…',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, 4, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        [{ align: [] }],
                        [{ color: [] }, { background: [] }],
                        ['blockquote', 'link', 'image'],
                        ['clean'],
                    ],
                },
            });

            // Safety net: also sync on form submit (covers Enter-key submits).
            quill.root.closest('form')?.addEventListener('submit', function () {
                document.getElementById('content-input').value = quill.root.innerHTML;
            });
        }
    });
</script>
