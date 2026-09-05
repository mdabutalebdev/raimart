<x-admin-layout title="Brands - Raimart Admin">
    <div x-data="{
            deleteModalOpen: false,
            deleteFormAction: '',
            brandName: '',
            openDeleteModal(action, name) {
                this.deleteFormAction = action;
                this.brandName = name;
                this.deleteModalOpen = true;
            }
        }"
        @keydown.escape.window="deleteModalOpen = false"
    >
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-serif text-2xl font-bold">Brands</h1>
                <p class="mt-1 text-sm text-gray-500">Manage the brands shown on the home page and used in shop filters.</p>
            </div>
            <a href="{{ route('admin.brands.create') }}" class="rounded-lg bg-brand-orange px-4 py-2 text-sm font-semibold text-white hover:bg-brand-navy">
                <i class="fa-solid fa-plus"></i> Add Brand
            </a>
        </div>

        @if (! $brands->count())
            <div class="mt-8 rounded-xl border border-dashed border-gray-200 bg-white py-16 text-center">
                <i class="fa-solid fa-trademark text-4xl text-gray-200"></i>
                <p class="mt-4 text-gray-500">No brands yet. Add your first brand to show it on the home page.</p>
            </div>
        @else
            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($brands as $brand)
                    <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-brand-navy/5">
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-24 shrink-0 items-center justify-center rounded-lg border border-gray-100 bg-brand-bg p-2">
                                <img src="{{ image_url($brand->logo, urlencode($brand->name)) }}" alt="{{ $brand->name }}" class="max-h-full max-w-full object-contain">
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="truncate text-lg font-medium text-brand-navy">{{ $brand->name }}</p>
                                    @if($brand->show_on_home)
                                        <span class="rounded-full bg-blue-50 text-blue-600 px-2 py-0.5 text-[11px] font-semibold border border-blue-100 flex items-center gap-1">
                                            <i class="fa-solid fa-house w-3"></i> Home
                                        </span>
                                    @endif
                                    @if(!$brand->is_active)
                                        <span class="rounded-full bg-red-50 text-red-600 px-2 py-0.5 text-[11px] font-semibold border border-red-100">Hidden</span>
                                    @endif
                                </div>
                                <p class="mt-1 text-xs text-gray-500"><i class="fa-solid fa-box mr-1"></i> {{ $brand->products_count }} {{ Str::plural('product', $brand->products_count) }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-3 border-t border-brand-navy/5 pt-3 text-sm">
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="font-medium text-brand-orange hover:underline">Edit</a>
                            <button type="button" @click="openDeleteModal('{{ route('admin.brands.destroy', $brand) }}', '{{ addslashes($brand->name) }}')" class="font-medium text-red-500 hover:underline">Delete</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-brand-navy/60 backdrop-blur-sm" style="display: none;"></div>

        <div x-show="deleteModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0" style="display: none;">

            <div @click.away="deleteModalOpen = false" class="relative w-full max-w-sm overflow-hidden rounded-2xl bg-white text-left shadow-2xl ring-1 ring-black/5 sm:my-8">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-lg text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Delete Brand</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete <span class="font-bold text-gray-900" x-text="brandName"></span>? Products keep existing but lose this brand. This action cannot be undone.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form :action="deleteFormAction" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">Delete</button>
                    </form>
                    <button @click="deleteModalOpen = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
