<div class="mb-8">
    <h1 class="text-3xl font-display font-bold text-zinc-900 dark:text-white mb-2">Overview</h1>
    <p class="text-zinc-500 dark:text-zinc-400">Welcome back. Here's what's happening across the marketplace today.</p>
</div>

<!-- Bento Grid Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-10">
    <!-- Metric 1 -->
    <div class="bg-white dark:bg-darkCard rounded-xl p-6 border border-zinc-200 dark:border-zinc-800 flex flex-col justify-between h-32 hover:-translate-y-1 transition-transform duration-300">
        <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-2">
            <h3 class="text-xs font-medium uppercase tracking-widest">Total Users</h3>
            <i class="ph ph-users text-lg"></i>
        </div>
        <p class="text-3xl font-display font-bold text-zinc-900 dark:text-white"><?= number_format($totalUsers) ?></p>
    </div>

    <!-- Metric 2 -->
    <div class="bg-white dark:bg-darkCard rounded-xl p-6 border border-zinc-200 dark:border-zinc-800 flex flex-col justify-between h-32 hover:-translate-y-1 transition-transform duration-300">
        <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-2">
            <h3 class="text-xs font-medium uppercase tracking-widest">Total Orders</h3>
            <i class="ph ph-shopping-bag text-lg"></i>
        </div>
        <p class="text-3xl font-display font-bold text-zinc-900 dark:text-white"><?= number_format($totalOrders) ?></p>
    </div>

    <!-- Metric 3 -->
    <div class="bg-white dark:bg-darkCard rounded-xl p-6 border border-zinc-200 dark:border-zinc-800 flex flex-col justify-between h-32 hover:-translate-y-1 transition-transform duration-300">
        <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-2">
            <h3 class="text-xs font-medium uppercase tracking-widest">Platform Revenue</h3>
            <i class="ph ph-currency-circle-dollar text-lg text-green-500"></i>
        </div>
        <p class="text-3xl font-display font-bold text-zinc-900 dark:text-white">R <?= number_format($revenue, 2) ?></p>
    </div>

    <!-- Metric 4 -->
    <div class="bg-white dark:bg-darkCard rounded-xl p-6 border border-zinc-200 dark:border-zinc-800 flex flex-col justify-between h-32 hover:-translate-y-1 transition-transform duration-300">
        <div class="flex items-center justify-between text-zinc-500 dark:text-zinc-400 mb-2">
            <h3 class="text-xs font-medium uppercase tracking-widest">Escrow Volume</h3>
            <i class="ph ph-vault text-lg text-blue-500"></i>
        </div>
        <p class="text-3xl font-display font-bold text-zinc-900 dark:text-white">R <?= number_format($escrowVolume, 2) ?></p>
    </div>
</div>

<!-- Recent Orders Section -->
<div class="bg-white dark:bg-darkCard rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
    <div class="px-6 py-5 border-b border-zinc-100 dark:border-zinc-800/80 flex items-center justify-between">
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Recent Orders</h2>
        <a href="/admin/orders" class="text-sm font-medium text-primary dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center gap-1">
            View All <i class="ph ph-arrow-right"></i>
        </a>
    </div>
    
    <?php if(empty($recentOrders)): ?>
        <div class="p-8 text-center text-zinc-500 dark:text-zinc-400">
            <i class="ph ph-receipt text-3xl mb-2 opacity-50"></i>
            <p>No recent orders found.</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-800/30 text-xs uppercase tracking-wider text-zinc-500 dark:text-zinc-400 border-b border-zinc-200 dark:border-zinc-800">
                        <th class="px-6 py-4 font-medium">Order ID</th>
                        <th class="px-6 py-4 font-medium">Buyer</th>
                        <th class="px-6 py-4 font-medium">Amount</th>
                        <th class="px-6 py-4 font-medium">Date</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    <?php foreach($recentOrders as $order): ?>
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-200">
                                #<?= htmlspecialchars($order['id']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                                <?= htmlspecialchars($order['buyer_name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-400">
                                R <?= number_format($order['total'], 2) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500">
                                <?= date('M d, Y H:i', strtotime($order['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php 
                                    $statusClasses = [
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'delivered' => 'bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-400',
                                        'in_transit' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'processing' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
                                        'paid' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
                                        'pending_payment' => 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300',
                                        'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
                                    ];
                                    $class = $statusClasses[$order['status']] ?? 'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300';
                                    $displayStatus = ucwords(str_replace('_', ' ', $order['status']));
                                ?>
                                <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-md <?= $class ?>">
                                    <?= $displayStatus ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
