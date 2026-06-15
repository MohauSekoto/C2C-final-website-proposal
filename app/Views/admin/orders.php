<div class="mb-8">
    <h1 class="text-3xl font-display font-bold text-zinc-900 dark:text-white mb-2">Manage Orders</h1>
    <p class="text-zinc-500 dark:text-zinc-400">View and update the status of all marketplace orders.</p>
</div>

<div class="bg-white dark:bg-darkCard rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-zinc-50 dark:bg-zinc-800/30 text-xs uppercase tracking-wider text-zinc-500 dark:text-zinc-400 border-b border-zinc-200 dark:border-zinc-800">
                    <th scope="col" class="px-6 py-4 font-medium">Order ID</th>
                    <th scope="col" class="px-6 py-4 font-medium">Buyer</th>
                    <th scope="col" class="px-6 py-4 font-medium">Amount</th>
                    <th scope="col" class="px-6 py-4 font-medium">Date</th>
                    <th scope="col" class="px-6 py-4 font-medium">Status</th>
                    <th scope="col" class="px-6 py-4 font-medium text-right">Update Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <?php foreach($orders as $order): ?>
                <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-200">
                        #<?= htmlspecialchars($order['id']) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="font-medium text-zinc-900 dark:text-zinc-200"><?= htmlspecialchars($order['buyer_name']) ?></div>
                        <div class="text-zinc-500 dark:text-zinc-400 text-xs"><?= htmlspecialchars($order['buyer_email']) ?></div>
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
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <form action="/admin/orders/update" method="POST" class="flex items-center justify-end gap-2">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" class="text-xs bg-zinc-50 border border-zinc-200 text-zinc-900 rounded-md focus:ring-primary focus:border-primary block p-1.5 dark:bg-zinc-800/50 dark:border-zinc-700 dark:text-white outline-none">
                                <option value="pending_payment" <?= $order['status'] === 'pending_payment' ? 'selected' : '' ?>>Pending Payment</option>
                                <option value="paid" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                <option value="refund_requested" <?= $order['status'] === 'refund_requested' ? 'selected' : '' ?>>Refund Requested</option>
                                <option value="refunded" <?= $order['status'] === 'refunded' ? 'selected' : '' ?>>Refunded</option>
                            </select>
                            <button type="submit" class="bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 px-3 py-1.5 rounded-md text-xs font-medium hover:bg-zinc-800 dark:hover:bg-zinc-100 transition shadow-sm" aria-label="Save order status">
                                Save
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($orders)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                        <i class="ph ph-receipt text-4xl mb-3 opacity-50"></i>
                        <p>No orders found.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
