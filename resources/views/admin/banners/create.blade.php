<x-admin-layout title="Add Banner - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Add Banner</h1>

    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        @csrf
        @include('admin.banners._form')
    </form>
</x-admin-layout>
