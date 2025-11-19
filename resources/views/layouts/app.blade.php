<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sales Management System</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="app-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                {{-- <h2>Sales System</h2> --}}
                <img src="{{ asset('images/logo.png') }}" width="100" style="display: block; margin: 0 auto;">
            </div>
            <ul class="sidebar-nav">
                <li><a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Home</a></li>
                <li><a href="{{ route('products.index') }}"
                        class="{{ request()->routeIs('products.index') ? 'active' : '' }}">Products</a></li>
                <li><a href="{{ route('sales.data') }}"
                        class="{{ request()->routeIs('sales.data') ? 'active' : '' }}">Sales Data</a></li>
                <li><a href="{{ route('sales.history') }}"
                        class="{{ request()->routeIs('sales.history') ? 'active' : '' }}">Sales History</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <main class="main-content">
            <!-- Top Bar -->
            <header class="top-bar">
                <div class="top-bar-left">
                    <h1>@yield('title', 'Dashboard')</h1>
                </div>
                <div class="top-bar-right">
                    <div class="cart-icon" id="cartToggle">
                        🛒 <span class="cart-count" id="cartCount">0</span>
                    </div>
                    <span class="user-info">Welcome, {{ Auth::user()->name }}</span>
                </div>
            </header>

            <!-- Content -->
            <div class="content">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Cart Sidebar -->
        <!-- Cart Sidebar -->
        <div class="cart-sidebar" id="cartSidebar">
            <div class="cart-header">
                <h3>Shopping Cart</h3>
                <button class="close-cart" id="closeCart">&times;</button>
            </div>

            <!-- Customer Name Input -->
            <div class="customer-name-section">
                <label for="customerName">Customer Name:</label>
                <input type="text" id="customerName" placeholder="Enter customer name" required>
                <small class="customer-name-error" style="color: red; display: none;">Please enter customer name</small>
            </div>

            <div class="cart-items" id="cartItems">
                <!-- Cart items will be loaded here -->
            </div>
            <div class="cart-footer">
                <button class="confirm-order" id="confirmOrder">Confirm Order</button>
            </div>
        </div>
        <div class="cart-overlay" id="cartOverlay"></div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
    @yield('scripts')
</body>

</html>
