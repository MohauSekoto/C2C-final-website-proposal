<!-- app/Views/products.php -->
<div class="bg-white dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-semibold tracking-tighter text-slate-900 dark:text-white transition-colors duration-300">Shop All Products</h1>
        <p class="text-slate-500 dark:text-slate-400 mt-3 text-lg transition-colors duration-300">Browse unique items from local entrepreneurs across South Africa.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16" x-data="{ filterOpen: false }">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Sidebar Filters -->
        <aside class="w-full lg:w-64 flex-shrink-0">
            <div class="lg:hidden mb-6">
                <button @click="filterOpen = !filterOpen" aria-controls="filter-menu" :aria-expanded="filterOpen.toString()" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-slate-900 dark:text-white font-medium py-3 px-4 rounded-xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] dark:shadow-none flex justify-between items-center active:scale-[0.98] transition-all duration-200 ease-out focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span>Filters</span>
                    <svg class="w-5 h-5 text-slate-400 dark:text-slate-500 transform transition-transform duration-300" :class="filterOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
            </div>
            
            <form id="filter-form" method="GET" action="/products" x-show="filterOpen || window.innerWidth >= 1024" 
                 x-transition:enter="transition-all ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="space-y-10 hidden lg:block" :class="filterOpen ? '!block' : ''">
                 
                 <?php if(!empty($_GET['search'])): ?>
                     <input type="hidden" name="search" value="<?= htmlspecialchars($_GET['search']) ?>">
                 <?php endif; ?>
                <div>
                    <h3 class="text-sm font-medium tracking-wide text-slate-900 dark:text-white uppercase mb-4 transition-colors duration-300">Categories</h3>
                    <ul class="space-y-3">
                        <?php foreach($allCategories as $cat): ?>
                        <li>
                            <label class="flex items-center group cursor-pointer">
                                <input type="checkbox" name="category[]" value="<?= htmlspecialchars($cat['name']) ?>" <?= in_array($cat['name'], $filters['categories'] ?? []) ? 'checked' : '' ?> class="rounded border-slate-300 dark:border-slate-600 dark:bg-slate-800 text-blue-600 focus:ring-blue-500 h-4 w-4 transition-colors">
                                <span class="ml-3 text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors"><?= htmlspecialchars($cat['name']) ?></span>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-sm font-medium tracking-wide text-slate-900 dark:text-white uppercase mb-4 transition-colors duration-300">Price Range</h3>
                    <div class="flex items-center space-x-3">
                        <input type="number" name="min_price" value="<?= htmlspecialchars($filters['min_price'] ?? '') ?>" placeholder="Min" aria-label="Minimum price" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-1 focus:ring-slate-900 dark:focus:ring-white focus:border-slate-900 dark:focus:border-white transition-all outline-none">
                        <span class="text-slate-400 dark:text-slate-500 transition-colors">-</span>
                        <input type="number" name="max_price" value="<?= htmlspecialchars($filters['max_price'] ?? '') ?>" placeholder="Max" aria-label="Maximum price" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:ring-1 focus:ring-slate-900 dark:focus:ring-white focus:border-slate-900 dark:focus:border-white transition-all outline-none">
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-slate-900 dark:bg-blue-600 text-white rounded-lg font-medium py-3 hover:bg-slate-800 dark:hover:bg-blue-700 transition-colors active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">Apply Filters</button>
            </form>
        </aside>
        
        <!-- Product Grid -->
        <main class="flex-grow">
            <!-- Sort Bar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div class="text-slate-500 dark:text-slate-400 text-sm font-medium transition-colors duration-300">Showing <?= count($products) ?> results</div>
                <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()" aria-label="Sort products" class="bg-transparent border border-slate-200 dark:border-slate-700 rounded-lg text-sm p-2.5 pr-8 focus:ring-1 focus:ring-slate-900 dark:focus:ring-white focus:border-slate-900 dark:focus:border-white outline-none text-slate-700 dark:text-slate-300 dark:bg-slate-900 cursor-pointer transition-colors duration-300">
                    <option value="newest" <?= ($filters['sort'] ?? '') === 'newest' ? 'selected' : '' ?>>Sort by: Newest</option>
                    <option value="price_asc" <?= ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>Sort by: Price (Low to High)</option>
                    <option value="price_desc" <?= ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>Sort by: Price (High to Low)</option>
                </select>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8" x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)">
                <!-- Product Card (Dynamic data) -->
                <?php if (empty($products)): ?>
                    <div class="col-span-full py-12 text-center text-slate-500">No products found.</div>
                <?php else: ?>
                    <?php foreach($products as $i => $product): ?>
                    <div class="transform transition-all duration-700 ease-out opacity-0 translate-y-8" 
                         :class="mounted ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" 
                         style="transition-delay: <?= ($i % 9) * 75 ?>ms;">
                        <article class="group bg-white dark:bg-slate-900 rounded-md border border-slate-200 dark:border-slate-700 overflow-hidden hover:shadow-md hover:border-slate-300 dark:hover:border-slate-600 transition-colors duration-200 flex flex-col h-full">
                            <a href="/product/<?= $product['id'] ?>" class="block h-56 bg-slate-100 dark:bg-slate-800 relative overflow-hidden transition-colors duration-300">
                                <div class="absolute inset-0 flex items-center justify-center text-slate-400 dark:text-slate-500 font-medium text-sm group-hover:scale-105 transition-transform duration-500 ease-out">
                                    <?php if (!empty($product['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="object-cover w-full h-full">
                                    <?php else: ?>
                                        No Image
                                    <?php endif; ?>
                                </div>
                                <?php if($product['stock_quantity'] <= 0): ?>
                                <div class="absolute top-3 left-3 bg-red-600 text-white border border-red-700 text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm transition-colors duration-300">OUT OF STOCK</div>
                                <?php elseif($product['is_on_sale']): ?>
                                <div class="absolute top-3 left-3 bg-blue-600 text-white border border-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm transition-colors duration-300 tracking-wider">SALE</div>
                                <?php endif; ?>
                            </a>
                            <div class="p-6 flex-grow flex flex-col">
                                <div class="text-xs font-medium text-slate-500 dark:text-slate-400 tracking-wide uppercase mb-2 transition-colors duration-300"><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></div>
                                <h3 class="font-medium text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-300">
                                    <a href="/product/<?= $product['id'] ?>" class="focus:outline-none focus:underline"><?= htmlspecialchars($product['title']) ?></a>
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-6 leading-relaxed transition-colors duration-300"><?= htmlspecialchars($product['description']) ?></p>
                                
                                <div class="mt-auto flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-slate-900 dark:text-white text-lg transition-colors duration-300">R <?= number_format($product['price'], 2) ?></span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <?php if(isset($_SESSION['user_id'])): ?>
                                        <form action="/wishlist/toggle" method="POST" class="inline">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <input type="hidden" name="redirect" value="/products">
                                            <button type="submit" aria-label="Toggle wishlist" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700 text-red-500 p-2.5 rounded transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500" title="Toggle wishlist">
                                                <i class="ph ph-heart text-lg"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                        <form action="/cart/add" method="POST" class="inline">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <input type="hidden" name="title" value="<?= htmlspecialchars($product['title']) ?>">
                                            <input type="hidden" name="price" value="<?= $product['price'] ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" aria-label="Add to cart" class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 hover:border-slate-300 dark:hover:border-slate-500 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-blue-600 dark:hover:text-blue-400 text-slate-700 dark:text-slate-300 p-2.5 rounded transition-colors duration-200 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" title="Add to cart">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Pagination -->
            <div class="mt-16 flex justify-center border-t border-slate-200 dark:border-slate-800 pt-8 transition-colors duration-300">
                <nav class="flex items-center gap-2" aria-label="Pagination">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">Previous</a>
                    <a href="#" aria-current="page" class="w-10 h-10 flex items-center justify-center bg-slate-900 dark:bg-blue-600 text-white rounded-lg font-medium text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-950">1</a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg font-medium text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">2</a>
                    <a href="#" class="w-10 h-10 flex items-center justify-center text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg font-medium text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">3</a>
                    <span class="w-10 h-10 flex items-center justify-center text-slate-400 dark:text-slate-500">...</span>
                    <a href="#" class="px-4 py-2 text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">Next</a>
                </nav>
            </div>
        </main>
    </div>
</div>
