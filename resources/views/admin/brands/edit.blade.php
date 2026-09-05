<x-admin-layout title="Edit Brand - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Edit Brand</h1>

    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        @csrf
        @method('PUT')
        @include('admin.brands._form')
    </form>
</x-admin-layout>
