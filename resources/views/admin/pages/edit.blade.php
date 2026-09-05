<x-admin-layout title="Edit Page - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Edit Page</h1>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.pages.update', $page) }}" method="POST" class="mt-6 max-w-4xl">
        @method('PUT')
        @include('admin.pages._form', ['submitLabel' => 'Update Page'])
    </form>
</x-admin-layout>
