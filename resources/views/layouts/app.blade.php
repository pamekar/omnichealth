<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OMNIC Medical Store')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @livewireStyles
    @stack('seo')
</head>
<body class="bg-gray-50">

    <header class="main-header">
        <nav class="main-nav">
            <a href="/" class="logo">OMNIC</a>
            <ul class="nav-links">
                <li><a href="/" wire:navigate>Home</a></li>
                <li><a href="{{ route('shop.index') }}" wire:navigate>Shop</a></li>
                <li><a href="{{ route('shop.about') }}" wire:navigate>About</a></li>
            </ul>
            <a href="{{ route('cart.list') }}" wire:navigate class="cart-link">
                <i class="fas fa-shopping-cart"></i>
                @livewire('cart-count')
            </a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Toast Notification Container -->
    <div x-data="{ 
            notifications: [], 
            add(message, type = 'success') {
                const id = Date.now();
                this.notifications.push({ id, message, type });
                setTimeout(() => {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 3000);
            } 
        }" 
        @notify.window="add($event.detail.message, $event.detail.type)"
        class="fixed top-4 right-4 z-50 flex flex-col gap-2 pointer-events-none"
        style="max-width: 300px;">
        <template x-for="n in notifications" :key="n.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-x-8"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform translate-x-8"
                 :class="{
                    'bg-green-600': n.type === 'success',
                    'bg-red-600': n.type === 'error',
                    'bg-blue-600': n.type === 'info'
                 }"
                 class="pointer-events-auto px-4 py-3 rounded-lg shadow-lg text-white flex items-center justify-between min-w-[200px]">
                <span x-text="n.message" class="text-sm font-medium"></span>
                <button @click="notifications = notifications.filter(notif => notif.id !== n.id)" class="ml-4 text-white opacity-70 hover:opacity-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </template>
    </div>

    <footer class="main-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h4>About OMNIC</h4>
                <p>Your trusted partner for high-quality medical supplies and equipment. We are dedicated to improving healthcare access and quality.</p>
            </div>
            <div class="footer-section">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li><a href="{{ route('shop.about') }}">About Us</a></li>
                    <li><a href="{{ route('shop.privacy') }}">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact Us</h4>
                @php
                    $company = \App\Models\Company::find(config('filament-ecommerce.default_company_id'));
                @endphp
                <p><i class="fas fa-map-marker-alt"></i> {{ $company?->address ?? '123 Health St, Medtown, USA' }}</p>
                <p><i class="fas fa-phone"></i> {{ $company?->phone ?? '+1 (555) 123-4567' }}</p>
                <p><i class="fas fa-envelope"></i> {{ $company?->email ?? 'support@omnic.com' }}</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} OMNIC Medical Store. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 50,
        });
    </script>
    @livewireScripts
</body>
</html>
