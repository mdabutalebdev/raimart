@if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="text-sm font-medium">Type</label>
        <select name="type" class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
            <option value="hero" @selected(old('type', $banner->type ?? '') === 'hero')>Hero (main slider)</option>
            <option value="promo" @selected(old('type', $banner->type ?? '') === 'promo')>Promo (side tile)</option>
        </select>
    </div>

    <div>
        <label class="text-sm font-medium">Badge Text</label>
        <input type="text" name="badge_text" value="{{ old('badge_text', $banner->badge_text ?? '') }}" placeholder="e.g. 10% OFF"
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
    </div>

    <div class="sm:col-span-2">
        <label class="text-sm font-medium">Title</label>
        <input type="text" name="title" value="{{ old('title', $banner->title ?? '') }}"
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
    </div>

    <div class="sm:col-span-2">
        <label class="text-sm font-medium">Subtitle</label>
        <textarea name="subtitle" rows="2" class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">{{ old('subtitle', $banner->subtitle ?? '') }}</textarea>
    </div>

    <div>
        <label class="text-sm font-medium">Image</label>
        <input type="file" name="image" accept="image/*" class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2 text-sm">
        <p class="mt-1 text-xs text-brand-navy/50">
            Recommended size &mdash; <strong>Hero:</strong> 1920&times;800 (2.4:1 wide) &nbsp;•&nbsp; <strong>Promo:</strong> 600&times;600 (1:1 square). Uploading these ratios keeps every banner perfectly fitted.
        </p>
        @if (! empty($banner) && $banner->image)
            <img src="{{ image_url($banner->image) }}" class="mt-2 h-16 w-28 rounded-lg object-cover">
        @endif
    </div>

    <div>
        <label class="text-sm font-medium">Button Text <span class="font-normal text-brand-navy/40">(optional)</span></label>
        <input type="text" name="button_text" value="{{ old('button_text', $banner->button_text ?? '') }}" placeholder="e.g. Shop Now"
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        <p class="mt-1 text-xs text-brand-navy/40">Leave empty to hide the button.</p>
    </div>

    <div>
        <label class="text-sm font-medium">Link URL</label>
        <input type="text" name="link" value="{{ old('link', $banner->link ?? '') }}" placeholder="#"
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
    </div>

    <div>
        <label class="text-sm font-medium">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
    </div>

    <div class="flex items-center gap-2 sm:col-span-2">
        <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))
            class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
        <label for="is_active" class="text-sm font-medium">Active</label>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save</button>
    <a href="{{ route('admin.banners.index') }}" class="rounded-lg border border-brand-navy/15 px-6 py-2.5 text-sm font-medium hover:bg-brand-bg">Cancel</a>
</div>
