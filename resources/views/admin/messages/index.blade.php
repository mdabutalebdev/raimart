<x-admin-layout title="Messages - Raimart Admin">
    <h1 class="font-serif text-2xl font-bold">Messages</h1>
    <p class="mt-1 text-sm text-brand-navy/60">Submissions from the website contact form.</p>

    <div class="mt-6 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-brand-navy/5">
        <table class="w-full text-sm">
            <thead class="bg-brand-navy/[0.03] text-left text-xs uppercase tracking-wide text-brand-navy/60">
                <tr>
                    <th class="px-5 py-3">From</th>
                    <th class="px-5 py-3">Subject</th>
                    <th class="px-5 py-3">Received</th>
                    <th class="px-5 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-brand-navy/5">
                @forelse ($messages as $message)
                    <tr class="{{ $message->is_read ? '' : 'bg-brand-orange/[0.04]' }}">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2 font-medium">
                                @unless ($message->is_read)
                                    <span class="h-2 w-2 shrink-0 rounded-full bg-brand-orange" title="Unread"></span>
                                @endunless
                                {{ $message->name }}
                            </div>
                            <div class="text-xs text-brand-navy/50">{{ $message->email ?: $message->phone }}</div>
                        </td>
                        <td class="px-5 py-3 text-brand-navy/70">{{ $message->subject ?: '—' }}</td>
                        <td class="px-5 py-3 text-brand-navy/50">{{ $message->created_at->diffForHumans() }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.messages.show', $message) }}" class="font-medium text-brand-orange hover:underline">View</a>
                            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="ml-3 inline"
                                onsubmit="return confirm('Delete this message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-brand-navy/40">No messages yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $messages->links() }}</div>
</x-admin-layout>
