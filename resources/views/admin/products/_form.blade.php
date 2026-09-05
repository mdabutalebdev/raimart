@if ($errors->any())
    <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
        <ul class="list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-lg border border-gray-200" x-data="productWizard()">
    
    <!-- Stepper UI (Clean & Professional) -->
    <div class="border-b border-gray-200 px-4 py-6 sm:px-8">
        <nav aria-label="Progress">
            <ol role="list" class="flex items-start">
                <template x-for="i in maxStep" :key="i">
                    <li class="relative flex-1 text-center">
                        <!-- Line connecting steps (don't show on last step) -->
                        <div x-show="i !== maxStep" class="absolute left-1/2 top-4 w-full flex items-center" aria-hidden="true">
                            <div class="h-0.5 w-full transition-colors duration-300" :class="step > i ? 'bg-brand-orange' : 'bg-gray-200'"></div>
                        </div>
                        
                        <button type="button" @click="step = i" class="relative z-10 flex flex-col items-center group focus:outline-none w-full">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full transition-colors duration-300 mx-auto"
                                :class="step > i ? 'bg-brand-orange hover:bg-brand-orange/90' : (step === i ? 'border-2 border-brand-orange bg-white' : 'border-2 border-gray-300 bg-white group-hover:border-gray-400')">
                                <i x-show="step > i" class="fa-solid fa-check text-white text-xs" style="display: none;"></i>
                                <span x-show="step <= i" class="text-xs font-medium" :class="step === i ? 'text-brand-orange' : 'text-gray-500'" x-text="i"></span>
                            </span>
                            
                            <!-- Titles -->
                            <span class="mt-2 text-xs font-medium transition-colors duration-300" 
                                  :class="step === i ? 'text-brand-orange' : (step > i ? 'text-gray-900' : 'text-gray-400')"
                                  x-text="['Basic Info', 'Pricing', 'Attributes', 'Media', 'Settings'][i-1]">
                            </span>
                        </button>
                    </li>
                </template>
            </ol>
        </nav>
    </div>

    <div class="p-6 sm:p-8">
        <!-- Step 1: General Information -->
        <div x-show="step === 1" style="display: none;">
            <h3 class="text-base font-medium text-gray-900 mb-5">General Information</h3>
            
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
                        @input="delete errors.name"
                        :class="errors.name ? 'border-red-400 ring-1 ring-red-300' : 'border-gray-300'"
                        class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange">
                    <p x-show="errors.name" x-text="errors.name" class="mt-1 text-xs text-red-600" style="display:none;"></p>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" @change="delete errors.category_id"
                        :class="errors.category_id ? 'border-red-400 ring-1 ring-red-300' : 'border-gray-300'"
                        class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange bg-white">
                        <option value="">— Select Category —</option>
                        @foreach ($categoryGroups as $parent)
                            <optgroup label="{{ $parent->name }}">
                                <option value="{{ $parent->id }}" @selected(old('category_id', $product->category_id ?? '') == $parent->id)>{{ $parent->name }} (Main Category)</option>
                                @foreach ($parent->children as $child)
                                    <option value="{{ $child->id }}" @selected(old('category_id', $product->category_id ?? '') == $child->id)>&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <p x-show="errors.category_id" x-text="errors.category_id" class="mt-1 text-xs text-red-600" style="display:none;"></p>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Brand</label>
                    <select name="brand_id" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange bg-white">
                        <option value="">— No Brand —</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected(old('brand_id', $product->brand_id ?? '') == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        Manage brands in <a href="{{ route('admin.brands.index') }}" class="text-brand-orange hover:underline">Brands</a>.
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Short Description</label>
                    <textarea name="short_description" rows="2" class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Brief overview displayed next to the product image.</p>
                </div>

                <div class="sm:col-span-2" wire:ignore>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                    <!-- Hidden input to store Quill content -->
                    <input type="hidden" name="description" id="description-input" value="{{ old('description', $product->description ?? '') }}">
                    <div id="quill-editor" class="bg-white rounded-b-md border-gray-300" style="min-height: 200px;">
                        {!! old('description', $product->description ?? '') !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: Pricing & Inventory -->
        <div x-show="step === 2" style="display: none;" x-cloak>
            <h3 class="text-base font-medium text-gray-900 mb-5">Pricing & Inventory</h3>
            
            <div class="grid gap-8 sm:grid-cols-2">
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Pricing</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price (৳) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}"
                                @input="delete errors.price"
                                :class="errors.price ? 'border-red-400 ring-1 ring-red-300' : 'border-gray-300'"
                                class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange">
                            <p x-show="errors.price" x-text="errors.price" class="mt-1 text-xs text-red-600" style="display:none;"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Compare at Price (৳)</label>
                            <input type="number" step="0.01" name="old_price" value="{{ old('old_price', $product->old_price ?? '') }}"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange">
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-4 pb-2 border-b border-gray-100">Inventory</h4>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock Quantity <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}"
                                @input="delete errors.stock"
                                :class="errors.stock ? 'border-red-400 ring-1 ring-red-300' : 'border-gray-300'"
                                class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange">
                            <p x-show="errors.stock" x-text="errors.stock" class="mt-1 text-xs text-red-600" style="display:none;"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">SKU (Stock Keeping Unit)</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Weight / Size</label>
                            <input type="text" name="weight" value="{{ old('weight', $product->weight ?? '') }}" placeholder="e.g. 1kg, 500ml, 400g"
                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: Product Attributes -->
        <div x-show="step === 3" style="display: none;" x-cloak>
            <div x-data="attributeBuilder({{ isset($product) && $product->attributes ? json_encode($product->attributes) : '[]' }})">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-medium text-gray-900">Variants & Options</h3>
                        <p class="text-xs text-gray-500 mt-1">Does this product come in multiple sizes, colors, or materials?</p>
                    </div>
                    <button type="button" @click="attributes.push({name: '', values: ''})" class="text-sm font-medium text-brand-orange hover:text-brand-navy">
                        + Add Option
                    </button>
                </div>
                
                <input type="hidden" name="attributes" :value="JSON.stringify(attributes)">
                
                <div class="space-y-4 mt-4">
                    <template x-for="(attr, index) in attributes" :key="index">
                        <div class="flex items-start gap-4 p-4 border border-gray-200 rounded-md bg-gray-50/50">
                            <div class="flex-1 grid sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Option Name</label>
                                    <input type="text" x-model="attr.name" placeholder="e.g., Size, Color" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Option Values (comma separated)</label>
                                    <input type="text" x-model="attr.values" placeholder="e.g., Small, Medium, Large" class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none focus:ring-1 focus:ring-brand-orange bg-white">
                                </div>
                            </div>
                            <button type="button" @click="attributes.splice(index, 1)" class="mt-6 text-gray-400 hover:text-red-500 transition-colors p-1">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </template>

                    <template x-if="attributes.length === 0">
                        <div class="text-center py-8 px-4 rounded-md border-2 border-dashed border-gray-200">
                            <p class="text-sm text-gray-500">This product has no variants.</p>
                            <button type="button" @click="attributes.push({name: '', values: ''})" class="mt-2 text-sm font-medium text-brand-orange hover:text-brand-navy">
                                Add variants like size or color
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Step 4: Product Media -->
        <div x-show="step === 4" style="display: none;" x-cloak>
            <h3 class="text-base font-medium text-gray-900 mb-5">Media</h3>
            
            <div class="grid gap-6 sm:grid-cols-3">
                <div class="sm:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Primary Image <span class="text-red-500">*</span></label>
                    <div x-data="mainImageUploader()" class="relative aspect-square rounded-md border border-dashed border-gray-300 overflow-hidden bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-colors cursor-pointer">
                        <template x-if="!imageUrl && !hasExistingImage">
                            <div class="text-center p-4">
                                <i class="fa-regular fa-image text-2xl text-gray-400 mb-2"></i>
                                <span class="block text-xs text-gray-500">Upload Image</span>
                            </div>
                        </template>
                        <template x-if="imageUrl">
                            <img :src="imageUrl" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!imageUrl && hasExistingImage">
                            <img src="{{ isset($product) && $product->main_image ? image_url($product->main_image) : '' }}" class="w-full h-full object-cover">
                        </template>
                        <input type="file" name="main_image" accept="image/*" data-required="{{ isset($product) && $product->main_image ? '0' : '1' }}" class="absolute inset-0 opacity-0 cursor-pointer" @change="handleFileChange($event); delete errors.main_image">
                    </div>
                    <p x-show="errors.main_image" x-text="errors.main_image" class="mt-1 text-xs text-red-600" style="display:none;"></p>
                </div>

                <div class="sm:col-span-2" x-data="multiImageUploader()">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Media Gallery</label>
                    <div class="flex justify-center items-center rounded-md border border-dashed border-gray-300 px-6 py-10 transition-colors bg-gray-50"
                         :class="{ 'bg-gray-100 border-gray-400': isDragging }"
                         @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleDrop($event)">
                         
                        <div class="text-center">
                            <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-2"></i>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="images-upload" class="relative cursor-pointer rounded-md font-medium text-brand-orange hover:text-brand-orange/80 focus-within:outline-none">
                                    <span>Upload files</span>
                                    <input id="images-upload" name="images[]" type="file" multiple accept="image/*,video/*" class="sr-only" @change="handleFileSelect">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Images (PNG, JPG, WEBP) &amp; videos (MP4, MOV, WEBM) up to 20MB</p>
                        </div>
                    </div>

                    <!-- Previews of newly selected files -->
                    <template x-if="newFiles.length > 0">
                        <div class="mt-4">
                            <p class="text-xs font-medium text-gray-500 mb-2">Selected for upload</p>
                            <div class="flex flex-wrap gap-3">
                                <template x-for="(fileItem, index) in newFiles" :key="index">
                                    <div class="relative group">
                                        <template x-if="fileItem.type === 'image'">
                                            <img :src="fileItem.url" class="h-16 w-16 rounded border border-gray-200 object-cover">
                                        </template>
                                        <template x-if="fileItem.type === 'video'">
                                            <div class="h-16 w-16 rounded border border-gray-200 bg-gray-100 flex items-center justify-center">
                                                <i class="fa-solid fa-video text-gray-400"></i>
                                            </div>
                                        </template>
                                        <button type="button" @click="removeNewFile(index)" class="absolute -right-1.5 -top-1.5 rounded-full bg-white border border-gray-200 w-5 h-5 text-gray-600 hover:text-red-500 flex items-center justify-center shadow-sm">
                                            <i class="fa-solid fa-xmark" style="font-size: 10px;"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    
                    <!-- Existing Gallery -->
                    @if (! empty($product) && $product->images->count())
                        <div class="mt-6 border-t border-gray-100 pt-4">
                            <p class="text-xs font-medium text-gray-500 mb-2">Current gallery</p>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($product->images as $image)
                                    <div class="relative group">
                                        @if(is_video($image->image))
                                            <video src="{{ image_url($image->image) }}" class="h-16 w-16 rounded border border-gray-200 object-cover" muted></video>
                                        @else
                                            <img src="{{ image_url($image->image) }}" class="h-16 w-16 rounded border border-gray-200 object-cover">
                                        @endif
                                        <button type="submit" form="delete-image-{{ $image->id }}" class="absolute -right-1.5 -top-1.5 rounded-full bg-white border border-gray-200 w-5 h-5 text-gray-600 hover:text-red-500 flex items-center justify-center shadow-sm">
                                            <i class="fa-solid fa-trash-can" style="font-size: 10px;"></i>
                                        </button>
                                    </div>
                                    <form id="delete-image-{{ $image->id }}" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Step 5: Visibility & Settings -->
        <div x-show="step === 5" style="display: none;" x-cloak>
            <h3 class="text-base font-medium text-gray-900 mb-5">Visibility & Settings</h3>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $product->is_active ?? true)) class="h-4 w-4 rounded border-gray-300 text-brand-orange focus:ring-brand-orange">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Active (Visible)</label>
                        <p class="text-gray-500 text-xs mt-0.5">Product will be visible on the store and available for purchase.</p>
                    </div>
                </div>

                <div class="flex items-start pt-3 border-t border-gray-100">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured', $product->is_featured ?? false)) class="h-4 w-4 rounded border-gray-300 text-brand-orange focus:ring-brand-orange">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_featured" class="font-medium text-gray-700">Featured Product</label>
                        <p class="text-gray-500 text-xs mt-0.5">Show this product in the featured section on the homepage.</p>
                    </div>
                </div>

                <div class="flex items-start pt-3 border-t border-gray-100">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" name="is_best_seller" id="is_best_seller" value="1" @checked(old('is_best_seller', $product->is_best_seller ?? false)) class="h-4 w-4 rounded border-gray-300 text-brand-orange focus:ring-brand-orange">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_best_seller" class="font-medium text-gray-700">Best Seller Label</label>
                        <p class="text-gray-500 text-xs mt-0.5">Display a Best Seller badge on the product card.</p>
                    </div>
                </div>

                <div class="flex items-start pt-3 border-t border-gray-100">
                    <div class="flex h-5 items-center">
                        <input type="checkbox" name="is_new_arrival" id="is_new_arrival" value="1" @checked(old('is_new_arrival', $product->is_new_arrival ?? false)) class="h-4 w-4 rounded border-gray-300 text-brand-orange focus:ring-brand-orange">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_new_arrival" class="font-medium text-gray-700">New Arrival Label</label>
                        <p class="text-gray-500 text-xs mt-0.5">Display a New Arrival badge on the product card.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Actions -->
        <div class="sticky bottom-0 z-40 mt-8 flex items-center justify-end border-t border-gray-200 bg-white py-4 w-full gap-3">
            <button type="button" @click="step--" x-show="step > 1" class="mr-auto rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-orange focus:ring-offset-2 shadow-sm" style="display: none;">
                Previous
            </button>

            <button type="submit"
                @click="if (! validateAll()) { $event.preventDefault(); } else if (window.quill) { document.getElementById('description-input').value = quill.root.innerHTML; }"
                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand-orange focus:ring-offset-2">
                Save
            </button>

            <button type="button" @click="if (validateStep(step)) step++" x-show="step < maxStep" class="rounded-md border border-transparent bg-brand-navy px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-navy/90 focus:outline-none focus:ring-2 focus:ring-brand-navy focus:ring-offset-2">
                Next
            </button>
        </div>
    </div>
