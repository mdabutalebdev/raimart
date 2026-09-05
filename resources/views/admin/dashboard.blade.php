<x-admin-layout title="Dashboard - Raimart Admin">
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-serif text-2xl font-bold">Dashboard Overview</h1>
        <p class="text-sm text-gray-500">{{ now()->format('l, j F Y') }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 gap-5 lg:grid-cols-4">
        <!-- Revenue -->
        <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 p-6 shadow-sm">
            <p class="text-sm font-medium text-white/80">Total Revenue</p>
            <p class="mt-2 font-serif text-3xl font-bold text-white tracking-tight">৳{{ number_format($stats['revenue'], 2) }}</p>
        </div>

        <!-- This Month Revenue -->
        <div class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-400 p-6 shadow-sm">
            <p class="text-sm font-medium text-white/80">Revenue (This Month)</p>
            <p class="mt-2 font-serif text-3xl font-bold text-white tracking-tight">৳{{ number_format($stats['this_month_revenue'], 2) }}</p>
        </div>

        <!-- Total Orders -->
        <div class="rounded-2xl bg-gradient-to-r from-violet-600 to-purple-500 p-6 shadow-sm">
            <p class="text-sm font-medium text-white/80">Total Orders</p>
            <p class="mt-2 font-serif text-3xl font-bold text-white tracking-tight">{{ number_format($stats['orders']) }}</p>
        </div>

        <!-- Pending Orders -->
        <div class="rounded-2xl bg-gradient-to-r from-orange-400 to-amber-400 p-6 shadow-sm">
            <p class="text-sm font-medium text-white/80">Pending Orders</p>
            <p class="mt-2 font-serif text-3xl font-bold text-white tracking-tight">{{ number_format($stats['pending_orders']) }}</p>
        </div>

        <!-- Customers -->
        <div class="rounded-2xl bg-gradient-to-r from-pink-500 to-rose-400 p-6 shadow-sm">
            <p class="text-sm font-medium text-white/80">Total Customers</p>
            <p class="mt-2 font-serif text-3xl font-bold text-white tracking-tight">{{ number_format($stats['customers']) }}</p>
        </div>

        <!-- Products -->
        <div class="rounded-2xl bg-gradient-to-r from-sky-500 to-blue-400 p-6 shadow-sm">
            <p class="text-sm font-medium text-white/80">Total Products</p>
            <p class="mt-2 font-serif text-3xl font-bold text-white tracking-tight">{{ number_format($stats['products']) }}</p>
        </div>

        <!-- Categories -->
        <div class="rounded-2xl bg-gradient-to-r from-fuchsia-600 to-pink-500 p-6 shadow-sm">
            <p class="text-sm font-medium text-white/80">Total Categories</p>
            <p class="mt-2 font-serif text-3xl font-bold text-white tracking-tight">{{ number_format($stats['categories']) }}</p>
        </div>

        <!-- Low Stock -->
        <div class="rounded-2xl bg-gradient-to-r from-red-500 to-rose-500 p-6 shadow-sm">
            <p class="text-sm font-medium text-white/80">Low Stock Products</p>
            <p class="mt-2 font-serif text-3xl font-bold text-white tracking-tight">{{ number_format($stats['low_stock_products']) }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Revenue Chart -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5 lg:col-span-2">
            <h2 class="font-serif text-lg font-bold mb-4">Revenue (Last 7 Days)</h2>
            <div x-data="revenueChart()" x-init="initChart()" class="w-full">
                <div id="revenue-chart"></div>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
            <h2 class="font-serif text-lg font-bold mb-4">Order Statuses</h2>
            <div x-data="statusChart()" x-init="initChart()" class="w-full flex justify-center mt-6">
                <div id="status-chart"></div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="mt-6 rounded-xl bg-white p-6 shadow-sm ring-1 ring-brand-navy/5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-serif text-lg font-bold">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" wire:navigate class="text-sm text-brand-orange hover:underline font-medium">View All</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-brand-navy/10 text-brand-navy/50">
                        <th class="pb-3 pr-4 font-medium">Order #</th>
                        <th class="pb-3 pr-4 font-medium">Customer</th>
                        <th class="pb-3 pr-4 font-medium">Date</th>
                        <th class="pb-3 pr-4 font-medium">Total</th>
                        <th class="pb-3 pr-4 font-medium">Status</th>
                        <th class="pb-3 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr class="border-b border-brand-navy/5 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 pr-4 font-medium text-brand-navy">#{{ $order->order_number }}</td>
                            <td class="py-3 pr-4">
                                <p class="font-medium text-gray-900">{{ $order->name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->phone }}</p>
                            </td>
                            <td class="py-3 pr-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="py-3 pr-4 font-medium">৳{{ number_format($order->total, 2) }}</td>
                            <td class="py-3 pr-4">
                                @if($order->status == 'pending')
                                    <span class="inline-flex items-center rounded-full bg-orange-50 px-2 py-1 text-xs font-medium text-brand-orange ring-1 ring-inset ring-brand-orange/20">Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Processing</span>
                                @elseif($order->status == 'completed')
                                    <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Completed</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Cancelled</span>
                                @endif
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" wire:navigate class="inline-flex items-center gap-1 rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    <i class="fa-regular fa-eye text-xs"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                                    <p>No recent orders found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Scripts -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('revenueChart', () => ({
                initChart() {
                    const data = {!! json_encode($revenueChart) !!};
                    
                    const options = {
                        series: [{
                            name: 'Revenue (৳)',
                            data: data.data
                        }],
                        chart: {
                            height: 300,
                            type: 'area',
                            fontFamily: 'inherit',
                            toolbar: { show: false },
                            zoom: { enabled: false }
                        },
                        colors: ['#F97316'], // brand-orange
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.4,
                                opacityTo: 0.05,
                                stops: [0, 90, 100]
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        xaxis: {
                            categories: data.labels,
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: {
                                style: { colors: '#6B7280', fontSize: '12px' }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#6B7280', fontSize: '12px' },
                                formatter: (value) => { return '৳' + value }
                            }
                        },
                        grid: {
                            borderColor: '#F3F4F6',
                            strokeDashArray: 4,
                            yaxis: { lines: { show: true } }
                        },
                        tooltip: { theme: 'light' }
                    };

                    const chart = new ApexCharts(document.querySelector("#revenue-chart"), options);
                    chart.render();
                }
            }));

            Alpine.data('statusChart', () => ({
                initChart() {
                    const data = {!! json_encode($statusChart) !!};
                    const totalOrders = data.data.reduce((a, b) => a + b, 0);
                    
                    let chartData = data.data;
                    let chartLabels = data.labels;
                    let chartColors = ['#F97316', '#3B82F6', '#10B981', '#EF4444'];
                    
                    // If no orders exist, show an empty gray circle
                    if (totalOrders === 0) {
                        chartData = [1];
                        chartLabels = ['No Orders Yet'];
                        chartColors = ['#F3F4F6']; // gray-100
                    }
                    
                    const options = {
                        series: chartData,
                        chart: {
                            type: 'donut',
                            height: 300,
                            fontFamily: 'inherit',
                        },
                        labels: chartLabels,
                        colors: chartColors,
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '75%',
                                    labels: {
                                        show: true,
                                        name: { fontSize: '14px', color: '#6B7280' },
                                        value: {
                                            fontSize: '24px',
                                            fontWeight: 600,
                                            color: '#111827',
                                            formatter: function (val) {
                                                return totalOrders === 0 ? 0 : val;
                                            }
                                        },
                                        total: {
                                            show: true,
                                            showAlways: true,
                                            label: totalOrders === 0 ? 'No Orders' : 'Total',
                                            fontSize: '14px',
                                            color: '#6B7280',
                                            formatter: function (w) {
                                                return totalOrders;
                                            }
                                        }
                                    }
                                }
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: { width: 0 },
                        legend: {
                            show: totalOrders > 0,
                            position: 'bottom',
                            markers: { radius: 12 },
                            itemMargin: { horizontal: 10, vertical: 5 }
                        },
                        tooltip: {
                            enabled: totalOrders > 0,
                            theme: 'light',
                            y: { formatter: function (val) { return val + " Orders" } }
                        }
                    };

                    const chart = new ApexCharts(document.querySelector("#status-chart"), options);
                    chart.render();
                }
            }));
        });
    </script>
</x-admin-layout>
