<x-admin-layout title="Reports - Raimart Admin">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="font-serif text-2xl font-bold">Sales Reports</h1>
            <p class="mt-0.5 text-sm text-brand-navy/60">{{ $from->format('d M Y') }} – {{ $to->format('d M Y') }}</p>
        </div>
        <a href="{{ route('admin.reports.export', request()->query()) }}"
            class="flex shrink-0 items-center gap-2 rounded border border-brand-navy/15 px-4 py-2 text-sm font-semibold text-brand-navy transition hover:border-brand-orange hover:text-brand-orange">
            <i class="fa-solid fa-file-csv"></i> Export CSV
        </a>
    </div>

    {{-- Range picker --}}
    <form action="{{ route('admin.reports.index') }}" class="mt-4 flex flex-wrap items-center gap-2 rounded border border-brand-navy/10 bg-white p-3">
        <input type="date" name="from" value="{{ $from->toDateString() }}"
            class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
        <span class="text-sm text-brand-navy/40">to</span>
        <input type="date" name="to" value="{{ $to->toDateString() }}"
            class="rounded border border-brand-navy/15 px-3 py-2 text-sm focus:border-brand-orange focus:outline-none">
        <button type="submit" class="rounded bg-brand-navy px-5 py-2 text-sm font-semibold text-white transition hover:bg-brand-orange">Apply</button>

        <div class="ml-auto flex gap-2 text-xs">
            <a href="{{ route('admin.reports.index', ['from' => now()->toDateString(), 'to' => now()->toDateString()]) }}" class="rounded border border-brand-navy/15 px-3 py-2 font-medium hover:border-brand-orange hover:text-brand-orange">Today</a>
            <a href="{{ route('admin.reports.index', ['from' => now()->subDays(6)->toDateString(), 'to' => now()->toDateString()]) }}" class="rounded border border-brand-navy/15 px-3 py-2 font-medium hover:border-brand-orange hover:text-brand-orange">7 days</a>
            <a href="{{ route('admin.reports.index', ['from' => now()->startOfMonth()->toDateString(), 'to' => now()->toDateString()]) }}" class="rounded border border-brand-navy/15 px-3 py-2 font-medium hover:border-brand-orange hover:text-brand-orange">This month</a>
        </div>
    </form>

    {{-- Summary cards --}}
    @php
        $cards = [
            ['label' => 'Revenue (paid)', 'value' => '৳'.number_format($summary['revenue']), 'icon' => 'sack-dollar', 'tone' => 'text-brand-orange'],
            ['label' => 'Orders', 'value' => number_format($summary['orders']), 'icon' => 'bag-shopping', 'tone' => 'text-brand-navy'],
            ['label' => 'Items sold', 'value' => number_format($summary['items_sold']), 'icon' => 'box', 'tone' => 'text-brand-navy'],
            ['label' => 'Avg. order value', 'value' => '৳'.number_format($summary['avg_order']), 'icon' => 'chart-simple', 'tone' => 'text-brand-navy'],
            ['label' => 'Discounts given', 'value' => '৳'.number_format($summary['discount']), 'icon' => 'ticket', 'tone' => 'text-green-600'],
            ['label' => 'Delivery collected', 'value' => '৳'.number_format($summary['shipping']), 'icon' => 'truck', 'tone' => 'text-brand-navy'],
        ];
    @endphp
    <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($cards as $card)
            <div class="rounded border border-brand-navy/10 bg-white p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-wide text-brand-navy/50">{{ $card['label'] }}</p>
                    <i class="fa-solid fa-{{ $card['icon'] }} text-brand-navy/20"></i>
                </div>
                <p class="mt-1.5 text-2xl font-bold {{ $card['tone'] }}">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Revenue chart --}}
    <div class="mt-4 rounded border border-brand-navy/10 bg-white p-4">
        <h2 class="mb-3 border-l-4 border-brand-orange pl-3 text-base font-bold">Revenue over time</h2>
        <div id="revenue-chart"></div>
    </div>

    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        {{-- Top products --}}
        <div class="overflow-x-auto rounded border border-brand-navy/10 bg-white">
            <h2 class="border-b border-brand-navy/10 px-4 py-3 text-base font-bold">Top selling products</h2>
            <table class="w-full text-left text-sm">
                <thead class="bg-brand-navy/[0.03] text-xs uppercase tracking-wide text-brand-navy/60">
                    <tr>
                        <th class="px-4 py-2.5 font-semibold">Product</th>
                        <th class="px-4 py-2.5 text-center font-semibold">Qty</th>
                        <th class="px-4 py-2.5 text-right font-semibold">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-brand-navy/5">
                    @forelse ($topProducts as $row)
                        <tr>
                            <td class="px-4 py-2.5">{{ $row->product_name }}</td>
                            <td class="px-4 py-2.5 text-center font-medium">{{ $row->qty }}</td>
                            <td class="px-4 py-2.5 text-right font-semibold text-brand-orange">৳{{ number_format($row->revenue) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-4 py-8 text-center text-brand-navy/40">No sales in this range.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Breakdown --}}
        <div class="space-y-4">
            <div class="overflow-x-auto rounded border border-brand-navy/10 bg-white">
                <h2 class="border-b border-brand-navy/10 px-4 py-3 text-base font-bold">Orders by status</h2>
                <table class="w-full text-left text-sm">
                    <tbody class="divide-y divide-brand-navy/5">
                        @forelse ($byStatus as $row)
                            <tr>
                                <td class="px-4 py-2.5 capitalize">{{ $row->status }}</td>
                                <td class="px-4 py-2.5 text-center">{{ $row->count }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold">৳{{ number_format($row->total) }}</td>
                            </tr>
                        @empty
                            <tr><td class="px-4 py-8 text-center text-brand-navy/40">No orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="overflow-x-auto rounded border border-brand-navy/10 bg-white">
                <h2 class="border-b border-brand-navy/10 px-4 py-3 text-base font-bold">Orders by delivery area</h2>
                <table class="w-full text-left text-sm">
                    <tbody class="divide-y divide-brand-navy/5">
                        @forelse ($byArea as $row)
                            <tr>
                                <td class="px-4 py-2.5">{{ $row->delivery_area === 'inside' ? 'Inside Dhaka' : ($row->delivery_area === 'outside' ? 'Outside Dhaka' : 'Not set') }}</td>
                                <td class="px-4 py-2.5 text-center">{{ $row->count }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold">৳{{ number_format($row->total) }}</td>
                            </tr>
                        @empty
                            <tr><td class="px-4 py-8 text-center text-brand-navy/40">No orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // ApexCharts is loaded by the admin layout.
        document.addEventListener('livewire:navigated', function () {
            var el = document.getElementById('revenue-chart');
            if (!el || typeof ApexCharts === 'undefined' || el.dataset.rendered) return;
            el.dataset.rendered = '1';

            new ApexCharts(el, {
                chart: { type: 'area', height: 280, toolbar: { show: false } },
                series: [{ name: 'Revenue', data: @json($chart['revenue']) }],
                xaxis: { categories: @json($chart['labels']), labels: { rotate: -45, style: { fontSize: '10px' } } },
                yaxis: { labels: { formatter: function (v) { return '৳' + Math.round(v).toLocaleString(); } } },
                colors: ['#f97316'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05 } },
                tooltip: { y: { formatter: function (v) { return '৳' + Math.round(v).toLocaleString(); } } },
            }).render();
        });
    </script>
</x-admin-layout>
