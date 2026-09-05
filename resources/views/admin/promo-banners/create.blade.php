<x-admin-layout title="Add Promo Banner - Raimart Admin">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.promo-banners.index') }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-900 transition-colors">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <h1 class="font-serif text-2xl font-bold">Add Promo Banner</h1>
    </div>

    <form action="{{ route('admin.promo-banners.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        @csrf
        @include('admin.promo-banners._form')
    </form>
</x-admin-layout>
