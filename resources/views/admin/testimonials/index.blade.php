<x-admin-layout title="Testimonials - Raimart Admin">
    <div class="flex items-center justify-between">
        <h1 class="font-serif text-2xl font-bold">Testimonials</h1>
        <a href="{{ route('admin.testimonials.create') }}" class="rounded-lg bg-brand-orange px-4 py-2 text-sm font-semibold text-white hover:bg-brand-navy">
            <i class="fa-solid fa-plus"></i> Add Testimonial
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-brand-navy/5" x-data="{ showModal: false, selectedTestimonial: null }">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-brand-navy/70">
                <thead class="bg-gray-50 text-xs uppercase text-brand-navy">
                    <tr>
                        <th scope="col" class="px-6 py-4">Customer Details</th>
                        <th scope="col" class="px-6 py-4">Rating</th>
                        <th scope="col" class="px-6 py-4">Review Preview</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($testimonials as $testimonial)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-medium text-brand-navy">{{ $testimonial->name ?? 'N/A' }}</div>
                                <div class="text-xs">{{ $testimonial->phone }}</div>
                                <div class="text-xs">{{ $testimonial->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-brand-orange text-xs">
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="fa-{{ $i < $testimonial->rating ? 'solid' : 'regular' }} fa-star"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="line-clamp-2 max-w-xs">{{ $testimonial->text }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($testimonial->is_active)
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                        Accepted
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                                <button type="button" @click="selectedTestimonial = {{ json_encode($testimonial) }}; showModal = true" class="text-blue-500 hover:underline">View</button>
                                
                                @if(!$testimonial->is_active)
                                    <form action="{{ route('admin.testimonials.accept', $testimonial) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:underline">Accept</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.testimonials.reject', $testimonial) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:underline">Reject</button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" class="inline" onsubmit="return confirm('Delete this review permanently?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-brand-navy/40">No reviews found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- View Details Modal -->
        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm" x-transition.opacity>
            <div @click.outside="showModal = false" class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl sm:p-8" x-show="showModal" x-transition.scale>
                <div class="flex items-center justify-between border-b pb-4 mb-4">
                    <h3 class="font-serif text-xl font-bold text-brand-navy">Review Details</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-lg"></i></button>
                </div>
                
                <template x-if="selectedTestimonial">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-brand-navy">Customer Name</p>
                            <p class="text-sm text-gray-700" x-text="selectedTestimonial.name || 'N/A'"></p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-brand-navy">Phone Number</p>
                                <p class="text-sm text-gray-700" x-text="selectedTestimonial.phone || 'N/A'"></p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-brand-navy">Email</p>
                                <p class="text-sm text-gray-700" x-text="selectedTestimonial.email || 'N/A'"></p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-brand-navy">Rating</p>
                            <div class="text-brand-orange text-sm flex gap-1 mt-1">
                                <template x-for="i in 5">
                                    <i :class="i <= selectedTestimonial.rating ? 'fa-solid fa-star' : 'fa-regular fa-star'"></i>
                                </template>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-brand-navy">Review Message</p>
                            <div class="mt-2 rounded-lg bg-gray-50 p-4 text-sm text-gray-700 whitespace-pre-wrap" x-text="selectedTestimonial.text"></div>
                        </div>
                    </div>
                </template>
                
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="showModal = false" class="rounded-lg bg-brand-navy px-4 py-2 text-sm font-medium text-white hover:bg-brand-navy/90">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
