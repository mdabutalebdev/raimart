<x-admin-layout title="Add Testimonial - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Add Testimonial</h1>

    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-2xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        @csrf
        @include('admin.testimonials._form')
    </form>
</x-admin-layout>
