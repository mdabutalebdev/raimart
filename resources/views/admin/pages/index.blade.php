<x-admin-layout title="Pages - Raimart Admin">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="font-serif text-2xl font-bold">Pages</h1>
            <p class="mt-1 text-sm text-brand-navy/60">Create and edit content pages like Privacy Policy or Terms &amp; Conditions.</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="flex shrink-0 items-center gap-2 rounded-lg bg-brand-orange px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">
            <i class="fa-solid fa-plus"></i> Add Page
        </a>
    </div>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-brand-navy/5">
        <table class="w-full text-sm">
            <thead class="bg-brand-navy/[0.03] text-left text-xs uppercase tracking-wide text-brand-navy/60">
                <tr>
                    <th class="px-5 py-3">Title</th>
                    <th class="px-5 py-3">URL</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Footer</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-navy/5">
                @forelse ($pages as $page)
                    <tr>
                        <td class="px-5 py-3 font-medium">{{ $page->title }}</td>
                        <td class="px-5 py-3 text-brand-navy/60">
                            <a href="{{ route('pages.show', $page) }}" target="_blank" class="hover:text-brand-orange">/page/{{ $page->slug }}</a>
                        </td>
                        <td class="px-5 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $page->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $page->is_active ? 'Active' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-brand-navy/60">{{ $page->show_in_footer ? 'Yes' : 'No' }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.pages.edit', $page) }}" class="font-medium text-brand-orange hover:underline">Edit</a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="ml-3 inline"
                                onsubmit="return confirm('Delete this page?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-brand-navy/40">No pages yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
