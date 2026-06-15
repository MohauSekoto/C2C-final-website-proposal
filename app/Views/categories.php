<!-- app/Views/categories.php -->
<div class="bg-white dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-semibold tracking-tighter text-slate-900 dark:text-white transition-colors duration-300">Shop by Category</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-3 text-lg transition-colors duration-300">Discover everything KasiBuy has to offer.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        $uiProps = [
            'Fashion & Accessories' => ['icon' => '👗', 'color' => 'bg-pink-50 dark:bg-pink-900/20 text-pink-600 dark:text-pink-400', 'border' => 'hover:border-pink-300 dark:hover:border-pink-600'],
            'Home & Appliances' => ['icon' => '🛋️', 'color' => 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400', 'border' => 'hover:border-emerald-300 dark:hover:border-emerald-600'],
            'Electronics' => ['icon' => '📱', 'color' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400', 'border' => 'hover:border-blue-300 dark:hover:border-blue-600'],
            'Arts & Crafts' => ['icon' => '🎨', 'color' => 'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400', 'border' => 'hover:border-purple-300 dark:hover:border-purple-600'],
            'Books' => ['icon' => '📚', 'color' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400', 'border' => 'hover:border-amber-300 dark:hover:border-amber-600'],
            'Health & Beauty' => ['icon' => '✨', 'color' => 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400', 'border' => 'hover:border-rose-300 dark:hover:border-rose-600']
        ];

        foreach($categories as $cat): 
            $props = $uiProps[$cat['name']] ?? ['icon' => '🛍️', 'color' => 'bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300', 'border' => 'hover:border-slate-300 dark:hover:border-slate-600'];
        ?>
            <a href="/products?category[]=<?= urlencode($cat['name']) ?>" class="group block p-8 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 <?= $props['border'] ?> hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="w-20 h-20 mx-auto rounded-full <?= $props['color'] ?> flex items-center justify-center text-4xl mb-6 group-hover:scale-110 transition-transform duration-300">
                    <span aria-hidden="true"><?= $props['icon'] ?></span>
                </div>
                <h3 class="text-lg font-semibold tracking-tight text-slate-900 dark:text-white transition-colors duration-300"><?= htmlspecialchars($cat['name']) ?></h3>
            </a>
        <?php endforeach; ?>
    </div>
</div>
