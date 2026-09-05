<x-admin-layout title="Categories - Raimart Admin">
    <div x-data="{ 
            deleteModalOpen: false, 
            deleteFormAction: '', 
            categoryName: '',
            openDeleteModal(action, name) {
                this.deleteFormAction = action;
                this.categoryName = name;
                this.deleteModalOpen = true;
            }
        }" 
        @keydown.escape.window="deleteModalOpen = false"
    >
        <div class="flex items-center justify-between">
            <h1 class="font-serif text-2xl font-bold">Categories</h1>
            <a href="{{ route('admin.categories.create') }}" class="rounded-lg bg-brand-orange px-4 py-2 text-sm font-semibold text-white hover:bg-brand-navy">
                <i class="fa-solid fa-plus"></i> Add Category
            </a>
        </div>

        <div class="mt-6 space-y-4">
            @foreach ($categories as $category)
                <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-brand-navy/5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ image_url($category->image) }}" class="h-12 w-12 rounded-lg object-cover" alt="{{ $category->name }}">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-lg text-brand-navy">{{ $category->name }}</p>
                                    @if($category->show_on_home)
                                        <span class="rounded-full bg-blue-50 text-blue-600 px-2.5 py-0.5 text-xs font-semibold border border-blue-100 flex items-center gap-1">
                                            <i class="fa-solid fa-house w-3"></i> Home
                                        </span>
                                    @endif
                                    @if(!$category->is_active)
                                        <span class="rounded-full bg-red-50 text-red-600 px-2.5 py-0.5 text-xs font-semibold border border-red-100">Hidden</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-list-tree mr-1"></i> {{ $category->children->count() }} subcategories</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-brand-orange hover:underline">Edit</a>
                            <button type="button" @click="openDeleteModal('{{ route('admin.categories.destroy', $category) }}', '{{ addslashes($category->name) }}')" class="text-red-500 hover:underline">Delete</button>
                        </div>
                    </div>

                    @if ($category->children->count())
                        <div class="mt-4 flex flex-wrap gap-2 border-t border-brand-navy/5 pt-4">
                            @foreach ($category->children as $child)
                                <span class="flex items-center gap-2 rounded-full bg-brand-bg px-3 py-1 text-xs">
                                    {{ $child->name }}
                                    <div class="flex items-center gap-1 border-l border-brand-navy/10 pl-2 ml-1">
                                        <a href="{{ route('admin.categories.edit', $child) }}" class="text-brand-navy/40 hover:text-brand-orange" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                        <button type="button" @click="openDeleteModal('{{ route('admin.categories.destroy', $child) }}', '{{ addslashes($child->name) }}')" class="text-brand-navy/40 hover:text-red-500" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                    </div>
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Alpine Delete Confirmation Modal -->
        <!-- Modal Backdrop -->
        <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 z-50 bg-brand-navy/60 backdrop-blur-sm"></div>

        <!-- Modal Content -->
        <div x-show="deleteModalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
             
            <div @click.away="deleteModalOpen = false" class="relative w-full max-w-sm overflow-hidden rounded-2xl bg-white text-left shadow-2xl ring-1 ring-black/5 sm:my-8">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fa-solid fa-triangle-exclamation text-lg text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">Delete Category</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to delete <span class="font-bold text-gray-900" x-text="categoryName"></span>? All subcategories will also be removed. This action cannot be undone.</p>
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
