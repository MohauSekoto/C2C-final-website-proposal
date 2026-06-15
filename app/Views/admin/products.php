<div class="mb-8">
    <h1 class="text-3xl font-display font-bold text-zinc-900 dark:text-white mb-2">Manage Products</h1>
    <p class="text-zinc-500 dark:text-zinc-400">Review and moderate products listed by sellers.</p>
</div>

<div class="bg-white dark:bg-darkCard rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-zinc-50 dark:bg-zinc-800/30 text-xs uppercase tracking-wider text-zinc-500 dark:text-zinc-400 border-b border-zinc-200 dark:border-zinc-800">
                    <th scope="col" class="px-6 py-4 font-medium">ID</th>
                    <th scope="col" class="px-6 py-4 font-medium">Title</th>
                    <th scope="col" class="px-6 py-4 font-medium">Category</th>
                    <th scope="col" class="px-6 py-4 font-medium">Price</th>
                    <th scope="col" class="px-6 py-4 font-medium">Stock</th>
                    <th scope="col" class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <?php foreach($products as $product): ?>
                <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                        <?= htmlspecialchars($product['id']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="/product/<?= $product['id'] ?>" class="text-primary dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors flex items-center gap-1.5" target="_blank">
                            <?= htmlspecialchars($product['title']) ?>
                            <i class="ph ph-arrow-up-right text-xs opacity-70"></i>
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                        <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                        R <?= number_format($product['price'], 2) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500">
                        <?php if($product['stock_quantity'] > 0): ?>
                            <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-md bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400"><?= $product['stock_quantity'] ?> in stock</span>
                        <?php else: ?>
                            <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-md bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Out of Stock</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <form action="/admin/products/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this product?');">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 focus:outline-none transition-colors p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded" title="Delete Product">
                                <i class="ph ph-trash text-lg"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($products)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                        <i class="ph ph-package text-4xl mb-3 opacity-50"></i>
                        <p>No products found.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
