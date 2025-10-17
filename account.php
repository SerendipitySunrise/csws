<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Coffee Shop</title>
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
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
        
        /* Base styles */
        body {
            background-color: var(--dark-bg);
            color: var(--text-light);
            min-height: 100vh;
        }
        
        /* Custom input style for dark theme */
        .custom-input {
            background-color: #3b3b3b;
            border: 1px solid #555;
            color: var(--text-light);
            padding: 10px;
            border-radius: 6px;
        }

        /* Active sidebar link style */
        .sidebar-link.active {
            border-left: 4px solid var(--primary-orange);
            background-color: rgba(255, 153, 51, 0.1); /* Light orange background */
            color: var(--primary-orange);
            font-weight: 600;
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
                    <!-- Active Link -->
                    <a href="#" class="text-primary-orange transition duration-150 border-b-2 border-primary-orange pb-3">PROFILE</a>
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

    <!-- Main Content Area - Profile Layout -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-4xl font-extrabold text-white tracking-wide mb-10">MY PROFILE</h1>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Navigation -->
            <nav class="w-full lg:w-64 bg-dark-card rounded-xl shadow-xl p-4 h-fit">
                <ul class="space-y-1">
                    <li>
                        <a href="#" class="sidebar-link active block p-3 rounded-lg text-text-light hover:bg-gray-700/50 transition">
                            <i class="fas fa-user-circle mr-2 w-5"></i> Account Details
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link block p-3 rounded-lg text-text-light hover:bg-gray-700/50 transition">
                            <i class="fas fa-receipt mr-2 w-5"></i> Order History
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link block p-3 rounded-lg text-text-light hover:bg-gray-700/50 transition">
                            <i class="fas fa-heart mr-2 w-5"></i> Favorite Items
                        </a>
                    </li>
                    <!-- REMOVED: Loyalty & Rewards link -->
                </ul>
            </nav>

            <!-- Profile Content Area -->
            <div class="flex-grow">

                <!-- Account Details Section -->
                <section class="bg-dark-card p-6 rounded-xl shadow-xl mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700/50 pb-2">ACCOUNT DETAILS</h2>
                    
                    <div class="space-y-6">
                        <!-- Full Name -->
                        <div class="flex items-center justify-between">
                            <label class="font-medium text-text-faded w-1/4">Full Name</label>
                            <input type="text" value="Joshua.chen@email.com" readonly class="custom-input flex-grow mr-2"/>
                            <button class="bg-primary-orange text-dark-bg font-bold px-4 py-2 rounded-lg text-sm hover:bg-orange-400 transition">Edit</button>
                        </div>

                        <!-- Email Address -->
                        <div class="flex items-center justify-between">
                            <label class="font-medium text-text-faded w-1/4">Email Address</label>
                            <input type="email" value="Joshua.chen@email.com" readonly class="custom-input flex-grow mr-2"/>
                            <button class="bg-primary-orange text-dark-bg font-bold px-4 py-2 rounded-lg text-sm hover:bg-orange-400 transition">Edit</button>
                        </div>

                        <!-- Password -->
                        <div class="flex items-center justify-between">
                            <label class="font-medium text-text-faded w-1/4">Password</label>
                            <input type="password" value="********" readonly class="custom-input flex-grow mr-2"/>
                            <button class="bg-primary-orange text-dark-bg font-bold px-4 py-2 rounded-lg text-sm hover:bg-orange-400 transition">Edit</button>
                        </div>
                    </div>
                </section>

                <!-- Favorite Items Section -->
                <section class="bg-dark-card p-6 rounded-xl shadow-xl">
                    <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700/50 pb-2">FAVORITE ITEMS</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        
                        <!-- Item 1: Iced Caramel Macchiato -->
                        <div class="bg-gray-800/50 rounded-xl overflow-hidden p-4 flex flex-col items-center">
                            <img src="https://placehold.co/120x120/4a4a4a/f3f4f6?text=Caramel" alt="Iced Caramel Macchiato" class="rounded-lg mb-3">
                            <p class="font-medium text-center">Iced Caramel Macchiato</p>
                            <button class="text-primary-orange text-xl mt-2 hover:text-orange-300 transition"><i class="fas fa-heart"></i></button>
                        </div>

                        <!-- Item 2: Iced Cinnamon Swirl Pastry -->
                        <div class="bg-gray-800/50 rounded-xl overflow-hidden p-4 flex flex-col items-center">
                            <img src="https://placehold.co/120x120/4a4a4a/f3f4f6?text=Swirl" alt="Iced Cinnamon Swirl Pastry" class="rounded-lg mb-3">
                            <p class="font-medium text-center">Iced Cinnamon Swirl Pastry</p>
                            <button class="text-primary-orange text-xl mt-2 hover:text-orange-300 transition"><i class="fas fa-heart"></i></button>
                        </div>

                        <!-- Item 3: Cold Brew -->
                        <div class="bg-gray-800/50 rounded-xl overflow-hidden p-4 flex flex-col items-center">
                            <img src="https://placehold.co/120x120/4a4a4a/f3f4f6?text=Cold+Brew" alt="Cold Brew" class="rounded-lg mb-3">
                            <p class="font-medium text-center">Cold Brew</p>
                            <button class="text-primary-orange text-xl mt-2 hover:text-orange-300 transition"><i class="fas fa-heart"></i></button>
                        </div>

                        <!-- Item 4: Another Item -->
                        <div class="bg-gray-800/50 rounded-xl overflow-hidden p-4 flex flex-col items-center">
                            <img src="https://placehold.co/120x120/4a4a4a/f3f4f6?text=Latte" alt="Latte" class="rounded-lg mb-3">
                            <p class="font-medium text-center">Vanilla Latte</p>
                            <button class="text-primary-orange text-xl mt-2 hover:text-orange-300 transition"><i class="fas fa-heart"></i></button>
                        </div>

                    </div>
                    
                </section>

                <!-- Logout Button -->
                <div class="mt-8 text-center">
                     <button class="bg-primary-orange text-dark-bg font-bold px-8 py-4 rounded-full text-lg shadow-2xl hover:bg-orange-400 transition">
                        LOGOUT
                    </button>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
