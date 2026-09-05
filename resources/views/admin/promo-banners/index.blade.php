<x-admin-layout title="Promo Banner - Raimart Admin">
    <div x-data="{ deleteModalOpen: false, deleteUrl: '' }">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-serif text-2xl font-bold">Promo Banner</h1>
                <p class="mt-1 text-sm text-gray-500">Full-width banner shown on the home page after the New Arrivals section.</p>
            </div>
            <a href="{{ route('admin.promo-banners.create') }}" class="rounded-lg bg-brand-orange px-4 py-2 text-sm font-semibold text-white hover:bg-brand-navy">
                <i class="fa-solid fa-plus"></i> Add Banner
            </a>
        </div>

        @if (session('status'))
            <div class="mt-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mt-6 space-y-4">
            @forelse ($banners as $banner)
                <div class="flex flex-col gap-4 rounded-xl bg-white p-4 shadow-sm ring-1 ring-brand-navy/5 sm:flex-row sm:items-center">
                    <div class="w-full overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-200 sm:w-64">
                        <img src="{{ image_url($banner->image) }}" class="h-24 w-full object-cover" alt="Promo Banner">
                    </div>
                    <div class="flex-1">
                        @if($banner->is_active)
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/10">Inactive</span>
                        @endif
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="fa-solid fa-link text-xs"></i>
                            {{ $banner->link ?: 'No link' }}
                        </p>
                        <p class="text-xs text-gray-400">Sort order: {{ $banner->sort_order }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.promo-banners.edit', $banner) }}" class="inline-flex items-center gap-1 rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <i class="fa-solid fa-pen-to-square text-xs"></i> Edit
                        </a>
                        <button type="button" @click="deleteUrl = '{{ route('admin.promo-banners.destroy', $banner) }}'; deleteModalOpen = true" class="inline-flex items-center gap-1 rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-red-600 ring-1 ring-inset ring-gray-300 hover:bg-red-50">
                            <i class="fa-solid fa-trash-can text-xs"></i> Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="rounded-xl bg-white px-6 py-12 text-center text-gray-500 shadow-sm ring-1 ring-brand-navy/5">
                    <i class="fa-solid fa-image text-4xl text-gray-300 mb-3"></i>
                    <p class="text-base font-medium text-gray-900">No promo banner yet</p>
                    <p class="text-sm">Add a banner to show it on the home page after New Arrivals.</p>
                </div>
            @endforelse
        </div>

        <!-- Delete Modal -->
        <div x-cloak x-show="deleteModalOpen" class="relative z-[100]" role="dialog" aria-modal="true" style="display: none;">
            <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <div x-show="deleteModalOpen" x-transition @click.away="deleteModalOpen = false" class="relative w-full max-w-sm transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl border border-gray-100">
                        <div class="px-4 pb-4 pt-5 sm:p-6 flex flex-col items-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-red-50 mb-4">
                                <i class="fa-solid fa-trash-can text-xl text-red-500"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Delete Banner</h3>
                            <p class="mt-2 text-sm text-gray-500">Are you sure? This action cannot be undone.</p>
                        </div>
                        <div class="bg-gray-50/50 px-6 py-4 flex items-center justify-center gap-3">
                            <button type="button" @click="deleteModalOpen = false" class="w-full rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancel</button>
                            <form method="POST" :action="deleteUrl" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded-xl bg-red-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-600">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
