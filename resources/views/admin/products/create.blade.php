<x-admin-layout title="Add Product - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Add Product</h1>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-5xl">
        @csrf
        @include('admin.products._form')
    </form>
</x-admin-layout>
