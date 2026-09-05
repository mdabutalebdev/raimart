<x-admin-layout title="Edit Product - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Edit Product</h1>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-5xl">
        @csrf
        @method('PUT')
        @include('admin.products._form')
    </form>
</x-admin-layout>
