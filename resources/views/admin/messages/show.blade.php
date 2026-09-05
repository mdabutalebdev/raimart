<x-admin-layout title="Message - Raimart Admin">
    <a href="{{ route('admin.messages.index') }}" class="text-sm font-medium text-brand-navy/60 hover:text-brand-orange">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to messages
    </a>

    <div class="mt-4 max-w-3xl rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        <h1 class="font-serif text-xl font-bold">{{ $message->subject ?: 'Contact message' }}</h1>
        <p class="mt-1 text-sm text-brand-navy/50">{{ $message->created_at->format('d M Y, h:i A') }}</p>

        <dl class="mt-5 grid gap-4 border-y border-brand-navy/10 py-5 text-sm sm:grid-cols-3">
            <div>
                <dt class="text-brand-navy/50">Name</dt>
                <dd class="mt-0.5 font-medium">{{ $message->name }}</dd>
            </div>
            <div>
                <dt class="text-brand-navy/50">Email</dt>
                <dd class="mt-0.5 font-medium">
                    @if ($message->email)
                        <a href="mailto:{{ $message->email }}" class="text-brand-orange hover:underline">{{ $message->email }}</a>
                    @else — @endif
                </dd>
            </div>
            <div>
                <dt class="text-brand-navy/50">Phone</dt>
                <dd class="mt-0.5 font-medium">
                    @if ($message->phone)
                        <a href="tel:{{ $message->phone }}" class="text-brand-orange hover:underline">{{ $message->phone }}</a>
                    @else — @endif
                </dd>
            </div>
        </dl>

        <p class="mt-5 whitespace-pre-line text-sm leading-relaxed text-brand-navy/80">{{ $message->message }}</p>

        <div class="mt-6 flex gap-3">
            @if ($message->email)
                <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject ?: 'Your message') }}"
                    class="rounded-lg bg-brand-orange px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-navy">Reply by Email</a>
            @endif
            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this message?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg border border-red-200 px-5 py-2.5 text-sm font-semibold text-red-500 hover:bg-red-50">Delete</button>
            </form>
        </div>
    </div>
</x-admin-layout>
