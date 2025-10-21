<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary - Coffee Shop</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom colors matching the dark theme */
        :root {
            --primary-orange: #ff9933;
            --dark-bg: #1e1e1e;
            --dark-card: #2a2a2a;
            --text-light: #f3f4f6;
            --text-faded: #9ca3af;
        }

        /* Configure Tailwind to use custom colors */
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-orange': 'var(--primary-orange)',
                        'dark-bg': 'var(--dark-bg)',
                        'dark-card': 'var(--dark-card)',
                        'text-faded': 'var(--text-faded)',
                    }
                }
            }
        }
        
        /* Base styles */
        body {
            background-color: var(--dark-bg);
            color: var(--text-light);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* Custom radio button styling to match the design */
        .custom-radio-group input[type="radio"] {
            /* Hide the default radio button */
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            position: absolute;
        }

        .custom-radio-group label {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.2s;
            border-radius: 9999px; /* Fully rounded */
        }

        .radio-dot {
            width: 14px;
            height: 14px;
            border: 2px solid var(--text-faded);
            border-radius: 50%;
            margin-right: 8px;
            transition: border-color 0.2s, background-color 0.2s;
            position: relative;
        }

        .radio-dot::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: transparent;
            transition: background-color 0.2s;
        }

        /* Checked state for custom radios */
        .custom-radio-group input[type="radio"]:checked + label .radio-dot {
            border-color: var(--primary-orange);
        }

        .custom-radio-group input[type="radio"]:checked + label .radio-dot::after {
            background-color: var(--primary-orange);
        }

        /* Additional visual detail from the image (orange vertical bar) */
        .sidebar-bar {
            background-color: var(--primary-orange);
            width: 8px;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            border-radius: 0 4px 4px 0;
        }

        .promo-input::placeholder {
            color: var(--text-faded);
        }
    </style>
