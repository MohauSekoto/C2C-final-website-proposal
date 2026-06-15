<!-- app/Views/home.php -->
<div class="bg-light dark:bg-darkBg text-dark dark:text-gray-100 transition-colors duration-500 selection:bg-primary selection:text-white pb-32">
    <!-- Hero Section: Asymmetric, Stark, Display Typography -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-32 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center" x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)">
            <div class="lg:col-span-7">
                <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-medium tracking-tighter leading-[1.05] text-balance mb-8 transform transition-all duration-1000 ease-[cubic-bezier(0.16,1,0.3,1)]"
                    :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    Support Local.<br>
                    <span class="text-zinc-400 dark:text-zinc-600">Shop KasiBuy.</span>
                </h1>
                <p class="text-lg md:text-xl text-zinc-600 dark:text-zinc-400 max-w-lg leading-relaxed transform transition-all duration-1000 delay-100 ease-[cubic-bezier(0.16,1,0.3,1)]"
                   :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    Discover unique products from talented South African entrepreneurs. Delivered safely to your door with built-in escrow protection.
                </p>
                <div class="mt-10 flex flex-wrap gap-4 items-center transform transition-all duration-1000 delay-200 ease-[cubic-bezier(0.16,1,0.3,1)]"
                     :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                    <a href="/products" class="bg-primary text-white font-medium text-base px-8 py-4 rounded hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-primary/25 active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-darkBg inline-flex items-center gap-2">
                        Start Shopping <i class="ph ph-arrow-right"></i>
                    </a>
                    <a href="/register" class="bg-transparent text-dark dark:text-white font-medium text-base px-8 py-4 rounded hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-all duration-300 border border-zinc-200 dark:border-zinc-800 active:scale-95 focus:outline-none focus:ring-2 focus:ring-zinc-400 focus:ring-offset-2 dark:focus:ring-offset-darkBg">
                        Become a Seller
                    </a>
                </div>
            </div>
            <div class="lg:col-span-5 hidden lg:block transform transition-all duration-1000 delay-300 ease-[cubic-bezier(0.16,1,0.3,1)]"
                 :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
                <!-- Abstract Premium Graphic replacing gradient blob -->
                <div class="aspect-[4/5] rounded bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="ph ph-shopping-bag text-9xl text-zinc-300 dark:text-zinc-800 group-hover:scale-105 transition-transform duration-700 ease-[cubic-bezier(0.16,1,0.3,1)]"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Categories: Phosphor Icons, Monochrome Cards -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12">
            <h2 class="font-display text-3xl font-medium tracking-tight text-dark dark:text-white">Featured Categories</h2>
            <a href="/products" class="text-sm font-medium text-zinc-500 dark:text-zinc-400 hover:text-primary transition-colors mt-4 sm:mt-0 inline-flex items-center gap-1 group">
                Explore all <i class="ph ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-6">
            <?php
            $categories = [
                ['name' => 'Fashion', 'icon' => 'ph-t-shirt'],
                ['name' => 'Home', 'icon' => 'ph-armchair'],
                ['name' => 'Electronics', 'icon' => 'ph-devices'],
                ['name' => 'Crafts', 'icon' => 'ph-palette'],
                ['name' => 'Books', 'icon' => 'ph-books'],
                ['name' => 'Beauty', 'icon' => 'ph-sparkle']
            ];
            
            foreach($categories as $cat): ?>
                <a href="/products?category[]=<?= urlencode($cat['name']) ?>" class="group block p-6 rounded bg-light dark:bg-darkCard border border-zinc-200 dark:border-zinc-800 hover:border-primary dark:hover:border-primary transition-all duration-300 text-center hover:-translate-y-1 hover:shadow-xl hover:shadow-primary/5 active:scale-95">
                    <div class="w-12 h-12 mx-auto rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 flex items-center justify-center text-2xl mb-4 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <i class="ph <?= $cat['icon'] ?>"></i>
                    </div>
                    <h3 class="font-medium tracking-tight text-sm text-dark dark:text-white"><?= $cat['name'] ?></h3>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Trending Products -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 border-t border-zinc-200 dark:border-zinc-800">
        <div class="mb-12">
            <h2 class="font-display text-3xl font-medium tracking-tight text-dark dark:text-white">Trending Now</h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            <?php foreach($trendingProducts as $product): ?>
            <article class="group relative flex flex-col h-full bg-transparent">
                <a href="/product/<?= $product['id'] ?>" class="block aspect-[4/5] bg-zinc-100 dark:bg-zinc-900 rounded overflow-hidden mb-4 border border-zinc-200 dark:border-zinc-800 relative">
                    <?php if (!empty($product['image_url'])): ?>
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="object-cover w-full h-full transition-transform duration-700 ease-[cubic-bezier(0.16,1,0.3,1)] group-hover:scale-105">
                    <?php else: ?>
                        <div class="absolute inset-0 flex items-center justify-center text-zinc-400">
                            <i class="ph ph-image text-4xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Quick Add Button -->
                    <div class="absolute bottom-4 right-4 z-10 opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                        <form action="/cart/add" method="POST" @click.stop>
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="title" value="<?= htmlspecialchars($product['title']) ?>">
                            <input type="hidden" name="price" value="<?= $product['price'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" aria-label="Add to cart" class="w-10 h-10 bg-white dark:bg-zinc-800 text-dark dark:text-white rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white dark:hover:bg-primary transition-colors duration-200 active:scale-90">
                                <i class="ph ph-plus text-lg"></i>
                            </button>
                        </form>
                    </div>
                </a>
                
                <div class="flex flex-col flex-grow">
                    <div class="flex justify-between items-start gap-4 mb-2">
                        <h3 class="font-medium text-dark dark:text-white line-clamp-1 group-hover:text-primary transition-colors">
                            <a href="/product/<?= $product['id'] ?>" class="focus:outline-none before:absolute before:inset-0"><?= htmlspecialchars($product['title']) ?></a>
                        </h3>
                        <div class="font-medium whitespace-nowrap">R <?= number_format($product['price'], 2) ?></div>
                    </div>
                    <div class="text-sm text-zinc-500 dark:text-zinc-400 capitalize">
                        <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>
