<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-dark dark:text-white">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="font-display text-4xl font-medium tracking-tight">Seller Dashboard</h1>
            <p class="text-zinc-500 mt-2">Manage your inventory, track sales, and optimize your store.</p>
        </div>
        <a href="/seller/product/add" class="bg-primary text-white px-6 py-3 rounded text-sm font-medium hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-primary/25 active:scale-95 inline-flex items-center gap-2">
            <i class="ph ph-plus-circle text-lg"></i> Add Product
        </a>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Sales Volume -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 text-zinc-100 dark:text-zinc-800/50 group-hover:scale-110 transition-transform duration-700">
                <i class="ph ph-currency-zar text-8xl"></i>
            </div>
            <h3 class="text-zinc-500 dark:text-zinc-400 text-xs font-medium uppercase tracking-wider mb-2 relative z-10">Total Sales Volume</h3>
            <p class="text-3xl font-display font-medium relative z-10">R <?= number_format($totalSales, 2) ?></p>
        </div>

        <!-- Escrow Balance -->
        <div class="bg-blue-50 dark:bg-blue-900/10 border border-blue-200 dark:border-blue-900/30 rounded p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 text-blue-100 dark:text-blue-900/30 group-hover:scale-110 transition-transform duration-700">
                <i class="ph ph-bank text-8xl"></i>
            </div>
            <h3 class="text-blue-600 dark:text-blue-400 text-xs font-medium uppercase tracking-wider mb-2 relative z-10">Escrow Balance</h3>
            <p class="text-3xl font-display font-medium text-blue-900 dark:text-blue-300 relative z-10">R <?= number_format($escrowBalance ?? 0, 2) ?></p>
        </div>
        
        <!-- Pending Orders -->
        <div class="bg-orange-50 dark:bg-orange-900/10 border border-orange-200 dark:border-orange-900/30 rounded p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 text-orange-100 dark:text-orange-900/30 group-hover:scale-110 transition-transform duration-700">
                <i class="ph ph-clock text-8xl"></i>
            </div>
            <h3 class="text-orange-600 dark:text-orange-400 text-xs font-medium uppercase tracking-wider mb-2 relative z-10">Pending Orders</h3>
            <p class="text-3xl font-display font-medium text-orange-900 dark:text-orange-300 relative z-10"><?= $pendingOrders ?></p>
        </div>

        <!-- Average Rating -->
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded p-6 relative overflow-hidden group flex flex-col justify-between">
            <h3 class="text-zinc-500 dark:text-zinc-400 text-xs font-medium uppercase tracking-wider mb-2">Store Rating</h3>
            <div class="flex items-center gap-3">
                <p class="text-3xl font-display font-medium"><?= number_format($avgRating, 1) ?></p>
                <div class="flex text-yellow-400 text-xl">
                    <i class="ph ph-star-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Product List -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50">
            <h2 class="text-lg font-medium tracking-tight">Your Products</h2>
        </div>
        
        <?php if (empty($products)): ?>
            <div class="p-12 text-center text-zinc-500 dark:text-zinc-400 flex flex-col items-center justify-center">
                <i class="ph ph-storefront text-6xl mb-4 text-zinc-300 dark:text-zinc-700"></i>
                <p class="mb-4">You haven't listed any products yet.</p>
                <a href="/seller/product/add" class="text-primary hover:text-blue-700 font-medium transition-colors">Create your first listing &rarr;</a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 text-sm uppercase tracking-wider">
                            <th class="p-4 font-medium">Product</th>
                            <th class="p-4 font-medium">Category</th>
                            <th class="p-4 font-medium">Price</th>
                            <th class="p-4 font-medium">Stock</th>
                            <th class="p-4 font-medium">Status</th>
                            <th class="p-4 font-medium text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        <?php foreach($products as $product): ?>
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <?php if (!empty($product['image_url'])): ?>
                                            <div class="w-10 h-10 rounded bg-zinc-100 overflow-hidden shrink-0 border border-zinc-200 dark:border-zinc-700">
                                                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="w-full h-full object-cover">
                                            </div>
                                        <?php endif; ?>
                                        <span class="font-medium"><?= htmlspecialchars($product['title']) ?></span>
                                    </div>
                                </td>
                                <td class="p-4 text-sm text-zinc-600 dark:text-zinc-400"><?= htmlspecialchars($product['category_name']) ?></td>
                                <td class="p-4 font-medium">R <?= number_format($product['price'], 2) ?></td>
                                <td class="p-4 text-sm">
                                    <?php if ($product['stock_quantity'] > 0): ?>
                                        <span class="text-green-600 dark:text-green-400"><?= $product['stock_quantity'] ?> in stock</span>
                                    <?php else: ?>
                                        <span class="text-red-500">Out of stock</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <?php if ($product['is_on_sale']): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                            <i class="ph ph-tag-fill mr-1"></i> On Sale
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700">
                                            Active
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="/seller/product/edit/<?= $product['id'] ?>" class="text-zinc-400 hover:text-primary transition-colors p-2 rounded hover:bg-blue-50 dark:hover:bg-blue-900/20 active:scale-95 inline-block" title="Edit">
                                            <i class="ph ph-pencil-simple text-lg"></i>
                                        </a>
                                        <form action="/seller/product/delete/<?= $product['id'] ?>" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to permanently delete this product?');">
                                            <button type="submit" class="text-zinc-400 hover:text-red-500 transition-colors p-2 rounded hover:bg-red-50 dark:hover:bg-red-900/20 active:scale-95 inline-block" title="Delete">
                                                <i class="ph ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Orders List -->
    <div class="mt-12 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded overflow-hidden shadow-sm">
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50">
            <h2 class="text-lg font-medium tracking-tight">Orders to Fulfill</h2>
        </div>
        
        <?php if (empty($orders)): ?>
            <div class="p-12 text-center text-zinc-500 dark:text-zinc-400 flex flex-col items-center justify-center">
                <i class="ph ph-package text-6xl mb-4 text-zinc-300 dark:text-zinc-700"></i>
                <p class="mb-4">No recent orders found.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 text-sm uppercase tracking-wider">
                            <th class="p-4 font-medium">Order #</th>
                            <th class="p-4 font-medium">Buyer</th>
                            <th class="p-4 font-medium">Date</th>
                            <th class="p-4 font-medium">Total</th>
                            <th class="p-4 font-medium">Status</th>
                            <th class="p-4 font-medium text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        <?php foreach($orders as $order): ?>
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                                <td class="p-4 font-medium">
                                    #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm font-medium"><?= htmlspecialchars($order['buyer_name']) ?></div>
                                    <div class="text-xs text-zinc-500"><?= htmlspecialchars($order['buyer_email']) ?></div>
                                </td>
                                <td class="p-4 text-sm text-zinc-600 dark:text-zinc-400"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                <td class="p-4 font-medium">R <?= number_format($order['total_amount'], 2) ?></td>
                                <td class="p-4">
                                    <?php 
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'processing' => 'bg-blue-100 text-blue-800',
                                            'in_transit' => 'bg-purple-100 text-purple-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $color = $statusColors[$order['status']] ?? 'bg-zinc-100 text-zinc-800';
                                    ?>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium <?= $color ?> capitalize">
                                        <?= str_replace('_', ' ', $order['status']) ?>
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <?php if (in_array($order['status'], ['pending', 'processing'])): ?>
                                        <form action="/seller/order/mark-sent" method="POST">
                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                            <button type="submit" class="bg-orange-500 text-white px-3 py-1.5 rounded text-xs font-medium hover:bg-orange-600 transition-colors shadow-sm flex items-center gap-1">
                                                <i class="ph ph-truck"></i> Ship via UrgentGo
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-xs text-zinc-400">No action needed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
