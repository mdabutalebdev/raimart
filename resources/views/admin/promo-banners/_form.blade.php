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
    <div class="sm:col-span-2">
        <label class="text-sm font-medium">Banner Image <span class="text-red-500">*</span></label>
        <input type="file" name="image" accept="image/*" class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2 text-sm">
        <p class="mt-1 text-xs text-brand-navy/50">
            Recommended size &mdash; wide banner, around <strong>1720&times;450</strong> (about 3.8:1). Uploading this ratio keeps the banner perfectly fitted.
        </p>
        @if (! empty($banner) && $banner->image)
            <img src="{{ image_url($banner->image) }}" class="mt-3 w-full max-w-md rounded-lg object-cover ring-1 ring-brand-navy/10">
        @endif
    </div>

    <div class="sm:col-span-2">
        <label class="text-sm font-medium">Link URL <span class="font-normal text-brand-navy/40">(optional)</span></label>
        <input type="text" name="link" value="{{ old('link', $banner->link ?? '') }}" placeholder="e.g. /shop or https://..."
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        <p class="mt-1 text-xs text-brand-navy/40">Where the banner links to when clicked. Leave empty for no link.</p>
    </div>

    <div>
        <label class="text-sm font-medium">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
        <p class="mt-1 text-xs text-brand-navy/40">Lower shows first (when multiple banners are active they slide).</p>
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))
            class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
        <label for="is_active" class="text-sm font-medium">Active</label>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save</button>
    <a href="{{ route('admin.promo-banners.index') }}" class="rounded-lg border border-brand-navy/15 px-6 py-2.5 text-sm font-medium hover:bg-brand-bg">Cancel</a>
</div>
