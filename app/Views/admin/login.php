<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KasiBuy Admin Login</title>
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
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-zinc-50 min-h-screen flex items-center justify-center font-sans antialiased">
    <div class="bg-white p-10 rounded-2xl shadow-sm border border-zinc-200 w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-zinc-100 rounded-xl mb-4">
                <i class="ph ph-lock-key text-2xl text-zinc-900"></i>
            </div>
            <h1 class="text-2xl font-display font-bold text-zinc-900 tracking-tight">KasiBuy Admin</h1>
            <p class="text-sm text-zinc-500 mt-1">Authenticate to access the control panel</p>
        </div>
        
        <?php if(isset($error) && $error): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 p-3.5 rounded-lg mb-6 text-sm flex items-center gap-2">
                <i class="ph ph-warning-circle text-lg"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/admin/login" method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <div>
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Admin Email</label>
                <input type="email" name="email" required class="w-full bg-zinc-50 border border-zinc-200 text-zinc-900 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-zinc-400" placeholder="admin@kasibuy.co.za">
            </div>
            
            <div x-data="{ show: false }">
                <label class="block text-sm font-medium text-zinc-700 mb-1.5">Security Key</label>
                <div class="relative">
                    <input name="password" :type="show ? 'text' : 'password'" required class="w-full bg-zinc-50 border border-zinc-200 text-zinc-900 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-zinc-400 pr-12" placeholder="••••••••">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-zinc-600 transition-colors">
                        <i class="ph" :class="show ? 'ph-eye-slash' : 'ph-eye'" style="font-size: 1.125rem;"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-zinc-900 text-white font-medium py-2.5 rounded-lg hover:bg-zinc-800 transition-all shadow-sm mt-2">
                Sign In
            </button>
        </form>

        <div class="mt-8 text-center">
            <a href="/" class="text-sm text-zinc-500 hover:text-zinc-900 transition-colors inline-flex items-center gap-1.5">
                <i class="ph ph-arrow-left"></i> Return to storefront
            </a>
        </div>
    </div>
</body>
</html>
