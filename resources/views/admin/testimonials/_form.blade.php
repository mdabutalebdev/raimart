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
        <label class="text-sm font-medium">Name</label>
        <input type="text" name="name" value="{{ old('name', $testimonial->name ?? '') }}" required
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
    </div>

    <div>
        <label class="text-sm font-medium">Location</label>
        <input type="text" name="location" value="{{ old('location', $testimonial->location ?? '') }}"
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
    </div>

    <div>
        <label class="text-sm font-medium">Avatar</label>
        <input type="file" name="avatar" accept="image/*" class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2 text-sm">
        @if (! empty($testimonial) && $testimonial->avatar)
            <img src="{{ image_url($testimonial->avatar) }}" class="mt-2 h-12 w-12 rounded-full object-cover">
        @endif
    </div>

    <div>
        <label class="text-sm font-medium">Rating (1-5)</label>
        <input type="number" min="1" max="5" name="rating" value="{{ old('rating', $testimonial->rating ?? 5) }}" required
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
    </div>

    <div class="sm:col-span-2">
        <label class="text-sm font-medium">Review Text</label>
        <textarea name="text" rows="3" required class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">{{ old('text', $testimonial->text ?? '') }}</textarea>
    </div>

    <div>
        <label class="text-sm font-medium">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}"
            class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
    </div>

    <div class="flex items-center gap-2">
        <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $testimonial->is_active ?? true))
            class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
        <label for="is_active" class="text-sm font-medium">Active</label>
    </div>
</div>

<div class="mt-6 flex gap-3">
    <button type="submit" class="rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save</button>
    <a href="{{ route('admin.testimonials.index') }}" class="rounded-lg border border-brand-navy/15 px-6 py-2.5 text-sm font-medium hover:bg-brand-bg">Cancel</a>
</div>
