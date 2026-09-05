<x-admin-layout title="Social Links - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Social Links</h1>
    <p class="mt-1 text-sm text-brand-navy/60">Tick a platform and give it a URL — only ticked ones show in the website footer.</p>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.social-links.update') }}" method="POST" class="mt-6 max-w-4xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        @csrf
        @method('PUT')

        <div class="space-y-3">
            @foreach (\App\Models\SocialLink::PLATFORMS as $key => $meta)
                @php $link = $links[$key] ?? null; @endphp
                <div class="flex flex-col gap-3 rounded-lg border border-brand-navy/10 p-3 sm:flex-row sm:items-center">
                    <label class="flex w-full shrink-0 cursor-pointer items-center gap-3 sm:w-56">
                        <input type="hidden" name="links[{{ $key }}][is_active]" value="0">
                        <input type="checkbox" name="links[{{ $key }}][is_active]" value="1"
                            @checked(old("links.$key.is_active", $link?->is_active))
                            class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-navy/5 text-brand-orange">
                            <i class="fa-brands fa-{{ $meta['icon'] }}"></i>
                        </span>
                        <span class="text-sm font-medium">{{ $meta['label'] }}</span>
                    </label>

                    <input type="text" name="links[{{ $key }}][url]" value="{{ old("links.$key.url", $link?->url) }}"
                        placeholder="https://… ({{ $meta['label'] }} link)"
                        class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>
            @endforeach
        </div>

        <button type="submit" class="mt-6 rounded-lg bg-brand-orange px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Save Social Links</button>
    </form>
</x-admin-layout>
