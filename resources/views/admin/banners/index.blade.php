<x-admin-layout title="Banners - Raimart Admin">
    <div x-data="{ deleteModalOpen: false, deleteUrl: '' }">
        <div class="flex items-center justify-between">
            <h1 class="font-serif text-2xl font-bold">Banners</h1>
            <a href="{{ route('admin.banners.create') }}" class="rounded-lg bg-brand-orange px-4 py-2 text-sm font-semibold text-white hover:bg-brand-navy">
                <i class="fa-solid fa-plus"></i> Add Banner
            </a>
        </div>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-brand-navy/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50 text-gray-500">
                        <th class="px-6 py-4 font-medium uppercase tracking-wider">Banner</th>
                        <th class="px-6 py-4 font-medium uppercase tracking-wider">Title</th>
                        <th class="px-6 py-4 font-medium uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 font-medium uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 font-medium uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($banners as $banner)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="h-16 w-32 overflow-hidden rounded-lg bg-gray-100 ring-1 ring-gray-200">
                                    <img src="{{ image_url($banner->image) }}" class="h-full w-full object-cover" alt="Banner Image">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-gray-900">{{ $banner->title ?: '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold uppercase text-blue-700 ring-1 ring-inset ring-blue-700/10">{{ $banner->type }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($banner->is_active)
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Active</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/10">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="inline-flex items-center gap-1 rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i> Edit
                                    </a>
                                    <button type="button" @click="deleteUrl = '{{ route('admin.banners.destroy', $banner) }}'; deleteModalOpen = true" class="inline-flex items-center gap-1 rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-red-600 ring-1 ring-inset ring-gray-300 hover:bg-red-50">
                                        <i class="fa-solid fa-trash-can text-xs"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-image text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-base font-medium text-gray-900">No banners found</p>
                                    <p class="text-sm">Get started by creating a new banner.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
        <!-- Delete Modal -->
        <div x-cloak x-show="deleteModalOpen" class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div x-show="deleteModalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 backdrop-blur-none" 
                 x-transition:enter-end="opacity-100 backdrop-blur-sm" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 backdrop-blur-sm" 
                 x-transition:leave-end="opacity-0 backdrop-blur-none" 
                 class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-all"></div>
    
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                    <div x-show="deleteModalOpen" 
                         x-transition:enter="ease-out duration-300" 
                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" 
                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         @click.away="deleteModalOpen = false" 
                         class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-100">
                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4 flex flex-col items-center">
                            <div class="mx-auto flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full bg-red-50 mb-4">
                                <i class="fa-solid fa-trash-can text-xl text-red-500"></i>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Delete Banner</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Are you sure? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50/50 px-6 py-4 flex items-center justify-center gap-3">
                            <button type="button" @click="deleteModalOpen = false" class="w-full rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Cancel</button>
                            <form method="POST" :action="deleteUrl" class="w-full m-0 p-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded-xl bg-red-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-600 transition-colors">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