</div>

<!-- Initialize Scripts -->
<script>
    // Initialize Quill Editor
    var quill;
    document.addEventListener("livewire:navigated", function() {
        if(typeof Quill !== 'undefined' && document.getElementById('quill-editor')) {
            quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Write a compelling product description...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        ['link', 'clean']
                    ]
                }
            });
        }
    });

    // Multi-step wizard state + step-by-step client-side validation.
    // Defined as a plain global (not Alpine.data) so it works even though the
    // admin loads Alpine twice (CDN + Livewire).
    window.productWizard = function () {
        return {
            step: 1,
            maxStep: 5,
            errors: {},

            // Required fields per step (main_image only when marked required).
            stepFields: {
                1: [
                    { name: 'name', msg: 'Product name is required.' },
                    { name: 'category_id', msg: 'Please select a category.' },
                ],
                2: [
                    { name: 'price', msg: 'Price is required.' },
                    { name: 'stock', msg: 'Stock quantity is required.' },
                ],
                4: [
                    { name: 'main_image', file: true, msg: 'Primary image is required.' },
                ],
            },

            check(fields, errs) {
                let ok = true;
                (fields || []).forEach((f) => {
                    const el = this.$root.querySelector('[name="' + f.name + '"]');
                    let bad;
                    if (f.file) {
                        bad = !!el && el.dataset.required === '1' && el.files.length === 0;
                    } else {
                        bad = !el || el.value.trim() === '';
                    }
                    if (bad) { errs[f.name] = f.msg; ok = false; } else { delete errs[f.name]; }
                });
                return ok;
            },

            // Validate the current step (Next button).
            validateStep(n) {
                const errs = Object.assign({}, this.errors);
                const ok = this.check(this.stepFields[n], errs);
                this.errors = errs;
                return ok;
            },

            // Validate every step; jump to the first one with an error (Save button).
            validateAll() {
                const errs = {};
                let firstBad = null;
                [1, 2, 3, 4, 5].forEach((n) => {
                    if (!this.check(this.stepFields[n], errs) && firstBad === null) {
                        firstBad = n;
                    }
                });
                this.errors = errs;
                if (firstBad !== null) this.step = firstBad;
                return firstBad === null;
            },
        };
    };

    document.addEventListener('alpine:init', () => {
        Alpine.data('attributeBuilder', (initialAttributes) => ({
            attributes: initialAttributes || []
        }));

        Alpine.data('mainImageUploader', () => ({
            imageUrl: null,
            hasExistingImage: {{ isset($product) && $product->main_image ? 'true' : 'false' }},
            handleFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.imageUrl = URL.createObjectURL(file);
                }
            }
        }));

        Alpine.data('multiImageUploader', () => ({
            isDragging: false,
            newFiles: [],
            
            handleDrop(event) {
                this.isDragging = false;
                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    this.appendFiles(files);
                }
            },
            
            handleFileSelect(event) {
                const files = event.target.files;
                if (files.length > 0) {
                    this.appendFiles(files);
                }
            },
            
            appendFiles(files) {
                const dataTransfer = new DataTransfer();
                const fileInput = document.getElementById('images-upload');
                
                // Add existing tracked files to data transfer
                this.newFiles.forEach(item => {
                    dataTransfer.items.add(item.file);
                });
                
                // Add new files
                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/') || file.type.startsWith('video/')) {
                        // Prevent duplicates by checking if file already exists in newFiles
                        const exists = this.newFiles.find(f => f.file.name === file.name && f.file.size === file.size);
                        
                        if (!exists) {
                            dataTransfer.items.add(file);
                            const isImage = file.type.startsWith('image/');
                            if (isImage) {
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    this.newFiles.push({
                                        file: file,
                                        url: e.target.result,
                                        type: 'image'
                                    });
                                };
                                reader.readAsDataURL(file);
                            } else {
                                this.newFiles.push({
                                    file: file,
                                    url: null,
                                    type: 'video'
                                });
                            }
                        }
                    }
                });
                
                fileInput.files = dataTransfer.files;
            },
            
            removeNewFile(index) {
                this.newFiles.splice(index, 1);
                
                // Rebuild FileList for the input
                const dataTransfer = new DataTransfer();
                const fileInput = document.getElementById('images-upload');
                
                this.newFiles.forEach(item => {
                    dataTransfer.items.add(item.file);
                });
                
                fileInput.files = dataTransfer.files;
            }
        }));
    });
</script>
