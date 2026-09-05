<x-admin-layout title="Edit Testimonial - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Edit Testimonial</h1>

    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        @csrf
        @method('PUT')
        @include('admin.testimonials._form')
    </form>
</x-admin-layout>
