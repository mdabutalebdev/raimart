<x-admin-layout title="Add Coupon - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Add Coupon</h1>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.coupons.store') }}" method="POST" class="mt-6 max-w-3xl">
        @include('admin.coupons._form', ['submitLabel' => 'Create Coupon'])
    </form>
</x-admin-layout>
