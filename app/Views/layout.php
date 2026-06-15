<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'KasiBuy' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0055ff', /* Electric Cobalt */
                        secondary: '#eab308',
                        dark: '#09090b', /* Zinc 950 */
                        light: '#ffffff',
                        darkBg: '#09090b',
                        darkCard: '#18181b' /* Zinc 900 */
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-light dark:bg-darkBg text-dark dark:text-gray-100 font-sans antialiased flex flex-col min-h-screen transition-colors duration-300">

    <!-- Navigation Bar -->
    <nav class="bg-white dark:bg-darkCard shadow-sm sticky top-0 z-50 transition-colors duration-300" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold"><span class="text-slate-900 dark:text-white transition-colors duration-300">Kasi</span><span class="text-yellow-500">Buy</span></a>
                </div>
                
                <!-- Desktop Menu & Dark Mode Toggle -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center sm:space-x-8">
                    <a href="/" class="text-gray-900 dark:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 border-primary text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">Home</a>
                    <a href="/products" class="text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 border-transparent hover:border-gray-300 dark:hover:border-gray-600 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">Shop</a>
                    <?php $isProductsPage = strpos($_SERVER['REQUEST_URI'], '/products') === 0; ?>
                    <?php if ($isProductsPage): ?>
                        <form action="/products" method="GET" class="relative hidden lg:block">
                            <!-- Preserve existing query parameters if needed -->
                            <?php if(!empty($_GET['category'])): foreach((array)$_GET['category'] as $cat): ?>
                                <input type="hidden" name="category[]" value="<?= htmlspecialchars($cat) ?>">
                            <?php endforeach; endif; ?>
                            <?php if(!empty($_GET['min_price'])): ?> <input type="hidden" name="min_price" value="<?= htmlspecialchars($_GET['min_price']) ?>"> <?php endif; ?>
                            <?php if(!empty($_GET['max_price'])): ?> <input type="hidden" name="max_price" value="<?= htmlspecialchars($_GET['max_price']) ?>"> <?php endif; ?>
                            <?php if(!empty($_GET['sort'])): ?> <input type="hidden" name="sort" value="<?= htmlspecialchars($_GET['sort']) ?>"> <?php endif; ?>
                            
                            <input type="search" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Search products..." class="bg-gray-100 dark:bg-zinc-800 text-sm rounded-full pl-10 pr-4 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary transition-all w-48 focus:w-64 border border-transparent dark:border-zinc-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="ph ph-magnifying-glass text-lg"></i>
                            </div>
                        </form>
                    <?php else: ?>
                        <a href="/products" title="Search Products" class="text-gray-500 dark:text-gray-400 hover:text-primary transition-colors p-2 focus:outline-none focus:ring-2 focus:ring-primary rounded-full flex items-center hover:bg-gray-100 dark:hover:bg-zinc-800" aria-label="Search Products">
                            <i class="ph ph-magnifying-glass text-xl"></i>
                        </a>
                    <?php endif; ?>
                    <?php 
                    $cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
                    $sellRoute = '/register-store';
                    if (isset($_SESSION['role'])) {
                        if (in_array($_SESSION['role'], ['seller', 'admin'])) $sellRoute = '/dashboard';
                    }
                    ?>
                    <a href="/cart" class="relative text-gray-500 dark:text-gray-400 hover:text-primary transition-colors p-2 focus:outline-none focus:ring-2 focus:ring-primary rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800" aria-label="Cart">
                        <i class="ph ph-shopping-cart text-xl"></i>
                        <?php if($cartCount > 0): ?>
                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="/profile" class="text-gray-500 dark:text-gray-400 hover:text-primary transition-colors p-2 focus:outline-none focus:ring-2 focus:ring-primary rounded-full hover:bg-gray-100 dark:hover:bg-zinc-800" aria-label="User Profile">
                            <i class="ph ph-user text-xl"></i>
                        </a>
                    <?php else: ?>
                        <a href="/login" class="text-primary dark:text-blue-400 font-semibold hover:text-blue-700 dark:hover:text-blue-300 text-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-2 py-1">Login</a>
                    <?php endif; ?>
                    
                    <a href="<?= $sellRoute ?>" class="bg-primary text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-600 shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-darkCard">Sell with us</a>
                    
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="/admin/dashboard" class="bg-gray-900 dark:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-800 dark:hover:bg-gray-600 shadow-sm transition-all duration-200">Admin Panel</a>
                    <?php endif; ?>
                    
                    <!-- Dark Mode Toggle Button -->
                    <button @click="darkMode = !darkMode" class="p-2 rounded-full text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors active:scale-95" aria-label="Toggle Dark Mode">
                        <!-- Sun icon for dark mode -->
                        <i x-show="darkMode" class="ph ph-sun text-xl" style="display: none;"></i>
                        <!-- Moon icon for light mode -->
                        <i x-show="!darkMode" class="ph ph-moon text-xl"></i>
                    </button>
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" aria-controls="mobile-menu" :aria-expanded="mobileMenuOpen.toString()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200 active:scale-95">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" x-show="mobileMenuOpen" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="sm:hidden overflow-hidden" 
             x-show="mobileMenuOpen" 
             x-transition:enter="transition-all ease-out duration-300"
             x-transition:enter-start="opacity-0 max-h-0"
             x-transition:enter-end="opacity-100 max-h-64"
             x-transition:leave="transition-all ease-in duration-200"
             x-transition:leave-start="opacity-100 max-h-64"
             x-transition:leave-end="opacity-0 max-h-0"
             x-cloak style="display: none;">
            <div class="pt-2 pb-3 space-y-1">
                <a href="/" class="bg-blue-50 dark:bg-blue-900/30 border-primary text-primary dark:text-blue-400 block pl-3 pr-4 py-2 border-l-4 text-base font-medium">Home</a>
                <a href="/products" class="border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors">Shop</a>
                <a href="/cart" class="border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors">Cart (<?= $cartCount ?>)</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="/profile" class="border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors">Profile</a>
                <?php else: ?>
                    <a href="/login" class="border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors">Login</a>
                <?php endif; ?>
                <a href="<?= $sellRoute ?>" class="border-transparent text-primary dark:text-blue-400 font-bold hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 block pl-3 pr-4 py-2 border-l-4 text-base transition-colors">Sell with us</a>
                <button @click="darkMode = !darkMode" class="w-full text-left border-transparent text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-200 block pl-3 pr-4 py-2 border-l-4 text-base font-medium transition-colors focus:outline-none">
                    <span x-show="!darkMode">🌙 Dark Mode</span>
                    <span x-show="darkMode" style="display: none;">☀️ Light Mode</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        <?php require_once $view; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4"><span class="text-white">Kasi</span><span class="text-yellow-500">Buy</span></h3>
                    <p class="text-gray-400 text-sm">Empowering local South African entrepreneurs by connecting them with buyers nationwide.</p>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Shop</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/products" class="hover:text-white hover:translate-x-1 inline-block transition-all duration-200">All Products</a></li>
                        <li><a href="/products" class="hover:text-white hover:translate-x-1 inline-block transition-all duration-200">Categories</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Sell</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="<?= $sellRoute ?>" class="hover:text-white hover:translate-x-1 inline-block transition-all duration-200">Seller Dashboard</a></li>
                        <li><a href="/guidelines" class="hover:text-white hover:translate-x-1 inline-block transition-all duration-200">Seller Guidelines</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="/contact" class="hover:text-white hover:translate-x-1 inline-block transition-all duration-200">Contact Us</a></li>
                        <li><a href="/terms" class="hover:text-white hover:translate-x-1 inline-block transition-all duration-200">Terms & Conditions</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                &copy; <?= date('Y') ?> KasiBuy. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Global Notifications -->
    <?php if(isset($_SESSION['success'])): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed bottom-4 right-4 z-50">
        <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-xl flex items-center gap-3 border border-green-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium"><?= htmlspecialchars($_SESSION['success']) ?></span>
            <button @click="show = false" class="ml-4 text-green-200 hover:text-white focus:outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
    <?php unset($_SESSION['success']); endif; ?>

</body>
</html>
