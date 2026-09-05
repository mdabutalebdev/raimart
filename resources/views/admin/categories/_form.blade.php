@if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div x-data="{ type: '{{ old('parent_id', $category->parent_id ?? '') ? 'sub' : 'main' }}' }" class="space-y-8">
    <!-- Category Type Selection (Only on create, on edit we can disable it or just let it be) -->
    <div class="flex items-center gap-6 p-5 bg-gray-50 border border-gray-100 rounded-xl">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" x-model="type" value="main" class="w-5 h-5 text-brand-orange border-gray-300 focus:ring-brand-orange">
            <span class="font-medium text-brand-navy">Main Category</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" x-model="type" value="sub" class="w-5 h-5 text-brand-orange border-gray-300 focus:ring-brand-orange">
            <span class="font-medium text-brand-navy">Sub Category</span>
        </label>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <!-- Parent Category (Visible only if type is 'sub') -->
        <div x-show="type === 'sub'" x-transition class="sm:col-span-2 p-5 bg-blue-50 border border-blue-100 rounded-xl">
            <label class="text-sm font-semibold text-brand-navy">Select Main Category <span class="text-red-500">*</span></label>
            <select name="parent_id" x-bind:required="type === 'sub'" class="mt-2 w-full rounded-lg border border-brand-navy/15 px-4 py-3 text-sm focus:border-brand-orange focus:ring-brand-orange">
                <option value="">— Select Main Category —</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id ?? '') == $parent->id)>{{ $parent->name }}</option>
                @endforeach
            </select>
            <p class="text-xs text-blue-600 mt-2"><i class="fa-solid fa-circle-info mr-1"></i> This subcategory will be placed under the selected main category.</p>
        </div>

        <!-- Name -->
        <div class="sm:col-span-2">
            <label class="text-sm font-semibold text-brand-navy">
                <span x-text="type === 'sub' ? 'Sub Category Name' : 'Category Name'">Category Name</span> 
                <span class="text-red-500">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required
                placeholder="e.g. Electronics, Men's Fashion, Laptops"
                class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-3 text-sm focus:border-brand-orange focus:ring-brand-orange">
        </div>

        <!-- Image Upload with Preview -->
        <div class="sm:col-span-2" x-data="imageUploader()">
            <label class="text-sm font-semibold text-brand-navy">Category Image</label>
            
            <div class="mt-2 flex justify-center rounded-xl border border-dashed border-gray-300 px-6 py-8"
                 :class="{ 'bg-brand-orange/5 border-brand-orange': isDragging }"
                 @dragover.prevent="isDragging = true"
                 @dragleave.prevent="isDragging = false"
                 @drop.prevent="handleDrop($event)">
                 
                <!-- The file input must be outside x-if templates so it isn't removed from DOM! -->
                <input id="image-upload" name="image" type="file" class="sr-only" accept="image/*" @change="handleFileSelect">

                <div class="text-center w-full">
                    <template x-if="imageUrl">
                        <div class="relative inline-block">
                            <img :src="imageUrl" class="mx-auto h-32 w-32 rounded-lg object-cover shadow-sm">
                            <button type="button" @click="removeImage" class="absolute -right-2 -top-2 rounded-full bg-red-500 p-1 text-white hover:bg-red-600 shadow">
                                <i class="fa-solid fa-xmark w-4 h-4 flex items-center justify-center"></i>
                            </button>
                        </div>
                    </template>
                    
                    <template x-if="!imageUrl">
                        <div>
                            @if(!empty($category) && $category->image)
                                <div class="relative inline-block mb-4">
                                    <img src="{{ image_url($category->image) }}" class="mx-auto h-32 w-32 rounded-lg object-cover shadow-sm">
                                </div>
                            @else
                                <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-300 mb-3"></i>
                            @endif
                            <div class="mt-2 flex text-sm leading-6 text-gray-600 justify-center">
                                <label for="image-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-brand-orange focus-within:outline-none hover:text-brand-navy">
                                    <span>Upload a file</span>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs leading-5 text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="sm:col-span-2 grid gap-6 sm:grid-cols-2 pt-4 border-t border-gray-100">
            <!-- Sort Order -->
            <div>
                <label class="text-sm font-semibold text-brand-navy">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}"
                    class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-3 text-sm focus:border-brand-orange focus:ring-brand-orange">
            </div>

            <!-- Toggles -->
            <div class="flex flex-col gap-4 justify-center">
                <!-- Active Toggle -->
                <label class="flex items-center gap-3 cursor-pointer w-fit">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true)) class="sr-only peer">
                        <div class="w-11 h-6 rounded-full bg-gray-200 peer-checked:bg-green-500 transition-colors"></div>
                        <div class="absolute left-[2px] top-[2px] h-5 w-5 rounded-full bg-white border border-gray-300 transition-transform peer-checked:translate-x-full peer-checked:border-white"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Active</span>
                </label>

                <!-- Show on Home Page Toggle -->
                <label x-show="type === 'main'" class="flex items-center gap-3 cursor-pointer w-fit">
                    <div class="relative flex items-center">
                        <input type="checkbox" name="show_on_home" value="1" @checked(old('show_on_home', $category->show_on_home ?? false)) class="sr-only peer">
                        <div class="w-11 h-6 rounded-full bg-gray-200 peer-checked:bg-brand-orange transition-colors"></div>
                        <div class="absolute left-[2px] top-[2px] h-5 w-5 rounded-full bg-white border border-gray-300 transition-transform peer-checked:translate-x-full peer-checked:border-white"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Show in "Browse Category" (Home Page)</span>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 flex gap-3 pt-6 border-t border-gray-100">
    <button type="submit" class="rounded-lg bg-brand-orange px-8 py-3 text-sm font-bold text-white shadow-sm hover:bg-brand-navy hover:shadow-md transition-all">
        <i class="fa-solid fa-floppy-disk mr-2"></i> Save Category
    </button>
    <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-gray-200 bg-white px-8 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
        Cancel
    </a>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('imageUploader', () => ({
            isDragging: false,
            imageUrl: null,
            fileInput: null,
            
            init() {
                this.fileInput = document.getElementById('image-upload');
            },
            
            handleDrop(event) {
                this.isDragging = false;
                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    this.fileInput.files = files;
                    this.previewImage(files[0]);
                }
            },
            
            handleFileSelect(event) {
                const files = event.target.files;
                if (files.length > 0) {
                    this.previewImage(files[0]);
                }
            },
            
            previewImage(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
            
            removeImage() {
                this.imageUrl = null;
                this.fileInput.value = '';
            }
        }));
    });
</script>
