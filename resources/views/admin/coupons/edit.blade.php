<x-admin-layout title="Edit Coupon - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Edit Coupon</h1>

    @if ($errors->any())
        <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="mt-6 max-w-3xl">
        @method('PUT')
        @include('admin.coupons._form', ['submitLabel' => 'Update Coupon'])
    </form>
</x-admin-layout>
