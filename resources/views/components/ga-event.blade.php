@props(['event', 'ecommerce' => []])

{{--
    Pushes one GA4 Enhanced Ecommerce event into the dataLayer BEFORE the GTM
    loader runs (this lands in the @stack('datalayer') slot in the layout head).
    The `ecommerce: null` reset stops GTM from merging leftover data between events.
--}}
@push('datalayer')
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ ecommerce: null });
    window.dataLayer.push({ event: @json($event), ecommerce: @json((object) $ecommerce) });
</script>
@endpush
