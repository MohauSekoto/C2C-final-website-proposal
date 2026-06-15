<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-dark dark:text-white" x-data="{ activeTab: 'profile' }">
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="font-display text-4xl font-medium tracking-tight">My Account</h1>
            <p class="text-zinc-500 mt-2">Manage your profile, track orders, and view your wishlist.</p>
        </div>
        <div class="flex gap-4">
            <?php if($user['role'] === 'buyer'): ?>
                <a href="/register-store" class="bg-yellow-500 text-zinc-900 px-5 py-2.5 rounded text-sm font-medium hover:bg-yellow-400 transition-colors shadow-sm">Start Selling</a>
            <?php elseif($user['role'] === 'seller'): ?>
                <a href="/dashboard" class="bg-primary text-white px-5 py-2.5 rounded text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm shadow-primary/20">Seller Dashboard</a>
            <?php endif; ?>
            <a href="/logout" class="bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 px-5 py-2.5 rounded text-sm font-medium hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors border border-zinc-200 dark:border-zinc-700">Logout</a>
        </div>
    </div>

    <?php if(!empty($success)): ?>
        <div class="bg-green-50 dark:bg-green-900/30 text-green-800 dark:text-green-400 p-4 rounded border border-green-200 dark:border-green-800 mb-6 flex items-center gap-3">
            <i class="ph ph-check-circle text-xl"></i> <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>
    <?php if(!empty($error)): ?>
        <div class="bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-400 p-4 rounded border border-red-200 dark:border-red-800 mb-6 flex items-center gap-3">
            <i class="ph ph-warning-circle text-xl"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Navigation Tabs -->
    <div class="flex space-x-1 border-b border-zinc-200 dark:border-zinc-800 mb-8 overflow-x-auto hide-scrollbar">
        <button @click="activeTab = 'profile'" :class="{'border-primary text-primary': activeTab === 'profile', 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300': activeTab !== 'profile'}" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
            <i class="ph ph-user text-lg"></i> Profile Details
        </button>
        <button @click="activeTab = 'orders'" :class="{'border-primary text-primary': activeTab === 'orders', 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300': activeTab !== 'orders'}" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
            <i class="ph ph-shopping-bag text-lg"></i> Order Tracking
        </button>
        <button @click="activeTab = 'wishlist'" :class="{'border-primary text-primary': activeTab === 'wishlist', 'border-transparent text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-300': activeTab !== 'wishlist'}" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors flex items-center gap-2">
            <i class="ph ph-heart text-lg"></i> My Wishlist
            <?php if(count($wishlist) > 0): ?>
                <span class="bg-primary text-white text-xs py-0.5 px-2 rounded-full ml-1"><?= count($wishlist) ?></span>
            <?php endif; ?>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left content area -->
        <div class="lg:col-span-2">
            
            <!-- Profile Tab -->
            <div x-show="activeTab === 'profile'" x-transition.opacity.duration.300ms class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded shadow-sm p-6 sm:p-8">
                <h2 class="text-xl font-medium tracking-tight mb-6 border-b border-zinc-100 dark:border-zinc-800 pb-4">Personal Information</h2>
                <form action="/profile/update" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Full Name</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required class="w-full rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-dark dark:text-white focus:ring-primary focus:border-primary shadow-sm px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Email Address</label>
                            <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled class="w-full rounded border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 cursor-not-allowed px-4 py-2">
                            <p class="text-xs text-zinc-500 mt-1">Email cannot be changed.</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Phone Number</label>
                        <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="w-full sm:w-1/2 rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-dark dark:text-white focus:ring-primary focus:border-primary shadow-sm px-4 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Delivery Address</label>
                        <textarea name="address" rows="3" class="w-full rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-dark dark:text-white focus:ring-primary focus:border-primary shadow-sm px-4 py-2"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded text-sm font-medium hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-primary/25 active:scale-95 inline-flex items-center gap-2">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Orders Tab -->
            <div x-show="activeTab === 'orders'" x-transition.opacity.duration.300ms style="display: none;" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50">
                    <h2 class="text-lg font-medium tracking-tight text-slate-900 dark:text-white">Order Tracking & History</h2>
                </div>
                
                <?php if(empty($orders)): ?>
                    <div class="p-12 text-center text-zinc-500 dark:text-zinc-400">
                        <i class="ph ph-package text-6xl mb-4 text-zinc-300 dark:text-zinc-700"></i>
                        <p class="mb-4">You haven't placed any orders yet.</p>
                        <a href="/products" class="text-primary hover:text-blue-700 font-medium transition-colors">Start Shopping &rarr;</a>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-zinc-200 dark:divide-zinc-800">
                        <?php foreach($orders as $order): ?>
                            <div x-data="{ expanded: false }" class="p-6 hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 mb-6 cursor-pointer" @click="expanded = !expanded">
                                    <div>
                                        <h3 class="font-medium text-lg text-slate-900 dark:text-white flex items-center gap-2">
                                            Order #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?>
                                            <i class="ph ph-caret-down text-sm text-zinc-400 transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                                        </h3>
                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1"><?= date('F j, Y', strtotime($order['created_at'])) ?> &bull; <?= $order['item_count'] ?> item(s)</p>
                                    </div>
                                    <div class="text-left sm:text-right">
                                        <p class="font-display font-medium text-xl text-slate-900 dark:text-white">R <?= number_format($order['total_amount'], 2) ?></p>
                                    </div>
                                </div>
                                
                                <!-- Order Tracking Timeline -->
                                <div class="mb-6 relative">
                                    <?php 
                                        $statuses = ['pending', 'processing', 'in_transit', 'delivered'];
                                        // If cancelled, just show cancelled state
                                        $is_cancelled = $order['status'] === 'cancelled';
                                        if (!$is_cancelled) {
                                            $current_index = array_search($order['status'], $statuses);
                                            if ($current_index === false) {
                                                if ($order['status'] === 'completed') $current_index = 3;
                                                else $current_index = 0;
                                            }
                                        }
                                    ?>
                                    
                                    <?php if ($is_cancelled): ?>
                                        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 flex items-center gap-3 text-red-700 dark:text-red-400">
                                            <i class="ph ph-x-circle text-2xl"></i>
                                            <span class="font-medium">This order was cancelled.</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-center justify-between relative z-10">
                                            <?php foreach ($statuses as $index => $step): ?>
                                                <?php 
                                                    $is_completed = $index <= $current_index;
                                                    $is_active = $index === $current_index;
                                                    
                                                    $circleClass = $is_completed ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-zinc-400';
                                                    if ($is_active && $index !== count($statuses)-1) {
                                                        $circleClass .= ' ring-4 ring-blue-100 dark:ring-blue-900/30';
                                                    }
                                                    
                                                    $icons = ['ph-clock', 'ph-package', 'ph-truck', 'ph-check-circle'];
                                                    $labels = ['Pending', 'Processing', 'In Transit', 'Delivered'];
                                                ?>
                                                <div class="flex flex-col items-center gap-2 relative bg-white dark:bg-transparent px-2">
                                                    <div class="w-10 h-10 rounded-full border-2 flex items-center justify-center transition-colors <?= $circleClass ?>">
                                                        <i class="ph <?= $icons[$index] ?> text-lg"></i>
                                                    </div>
                                                    <span class="text-xs font-medium <?= $is_completed ? 'text-slate-900 dark:text-white' : 'text-zinc-500' ?>"><?= $labels[$index] ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <!-- Timeline Background Line -->
                                        <div class="absolute top-5 left-8 right-8 h-0.5 bg-zinc-200 dark:bg-zinc-700 -z-10">
                                            <?php 
                                                $progressWidth = '0%';
                                                if ($current_index === 1) $progressWidth = '33%';
                                                if ($current_index === 2) $progressWidth = '66%';
                                                if ($current_index === 3) $progressWidth = '100%';
                                            ?>
                                            <div class="h-full bg-blue-600 transition-all duration-500" style="width: <?= $progressWidth ?>"></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($order['status'] === 'in_transit'): ?>
                                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-5 mb-6">
                                        <h4 class="font-medium text-blue-900 dark:text-blue-300 flex items-center gap-2 mb-2"><i class="ph ph-truck"></i> Courier Details</h4>
                                        <p class="text-sm text-blue-800 dark:text-blue-400 mb-4">Your order is on its way. Please confirm receipt once it has been delivered to release payment to the seller.</p>
                                        <form action="/profile/order/confirm-receipt" method="POST">
                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors text-sm shadow-sm flex items-center gap-2">
                                                <i class="ph ph-check-square-offset"></i> Confirm Receipt
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Expandable Items List -->
                                <div x-show="expanded" x-collapse class="mt-4 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                                    <h4 class="text-sm font-medium text-slate-900 dark:text-white mb-3 uppercase tracking-wider">Order Items</h4>
                                    <div class="space-y-3">
                                        <?php if (!empty($order['items'])): ?>
                                            <?php foreach ($order['items'] as $item): ?>
                                                <div class="flex items-center gap-4 bg-white dark:bg-zinc-800 border border-zinc-100 dark:border-zinc-700 rounded-lg p-3">
                                                    <div class="w-16 h-16 bg-zinc-100 dark:bg-zinc-900 rounded shrink-0 overflow-hidden flex items-center justify-center">
                                                        <?php if (!empty($item['image_url'])): ?>
                                                            <img src="<?= htmlspecialchars($item['image_url']) ?>" class="w-full h-full object-cover">
                                                        <?php else: ?>
                                                            <i class="ph ph-image text-zinc-400"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="flex-grow">
                                                        <a href="/product/<?= $item['product_id'] ?>" class="font-medium text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors line-clamp-1"><?= htmlspecialchars($item['title'] ?? 'Product') ?></a>
                                                        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Qty: <?= $item['quantity'] ?></p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="font-medium text-slate-900 dark:text-white">R <?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Wishlist Tab -->
            <div x-show="activeTab === 'wishlist'" x-transition.opacity.duration.300ms style="display: none;">
                <?php if(empty($wishlist)): ?>
                    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded p-12 text-center text-zinc-500 dark:text-zinc-400 shadow-sm">
                        <i class="ph ph-heart-break text-6xl mb-4 text-zinc-300 dark:text-zinc-700"></i>
                        <p class="mb-4">Your wishlist is currently empty.</p>
                        <a href="/products" class="text-primary hover:text-blue-700 font-medium transition-colors">Discover products &rarr;</a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <?php foreach($wishlist as $item): ?>
                            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded overflow-hidden flex flex-col group relative shadow-sm hover:shadow-md transition-shadow">
                                <div class="h-48 bg-zinc-100 dark:bg-zinc-800 relative overflow-hidden">
                                    <?php if (!empty($item['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($item['image_url']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <?php else: ?>
                                        <div class="absolute inset-0 flex items-center justify-center text-zinc-400 text-sm">No Image</div>
                                    <?php endif; ?>
                                    
                                    <!-- Remove from wishlist button -->
                                    <form action="/wishlist/toggle" method="POST" class="absolute top-3 right-3 z-10">
                                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                        <input type="hidden" name="redirect" value="/profile">
                                        <button type="submit" class="bg-white/90 dark:bg-zinc-900/90 hover:bg-red-50 dark:hover:bg-red-900/50 text-red-500 p-2 rounded-full shadow backdrop-blur transition-colors">
                                            <i class="ph-fill ph-heart text-lg"></i>
                                        </button>
                                    </form>
                                    
                                    <?php if($item['is_on_sale']): ?>
                                        <div class="absolute top-3 left-3 bg-blue-600 text-white border border-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm tracking-wider">SALE</div>
                                    <?php endif; ?>
                                </div>
                                <div class="p-4 flex flex-col flex-grow">
                                    <div class="text-xs text-zinc-500 uppercase tracking-wide mb-1"><?= htmlspecialchars($item['category_name']) ?></div>
                                    <a href="/product/<?= $item['product_id'] ?>" class="font-medium hover:text-primary transition-colors mb-2 line-clamp-1"><?= htmlspecialchars($item['title']) ?></a>
                                    <div class="mt-auto flex justify-between items-center">
                                        <span class="font-display font-medium text-lg">R <?= number_format($item['price'], 2) ?></span>
                                        <?php if($item['stock_quantity'] > 0): ?>
                                            <form action="/cart/add" method="POST">
                                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                                <input type="hidden" name="title" value="<?= htmlspecialchars($item['title']) ?>">
                                                <input type="hidden" name="price" value="<?= $item['price'] ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="text-primary hover:text-blue-700 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 px-3 py-1.5 rounded transition-colors text-sm font-medium">Add to Cart</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-red-500 text-sm font-medium">Out of Stock</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Right sidebar (Account Summary) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-zinc-50 dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 rounded p-6 shadow-sm">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center text-2xl font-bold uppercase shrink-0">
                        <?= substr($user['name'] ?? 'U', 0, 1) ?>
                    </div>
                    <div>
                        <h3 class="font-medium text-lg"><?= htmlspecialchars($user['name'] ?? 'User') ?></h3>
                        <p class="text-sm text-zinc-500 capitalize"><?= htmlspecialchars($user['role'] ?? 'Buyer') ?> Account</p>
                    </div>
                </div>
                
                <div class="space-y-4 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <div class="flex items-start gap-3 text-sm">
                        <i class="ph ph-envelope text-zinc-400 text-lg shrink-0 mt-0.5"></i>
                        <span class="text-zinc-700 dark:text-zinc-300 break-all"><?= htmlspecialchars($user['email'] ?? 'No email') ?></span>
                    </div>
                    <div class="flex items-start gap-3 text-sm">
                        <i class="ph ph-phone text-zinc-400 text-lg shrink-0 mt-0.5"></i>
                        <span class="text-zinc-700 dark:text-zinc-300"><?= htmlspecialchars($user['phone'] ?? 'No phone added') ?></span>
                    </div>
                    <div class="flex items-start gap-3 text-sm">
                        <i class="ph ph-map-pin text-zinc-400 text-lg shrink-0 mt-0.5"></i>
                        <span class="text-zinc-700 dark:text-zinc-300"><?= nl2br(htmlspecialchars($user['address'] ?? 'No address added')) ?></span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded p-6 shadow-sm">
                <h3 class="font-medium mb-4">Need Help?</h3>
                <p class="text-sm text-zinc-500 mb-4">If you have any questions about your orders or account, we're here to help.</p>
                <a href="/contact" class="text-primary text-sm font-medium hover:underline flex items-center gap-1">
                    Contact Support <i class="ph ph-arrow-right"></i>
                </a>
            </div>
        </div>
        
    </div>
</div>
