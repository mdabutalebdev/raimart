@php
    // Expects: $name, $value, $label, $checked; optional $count (null = no badge)
    $count = $count ?? null;
    $disabled = ($count !== null && $count === 0 && ! $checked);
@endphp
<label class="flex items-center gap-2.5 rounded-lg px-2 py-1.5 transition {{ $disabled ? 'cursor-not-allowed opacity-40' : 'cursor-pointer hover:bg-brand-bg' }}">
    <input type="checkbox" name="{{ $name }}" value="{{ $value }}" @checked($checked) @disabled($disabled)
        onchange="this.form.submit()" class="peer sr-only">
    <span class="flex h-[18px] w-[18px] shrink-0 items-center justify-center rounded-[5px] border-2 border-brand-navy/25 bg-white transition-colors peer-checked:border-brand-orange peer-checked:bg-brand-orange">
        <i class="fa-solid fa-check text-[9px] text-white"></i>
    </span>
    <span class="text-sm text-brand-navy/80 transition peer-checked:font-semibold peer-checked:text-brand-navy">{{ $label }}</span>
    @if ($count !== null)
        <span class="ml-auto rounded-full bg-brand-bg px-2 py-0.5 text-[11px] font-semibold text-brand-navy/45 transition peer-checked:bg-brand-orange/10 peer-checked:text-brand-orange">{{ $count }}</span>
    @endif
</label>
