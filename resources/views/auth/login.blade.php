<x-layout title="Login - Raimart">
    <section class="mx-auto max-w-md px-4 py-14 sm:px-6 lg:px-8">
        <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-brand-navy/5">
            <h1 class="text-center font-serif text-2xl font-bold">Welcome Back</h1>
            <p class="mt-1 text-center text-sm text-brand-navy/50">Login to your Raimart account</p>

            @if ($errors->any())
                <div class="mt-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 text-sm focus:border-brand-orange focus:outline-none">
                </div>

                <div x-data="{ show: false }">
                    <label class="text-sm font-medium">Password</label>
                    <div class="relative mt-1">
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full rounded-lg border border-brand-navy/15 px-4 py-2.5 pr-10 text-sm focus:border-brand-orange focus:outline-none">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-brand-navy/50 hover:text-brand-orange">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-brand-navy/30 text-brand-orange focus:ring-brand-orange">
                    Remember me
                </label>

                <button type="submit" class="w-full rounded-lg bg-brand-orange py-2.5 text-sm font-semibold text-white transition hover:bg-brand-navy">
                    Sign In
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-brand-navy/60">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-brand-orange hover:underline">Create one</a>
            </p>
        </div>
    </section>
</x-layout>
