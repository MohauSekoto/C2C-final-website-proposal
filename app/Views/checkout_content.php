<!-- app/Views/checkout_content.php -->
<div class="bg-white dark:bg-slate-950 min-h-screen py-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl lg:text-4xl font-display font-medium tracking-tight text-slate-900 dark:text-white mb-8 transition-colors">Secure Checkout</h1>
        
        <?php if(!empty($_GET['error'])): ?>
            <div class="bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-400 p-4 rounded border border-red-200 dark:border-red-800 mb-8 flex items-center gap-3">
                <i class="ph ph-warning-circle text-xl"></i> There was an issue processing your order. Please try again.
            </div>
        <?php endif; ?>

        <div class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column: Checkout Form -->
            <div class="lg:w-2/3">
                <form action="/checkout" method="POST" id="checkout-form" class="space-y-8">
                    
                    <!-- Contact Info -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm transition-colors duration-300">
                        <h2 class="text-xl font-medium text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                            <i class="ph ph-user text-blue-600 dark:text-blue-400"></i> Contact Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Full Name</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email Address</label>
                                <input type="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled class="w-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg p-3 text-slate-500 cursor-not-allowed transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Phone Number</label>
                                <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm transition-colors duration-300">
                        <h2 class="text-xl font-medium text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                            <i class="ph ph-map-pin text-blue-600 dark:text-blue-400"></i> Shipping Address
                        </h2>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Delivery Address</label>
                            <textarea name="address" rows="4" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Please provide a complete address including city, province, and postal code.</p>
                        </div>
                    </div>

                    <!-- Payment Details (Simulated) -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm transition-colors duration-300">
                        <h2 class="text-xl font-medium text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                            <i class="ph ph-credit-card text-blue-600 dark:text-blue-400"></i> Payment Details
                        </h2>
                        <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400 p-4 rounded border border-blue-200 dark:border-blue-800 mb-4 text-sm flex gap-3">
                            <i class="ph ph-info mt-0.5 text-lg"></i>
                            <p>This is a simulated payment gateway. Your order will be placed into the secure escrow system without requiring actual payment details.</p>
                        </div>
                    </div>
                    
                    <div class="hidden lg:block">
                        <button type="submit" form="checkout-form" class="w-full bg-slate-900 dark:bg-blue-600 text-white rounded-lg font-medium py-4 text-lg hover:bg-slate-800 dark:hover:bg-blue-700 transition-all shadow-md active:scale-[0.99] flex items-center justify-center gap-2">
                            Complete Order & Pay Securely
                            <i class="ph ph-lock-key"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="lg:w-1/3">
                <div class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 lg:sticky lg:top-8 shadow-sm transition-colors duration-300">
                    <h2 class="text-lg font-medium text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-800 pb-4">Order Summary</h2>
                    
                    <div class="space-y-4 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                        <?php 
                        $total = 0;
                        foreach($cart as $id => $item): 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-medium text-slate-900 dark:text-white line-clamp-2"><?= htmlspecialchars($item['title']) ?></h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Qty: <?= $item['quantity'] ?></p>
                                </div>
                                <div class="font-medium text-slate-900 dark:text-white whitespace-nowrap ml-4">
                                    R <?= number_format($subtotal, 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="border-t border-slate-200 dark:border-slate-800 mt-6 pt-6 space-y-4">
                        <?php 
                            $shipping_fee = 150.00;
                            $commission_fee = $total * 0.05;
                            $grand_total = $total + $shipping_fee + $commission_fee;
                        ?>
                        <div class="flex justify-between text-slate-600 dark:text-slate-400 text-sm">
                            <span>Subtotal</span>
                            <span>R <?= number_format($total, 2) ?></span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-400 text-sm">
                            <span>Shipping (Flat Rate)</span>
                            <span>R <?= number_format($shipping_fee, 2) ?></span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-400 text-sm">
                            <span>Platform Fee (5%)</span>
                            <span>R <?= number_format($commission_fee, 2) ?></span>
                        </div>
                        <div class="flex justify-between text-lg font-medium text-slate-900 dark:text-white border-t border-slate-200 dark:border-slate-800 pt-4 mt-4">
                            <span>Grand Total</span>
                            <span>R <?= number_format($grand_total, 2) ?></span>
                        </div>
                    </div>

                    <div class="mt-8 lg:hidden">
                        <button type="submit" form="checkout-form" class="w-full bg-slate-900 dark:bg-blue-600 text-white rounded-lg font-medium py-4 text-lg hover:bg-slate-800 dark:hover:bg-blue-700 transition-all shadow-md active:scale-[0.99] flex items-center justify-center gap-2">
                            Complete Order & Pay Securely
                            <i class="ph ph-lock-key"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
