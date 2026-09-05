<x-layout title="User Dashboard - Raimart">
    <div class="bg-[#F8F9FA] min-h-screen py-10">
        <div class="container mx-auto gb-container">
            
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-brand-navy">My Account</h1>
                <p class="text-gray-500 mt-2">Manage your profile, track orders, and update your settings.</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-red-500 text-xl mt-0.5"></i>
                    <div class="text-red-800">
                        <ul class="list-disc pl-4 space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar -->
                <aside class="w-full lg:w-1/4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:sticky lg:top-24">
                        <!-- Profile Summary -->
                        <div class="p-6 border-b border-gray-100 text-center bg-gradient-to-b from-brand-orange/10 to-transparent">
                            <div class="relative w-24 h-24 mx-auto mb-4">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                                @else
                                    <div class="w-full h-full rounded-full bg-brand-orange text-white flex items-center justify-center text-3xl font-bold shadow-md mx-auto">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold text-brand-navy">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        
                        <!-- Navigation -->
                        <nav class="p-4 space-y-2">
                            <button onclick="switchTab('dashboard')" id="tab-btn-dashboard" class="w-full flex items-center gap-3 px-4 py-3 text-left rounded-xl transition-all duration-200 bg-brand-orange text-white font-medium shadow-md shadow-brand-orange/20">
                                <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                                Dashboard
                            </button>
                            <button onclick="switchTab('orders')" id="tab-btn-orders" class="w-full flex items-center gap-3 px-4 py-3 text-left rounded-xl transition-all duration-200 text-brand-navy hover:bg-brand-bg hover:text-brand-orange font-medium">
                                <i class="fa-solid fa-box w-5 text-center"></i>
                                My Orders
                            </button>
                            <button onclick="switchTab('settings')" id="tab-btn-settings" class="w-full flex items-center gap-3 px-4 py-3 text-left rounded-xl transition-all duration-200 text-brand-navy hover:bg-brand-bg hover:text-brand-orange font-medium">
                                <i class="fa-solid fa-user-pen w-5 text-center"></i>
                                Profile Settings
                            </button>
                            
                            <hr class="my-4 border-gray-100">
                            
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-left rounded-xl transition-colors text-red-600 hover:bg-red-50 font-medium">
                                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                                    Logout
                                </button>
                            </form>
                        </nav>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="w-full lg:w-3/4">
                    
                    <!-- DASHBOARD TAB -->
                    <div id="tab-dashboard" class="space-y-6 block animate-fade-in">
                        <!-- Overview Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition">
                                <div class="w-14 h-14 rounded-full bg-brand-bg flex items-center justify-center text-brand-orange text-2xl shrink-0">
                                    <i class="fa-solid fa-basket-shopping"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Total Orders</p>
                                    <h3 class="text-2xl font-bold text-brand-navy">{{ $orders->count() }}</h3>
                                </div>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition">
                                <div class="w-14 h-14 rounded-full bg-green-50 flex items-center justify-center text-green-500 text-2xl shrink-0">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Delivered</p>
                                    <h3 class="text-2xl font-bold text-brand-navy">{{ $orders->where('status', 'delivered')->count() }}</h3>
                                </div>
                            </div>
                            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5 hover:shadow-md transition">
                                <div class="w-14 h-14 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-500 text-2xl shrink-0">
                                    <i class="fa-solid fa-spinner"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Processing</p>
                                    <h3 class="text-2xl font-bold text-brand-navy">{{ $orders->whereIn('status', ['pending', 'processing'])->count() }}</h3>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Orders Preview -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                                <h3 class="text-xl font-bold text-brand-navy">Recent Orders</h3>
                                <button onclick="switchTab('orders')" class="text-brand-orange hover:text-brand-navy text-sm font-medium transition flex items-center gap-1">
                                    View All <i class="fa-solid fa-angle-right"></i>
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-white text-gray-500 text-sm border-b border-gray-100">
                                            <th class="py-4 px-6 font-medium">Order ID</th>
                                            <th class="py-4 px-6 font-medium">Date</th>
                                            <th class="py-4 px-6 font-medium">Total</th>
                                            <th class="py-4 px-6 font-medium">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders->take(3) as $order)
                                            <tr class="border-b border-gray-50 hover:bg-brand-bg/50 transition">
                                                <td class="py-4 px-6 font-semibold text-brand-navy">#{{ $order->order_number }}</td>
                                                <td class="py-4 px-6 text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                                                <td class="py-4 px-6 font-medium">৳{{ number_format($order->total, 2) }}</td>
                                                <td class="py-4 px-6">
                                                    @php
                                                        $statusClasses = [
                                                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                            'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                            'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                                            'delivered' => 'bg-green-100 text-green-800 border-green-200',
                                                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                                        ];
                                                        $badgeClass = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                                    @endphp
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize border {{ $badgeClass }}">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-12 px-6 text-center text-gray-500">
                                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400 text-2xl">
                                                        <i class="fa-solid fa-box-open"></i>
                                                    </div>
                                                    <p>You haven't placed any orders yet.</p>
                                                    <a href="{{ route('shop') }}" wire:navigate class="mt-4 inline-block bg-brand-navy text-white px-6 py-2 rounded-lg hover:bg-brand-orange transition shadow-sm font-medium">Start Shopping</a>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- ORDERS TAB -->
                    <div id="tab-orders" class="hidden animate-fade-in">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="text-xl font-bold text-brand-navy">Order History</h3>
                                <p class="text-gray-500 text-sm mt-1">View and track all your orders in one place.</p>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-white text-gray-500 text-sm border-b border-gray-100">
                                            <th class="py-4 px-6 font-medium">Order ID</th>
                                            <th class="py-4 px-6 font-medium">Date</th>
                                            <th class="py-4 px-6 font-medium">Total</th>
                                            <th class="py-4 px-6 font-medium">Status</th>
                                            <th class="py-4 px-6 font-medium text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($orders as $order)
                                            <tr class="border-b border-gray-50 hover:bg-brand-bg/50 transition group">
                                                <td class="py-4 px-6 font-semibold text-brand-navy">#{{ $order->order_number }}</td>
                                                <td class="py-4 px-6 text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                                                <td class="py-4 px-6 font-medium text-brand-navy">৳{{ number_format($order->total, 2) }}</td>
                                                <td class="py-4 px-6">
                                                    @php
                                                        $badgeClass = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                                    @endphp
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize border {{ $badgeClass }}">
                                                        {{ $order->status }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6 text-right">
                                                    <a href="{{ route('track') }}?order_number={{ $order->order_number }}" wire:navigate class="inline-flex items-center justify-center bg-white border border-brand-orange/50 text-brand-orange hover:bg-brand-orange hover:text-white px-4 py-1.5 rounded-lg text-sm font-medium transition shadow-sm group-hover:shadow">
                                                        Track
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-16 px-6 text-center text-gray-500">
                                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300 text-4xl">
                                                        <i class="fa-solid fa-box-open"></i>
                                                    </div>
                                                    <p class="text-lg font-medium text-brand-navy mb-1">No Orders Found</p>
                                                    <p class="mb-5">Looks like you haven't made your first purchase yet.</p>
                                                    <a href="{{ route('shop') }}" wire:navigate class="inline-block bg-brand-orange text-white px-8 py-3 rounded-xl hover:bg-brand-navy transition shadow-md font-medium">Explore Products</a>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- SETTINGS TAB -->
                    <div id="tab-settings" class="hidden animate-fade-in">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="text-xl font-bold text-brand-navy">Profile Settings</h3>
                                <p class="text-gray-500 text-sm mt-1">Update your personal information and password.</p>
                            </div>
                            
                            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
                                @csrf
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Personal Info -->
                                    <div class="space-y-6">
                                        <h4 class="text-lg font-semibold text-brand-navy flex items-center gap-2">
                                            <i class="fa-regular fa-id-card text-brand-orange"></i> Personal Details
                                        </h4>
                                        
                                        <div class="space-y-2">
                                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border-gray-200 focus:border-brand-orange focus:ring-brand-orange bg-gray-50 focus:bg-white transition-colors shadow-sm py-2.5 px-4" required>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address <span class="text-xs text-gray-400 font-normal ml-1">(Cannot be changed)</span></label>
                                            <input type="email" id="email" value="{{ $user->email }}" class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed shadow-sm py-2.5 px-4" readonly>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">Profile Picture</label>
                                            <div class="mt-1 flex items-center gap-4">
                                                <div class="w-16 h-16 rounded-full bg-gray-100 border border-gray-200 overflow-hidden shrink-0">
                                                    @if($user->avatar)
                                                        <img src="{{ Storage::url($user->avatar) }}" alt="Preview" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-2xl">
                                                            <i class="fa-regular fa-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <input type="file" id="avatar" name="avatar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand-orange/10 file:text-brand-orange hover:file:bg-brand-orange/20 cursor-pointer transition">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Security -->
                                    <div class="space-y-6">
                                        <h4 class="text-lg font-semibold text-brand-navy flex items-center gap-2">
                                            <i class="fa-solid fa-lock text-brand-orange"></i> Security
                                        </h4>
                                        <p class="text-sm text-gray-500 leading-relaxed bg-brand-bg/50 p-3 rounded-lg border border-brand-navy/5">Leave the password fields blank if you don't want to change your current password.</p>
                                        
                                        <div class="space-y-2">
                                            <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                            <input type="password" id="password" name="password" class="w-full rounded-xl border-gray-200 focus:border-brand-orange focus:ring-brand-orange bg-gray-50 focus:bg-white transition-colors shadow-sm py-2.5 px-4">
                                        </div>
                                        <div class="space-y-2">
                                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full rounded-xl border-gray-200 focus:border-brand-orange focus:ring-brand-orange bg-gray-50 focus:bg-white transition-colors shadow-sm py-2.5 px-4">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-10 pt-6 border-t border-gray-100 flex justify-end">
                                    <button type="submit" class="bg-brand-navy hover:bg-brand-orange text-white font-semibold py-3 px-8 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex items-center gap-2">
                                        <i class="fa-solid fa-cloud-arrow-up"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        function switchTab(tabId) {
            document.getElementById('tab-dashboard').classList.add('hidden');
            document.getElementById('tab-orders').classList.add('hidden');
            document.getElementById('tab-settings').classList.add('hidden');
            
            document.getElementById('tab-' + tabId).classList.remove('hidden');

            const inactiveClasses = 'w-full flex items-center gap-3 px-4 py-3 text-left rounded-xl transition-all duration-200 text-brand-navy hover:bg-brand-bg hover:text-brand-orange font-medium'.split(' ');
            const activeClasses = 'w-full flex items-center gap-3 px-4 py-3 text-left rounded-xl transition-all duration-200 bg-brand-orange text-white font-medium shadow-md shadow-brand-orange/20'.split(' ');

            ['dashboard', 'orders', 'settings'].forEach(id => {
                const btn = document.getElementById('tab-btn-' + id);
                btn.className = '';
                btn.classList.add(...inactiveClasses);
            });

            const activeBtn = document.getElementById('tab-btn-' + tabId);
            activeBtn.className = '';
            activeBtn.classList.add(...activeClasses);
        }

        // Handle URL hashes for direct tab links
        window.addEventListener('livewire:navigated', () => {
            if(window.location.hash) {
                const tab = window.location.hash.substring(1);
                if(['dashboard', 'orders', 'settings'].includes(tab)) {
                    switchTab(tab);
                }
            }
        });
    </script>
</x-layout>
