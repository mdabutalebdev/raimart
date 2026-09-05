<x-layout :title="$page->title.' - Raimart'">
    <section class="gb-container py-8 md:py-11">
        <nav class="text-sm text-brand-navy/50">
            <a href="{{ route('home') }}" wire:navigate class="hover:text-brand-orange">Home</a>
            <span class="mx-1">/</span>
            <span class="text-brand-navy">{{ $page->title }}</span>
        </nav>

        <h1 class="mt-4 text-xl sm:text-3xl font-bold text-gray-800 mb-2 sm:mb-4">{{ $page->title }}</h1>

        <div class="prose-page mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5 sm:p-8">
            {!! $page->content !!}
        </div>
    </section>
</x-layout>