</head>
<body class="bg-dark-bg min-h-screen">

    <!-- Customer Header -->
    <header class="bg-dark-bg border-b border-gray-700/50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center py-3">
            <div class="flex items-center space-x-8">
                <div class="flex items-center text-xl font-bold text-white">
                    <i class="fas fa-mug-hot text-primary-orange mr-2"></i>
                </div>
                <nav class="hidden md:flex space-x-6 text-sm font-medium">
                    <a href="#" class="text-white hover:text-primary-orange transition duration-150">HOME</a>
                    <a href="#" class="text-white hover:text-primary-orange transition duration-150">MENU</a>
                    <a href="#" class="text-white hover:text-primary-orange transition duration-150">MY ORDERS</a>
                    <a href="#" class="text-white hover:text-primary-orange transition duration-150">PROFILE</a>
                </nav>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-primary-orange text-dark-bg font-bold px-4 py-2 rounded-full text-sm hover:bg-orange-400 transition">LOGOUT</button>
                <div class="w-8 h-8 rounded-full bg-gray-500 overflow-hidden border-2 border-primary-orange">
                    <!-- Placeholder for user avatar -->
                    <img src="https://placehold.co/32x32/d1d5db/374151?text=U" alt="User" onerror="this.src='https://placehold.co/32x32/d1d5db/374151?text=U'">
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- LEFT COLUMN: Order Summary, Delivery, Payment -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Order Summary Header -->
                <div class="flex justify-between items-center border-b border-gray-700 pb-4">
                    <h1 class="text-2xl font-bold text-white tracking-widest">ORDER SUMMARY</h1>
                    <i class="fas fa-shopping-cart text-xl text-text-faded"></i>
                </div>

                <!-- Order Item List -->
                <div class="bg-dark-card p-6 rounded-xl shadow-lg relative overflow-hidden">
                    <div class="sidebar-bar"></div>

                    <!-- Item 1: Featured Image Item -->
                    <div class="flex items-start mb-6">
                        <img src="https://placehold.co/120x150/505050/FFFFFF?text=Iced+Coffee" alt="Iced Caramel Macchiato" class="w-32 h-40 object-cover rounded-lg mr-6">
                        <div>
                            <p class="text-lg font-semibold text-white">Iced Caramel Macchiato</p>
                            <p class="text-text-faded text-sm mb-4">Swirled & Layered</p>
                            <p class="text-lg font-bold text-white">$8.45</p>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="flex justify-between items-center py-3 border-t border-gray-700">
                        <div>
                            <p class="font-medium">Iced Coffee</p>
                            <p class="text-text-faded text-sm">Rich, dark brew.</p>
                        </div>
                        <p class="font-medium">$8.95</p>
                    </div>

                    <!-- Item 3 -->
                    <div class="flex justify-between items-center py-3 border-t border-gray-700">
                        <div>
                            <p class="font-medium">Cinnamon Swirl Pastry</p>
                            <p class="text-text-faded text-sm">Fluffy, Q qushiitit!</p>
                        </div>
                        <p class="font-medium">$8.95</p>
                    </div>

                    <!-- Item 4 -->
                    <div class="flex justify-between items-center py-3 border-t border-gray-700">
                        <div>
                            <p class="font-medium">Iced Caramel Macchiato</p>
                            <p class="text-text-faded text-sm">Brewed, layered and refreshing.</p>
                        </div>
                        <p class="font-medium">$3.95</p>
                    </div>
                </div>

                <!-- Delivery Options -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-white">DELIVERY OPTIONS</h3>
                    <div class="flex custom-radio-group space-x-6">
                        <div class="flex items-center">
                            <input type="radio" id="pickup" name="delivery-option" value="pickup" checked>
                            <label for="pickup" class="text-white">
                                <span class="radio-dot"></span>
                                Pickup
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="delivery" name="delivery-option" value="delivery">
                            <label for="delivery" class="text-white">
                                <span class="radio-dot"></span>
                                Delivery
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Payment Method & Promo Code -->
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-white">PAYMENT METHOD</h3>

                    <!-- Payment Radios -->
                    <div class="flex custom-radio-group space-x-6">
                        <div class="flex items-center">
                            <input type="radio" id="credit" name="payment-method" value="credit">
                            <label for="credit" class="text-white">
                                <span class="radio-dot"></span>
                                Credit/Debit Card
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="gcash" name="payment-method" value="gcash">
                            <label for="gcash" class="text-white">
                                <span class="radio-dot"></span>
                                GCash (E-Wallat)
                            </label>
                        </div>
                    </div>
                    
                    <!-- Promo Code Input -->
                    <div class="mt-6">
                        <input type="text" placeholder="Promo Code" class="promo-input w-full bg-dark-card border border-gray-700 rounded-lg px-4 py-3 text-white placeholder-text-faded focus:outline-none focus:ring-2 focus:ring-primary-orange">
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Order Total -->
            <div class="lg:col-span-1">
                <div class="bg-dark-card p-6 rounded-xl shadow-lg space-y-6">
                    <h3 class="text-xl font-bold text-white border-b border-gray-700 pb-3">ORDER TOTAL</h3>

                    <!-- Pricing Details -->
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-text-faded">
                            <span>Subtotal</span>
                            <span id="subtotal-amount">$0.00</span>
                        </div>
                        <div class="flex justify-between text-text-faded">
                            <span>Delivery Fee</span>
                            <span id="delivery-fee-amount">$0.00</span>
                        </div>
                        <div class="flex justify-between text-text-faded">
                            <span>Tax</span>
                            <span id="tax-amount">$3.45</span>
                        </div>
                        <div class="flex justify-between text-text-faded">
                            <span>Tax</span>
                            <span id="tax-amount-2">$0.00</span>
                        </div>
                    </div>

                    <!-- Final Total -->
                    <div class="border-t border-gray-700 pt-4 space-y-4">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Cash on Pickup</span>
                            <span class="text-primary-orange text-2xl font-extrabold" id="total-amount">$21.75</span>
                        </div>
                        <p class="text-sm text-center text-text-faded tracking-widest">TOTAL AMOUNT</p>

                        <!-- Place Order Button -->
                        <button class="w-full bg-primary-orange text-dark-bg font-extrabold text-lg py-4 rounded-full shadow-lg hover:bg-orange-400 transition duration-150 tracking-wider">
                            PLACE ORDER
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
