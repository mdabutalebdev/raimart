@php
    // If settings are missing from DB, fallback to default placeholders so the widget ALWAYS shows up.
    $qcMessenger = filled($siteSettings['quick_messenger_url'] ?? '') ? $siteSettings['quick_messenger_url'] : 'https://m.me/yourpage';
    $qcWhatsappRaw = $siteSettings['quick_whatsapp_number'] ?? '';
    $qcWhatsapp = filled($qcWhatsappRaw) ? preg_replace('/\D+/', '', $qcWhatsappRaw) : '8801234567890';
    $qcPhone = filled($siteSettings['quick_phone_number'] ?? '') ? $siteSettings['quick_phone_number'] : '+8801234567890';
@endphp

{{-- Floating quick-contact bubble: tap to fan out Messenger / WhatsApp / call --}}
<div x-data="supportWidget()" 
     class="fixed bottom-[90px] right-4 md:bottom-8 md:right-8 z-50 flex flex-col items-end gap-3 transition-transform duration-75"
     :style="`transform: translateY(${offsetY}px);`">
     
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-50"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-50"
         class="flex flex-col items-end gap-4 mb-2" style="display:none;">
         
        @if (filled($qcMessenger))
            <a href="{{ $qcMessenger }}" target="_blank" rel="noopener" aria-label="Chat on Messenger"
                class="flex h-10 w-10 md:h-[52px] md:w-[52px] items-center justify-center rounded-full bg-[#0084FF] text-white shadow-[0_4px_14px_0_rgb(0,132,255,0.39)] transition-all hover:scale-110">
                <i class="fa-brands fa-facebook-messenger text-lg md:text-[24px]"></i>
            </a>
        @endif

        @if (filled($qcWhatsapp))
            <a href="https://wa.me/{{ $qcWhatsapp }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
                class="flex h-10 w-10 md:h-[52px] md:w-[52px] items-center justify-center rounded-full bg-[#25D366] text-white shadow-[0_4px_14px_0_rgb(37,211,102,0.39)] transition-all hover:scale-110">
                <i class="fa-brands fa-whatsapp text-xl md:text-[26px]"></i>
            </a>
        @endif

        @if (filled($qcPhone))
            <a href="tel:{{ $qcPhone }}" aria-label="Call us"
                class="flex h-10 w-10 md:h-[52px] md:w-[52px] items-center justify-center rounded-full bg-[#111827] text-white shadow-[0_4px_14px_0_rgb(17,24,39,0.39)] transition-all hover:scale-110">
                <i class="fa-solid fa-phone text-base md:text-[20px]"></i>
            </a>
        @endif
    </div>

    {{-- Main Toggle Button (Draggable on Mobile) --}}
    <button type="button" 
        @touchstart="startDrag"
        @touchmove.prevent="doDrag"
        @touchend="endDrag"
        @click="toggle($event)"
        :aria-expanded="open ? 'true' : 'false'" aria-label="Quick contact"
        class="flex h-12 w-12 md:h-[60px] md:w-[60px] items-center justify-center rounded-full bg-brand-orange text-white shadow-[0_8px_30px_rgb(255,100,51,0.4)] transition-colors duration-300 active:bg-orange-600 cursor-pointer touch-none">
        <i class="fa-solid text-xl md:text-[24px] transition-transform duration-300" :class="open ? 'fa-xmark rotate-90' : 'fa-headset'"></i>
    </button>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('supportWidget', () => ({
            open: false,
            dragging: false,
            hasDragged: false,
            startY: 0,
            offsetY: 0,
            currentY: 0,
            
            startDrag(e) {
                if (!e.touches) return;
                this.dragging = true;
                this.hasDragged = false;
                this.startY = e.touches[0].clientY;
            },
            
            doDrag(e) {
                if (!this.dragging) return;
                let clientY = e.touches[0].clientY;
                let delta = clientY - this.startY;
                
                // If dragged more than 5px, count it as a drag (not a tap)
                if (Math.abs(delta) > 5) {
                    this.hasDragged = true;
                }
                
                let newY = this.currentY + delta;
                
                // Allow dragging UP (negative Y)
                // Prevent dragging DOWN past 0 (so it stays above the bottom menu card)
                if (newY > 0) {
                    newY = 0;
                }
                
                // Prevent dragging TOO high off screen
                let maxY = -(window.innerHeight - 250);
                if (newY < maxY) {
                    newY = maxY;
                }
                
                this.offsetY = newY;
            },
            
            endDrag() {
                if (!this.dragging) return;
                this.dragging = false;
                this.currentY = this.offsetY;
            },
            
            toggle(e) {
                // If it was dragged, don't open the menu
                if (this.hasDragged) {
                    this.hasDragged = false;
                    e.preventDefault();
                    return;
                }
                this.open = !this.open;
            }
        }));
    });
</script>
