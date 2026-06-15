<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches) }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KasiBuy Admin Portal</title>
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
<body class="bg-zinc-50 dark:bg-darkBg text-zinc-900 dark:text-zinc-100 font-sans antialiased min-h-screen flex transition-colors duration-300">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-darkCard min-h-screen border-r border-zinc-200 dark:border-zinc-800 flex flex-col transition-colors duration-300 relative z-20">
        <div class="p-6 border-b border-zinc-100 dark:border-zinc-800/50 flex items-center justify-between">
            <h2 class="text-xl font-display font-bold tracking-tight"><span class="text-zinc-900 dark:text-white">Kasi</span><span class="text-yellow-500">Buy</span> <span class="text-zinc-400 font-normal text-sm ml-1">Admin</span></h2>
        </div>
        
        <nav class="flex-1 p-4 space-y-1">
            <?php 
                $currentUri = $_SERVER['REQUEST_URI'];
                $navItems = [
                    ['path' => '/admin/dashboard', 'icon' => 'ph-squares-four', 'label' => 'Dashboard'],
                    ['path' => '/admin/orders', 'icon' => 'ph-receipt', 'label' => 'Orders'],
                    ['path' => '/admin/products', 'icon' => 'ph-package', 'label' => 'Products'],
                    ['path' => '/admin/users', 'icon' => 'ph-users', 'label' => 'Users'],
                    ['path' => '/admin/database', 'icon' => 'ph-database', 'label' => 'Raw DB Explorer'],
                ];
                
                foreach ($navItems as $item):
                    $isActive = strpos($currentUri, $item['path']) === 0;
                    $activeClass = $isActive 
                        ? 'bg-zinc-100 dark:bg-zinc-800/50 text-zinc-900 dark:text-white font-medium' 
                        : 'text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800/30';
            ?>
                <a href="<?= $item['path'] ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm transition-all <?= $activeClass ?>">
                    <i class="ph <?= $item['icon'] ?> text-lg <?= $isActive ? 'text-primary dark:text-blue-400' : '' ?>"></i>
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>
        
        <div class="p-4 border-t border-zinc-200 dark:border-zinc-800 space-y-1">
            <button @click="darkMode = !darkMode" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-all text-left">
                <i x-show="!darkMode" class="ph ph-moon text-lg"></i>
                <i x-show="darkMode" class="ph ph-sun text-lg" style="display: none;"></i>
                <span x-show="!darkMode">Dark Mode</span>
                <span x-show="darkMode" style="display: none;">Light Mode</span>
            </button>
            <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-all">
                <i class="ph ph-storefront text-lg"></i>
                Storefront
            </a>
            <a href="/logout" class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all">
                <i class="ph ph-sign-out text-lg"></i>
                Secure Logout
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-screen overflow-y-auto bg-zinc-50/50 dark:bg-darkBg">
        <div class="p-8 lg:p-10 max-w-7xl mx-auto w-full">
            <?php require_once $admin_view; ?>
        </div>
    </main>
</body>
</html>
