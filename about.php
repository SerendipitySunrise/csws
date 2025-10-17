<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Coffee Shop</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Load Font Awesome for icons (used in the header) -->
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
                        /* Using a classic font for the titles as suggested by the image, while keeping Inter for body text */
                        'serif-display': ['Georgia', 'serif'], 
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

        /* Custom styling for the main content card, giving it a classic, elegant feel */
        .about-card {
            background-color: var(--dark-card);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            padding: 40px;
            border-radius: 12px;
        }

        .creator-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--primary-orange);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s;
        }
        
        .creator-image:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-dark-bg min-h-screen">

    <!-- Customer Header (Reusing existing style) -->
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
                    <!-- Assuming an "About" link would be added here in a final design -->
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
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <!-- About Us Card -->
        <div class="about-card">
            
            <!-- About Us Header -->
            <header class="mb-10 pt-4">
                <h1 class="text-6xl font-serif-display font-bold text-white mb-4">About Us</h1>
                <p class="text-text-faded text-lg border-b border-gray-600 pb-6">
                    The quiet intimacy of roasting the beans and serving the first cup to the customer is truly the core of what we're all about.
                </p>
            </header>

            <!-- Our Story Section -->
            <section class="mb-12">
                <h2 class="text-3xl font-serif-display font-bold text-white mb-6">Our Story</h2>
                <div class="text-text-light space-y-4 text-base">
                    <p>
                        A startup coffee roaster to finally mix quality and dedication is how the idea first began. We wanted to bring the neighborhood back to the coffee shop experience.
                    </p>
                    <p class="border-b border-gray-600 pb-6">
                        The past six months have been a want to transform the old routine into life in the sip. You see, all pouring modes really have you waiting for your perfect shot of espresso.
                    </p>
                </div>
            </section>

            <!-- Meet the Creators Section -->
            <section>
                <h2 class="text-3xl font-serif-display font-bold text-white mb-10">Meet the Creators</h2>
                
                <div class="flex flex-col sm:flex-row justify-around items-center text-center space-y-8 sm:space-y-0 sm:space-x-4">
                    
                    <!-- Creator 1: Li Hua -->
                    <div class="flex flex-col items-center max-w-[150px]">
                        <img src="https://placehold.co/120x120/ff9933/1e1e1e?text=LH" alt="Li Hua" class="creator-image mb-3">
                        <p class="text-lg font-semibold text-white">Li Hua</p>
                        <p class="text-sm text-text-faded">Co-Founder & Tech Lead</p>
                    </div>

                    <!-- Creator 2: Ben Carter -->
                    <div class="flex flex-col items-center max-w-[150px]">
                        <img src="https://placehold.co/120x120/ff9933/1e1e1e?text=BC" alt="Ben Carter" class="creator-image mb-3">
                        <p class="text-lg font-semibold text-white">Ben Carter</p>
                        <p class="text-sm text-text-faded">Co-Founder & Head Barista</p>
                    </div>

                    <!-- Creator 3: Sofia Rossi -->
                    <div class="flex flex-col items-center max-w-[150px]">
                        <img src="https://placehold.co/120x120/ff9933/1e1e1e?text=SR" alt="Sofia Rossi" class="creator-image mb-3">
                        <p class="text-lg font-semibold text-white">Sofia Rossi</p>
                        <p class="text-sm text-text-faded">Creative Director & Baker</p>
                    </div>
                </div>
            </section>

        </div>
    </main>

</body>
</html>
