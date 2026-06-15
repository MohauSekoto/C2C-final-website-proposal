<!-- app/Views/checkout_content.php -->
<div class="bg-white dark:bg-slate-950 min-h-screen py-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl lg:text-4xl font-display font-medium tracking-tight text-slate-900 dark:text-white mb-8 transition-colors">Secure Checkout</h1>
        
        <?php if(!empty($_GET['error'])): ?>
            <div class="bg-red-50 dark:bg-red-900/30 text-red-800 dark:text-red-400 p-4 rounded border border-red-200 dark:border-red-800 mb-8 flex items-center gap-3">
                <i class="ph ph-warning-circle text-xl"></i> There was an issue processing your order. Please try again.
            </div>
        <?php endif; ?>

        <?php 
            $subtotal = 0;
            $total_items = 0;
            foreach($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
                $total_items += $item['quantity'];
            }
        ?>

        <div x-data="checkoutCalculator(<?= $subtotal ?>, <?= $total_items ?>)" class="flex flex-col lg:flex-row gap-12">
            <!-- Left Column: Checkout Form -->
            <div class="lg:w-2/3">
                <form action="/checkout" method="POST" id="checkout-form" class="space-y-8" @submit="loading = true">
                    <input type="hidden" name="shipping_cost" :value="shippingCost">
                    <input type="hidden" name="shipping_method" :value="selectedShipping">
                    
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

                    <!-- Shipping Info & UrgentGo -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm transition-colors duration-300">
                        <h2 class="text-xl font-medium text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                            <i class="ph ph-truck text-orange-500"></i> UrgentGo Courier Delivery
                        </h2>
                        
                        <div class="mb-6 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="ph ph-package text-2xl text-slate-400"></i>
                                <div>
                                    <h3 class="font-medium text-slate-900 dark:text-white" x-text="'Parcel Size: ' + parcelSizeLabel"></h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400" x-text="totalItems + ' items in cart'"></p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 mb-6">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Select Shipping Method</label>
                            
                            <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer transition-all" :class="selectedShipping === 'economy' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/10' : 'border-slate-200 dark:border-slate-700 hover:border-orange-300'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" x-model="selectedShipping" value="economy" class="text-orange-500 focus:ring-orange-500">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">Economy Delivery (2-4 days)</p>
                                    </div>
                                </div>
                                <span class="font-medium text-slate-900 dark:text-white" x-text="'R ' + (45 * multiplier).toFixed(2)"></span>
                            </label>

                            <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer transition-all" :class="selectedShipping === 'express' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/10' : 'border-slate-200 dark:border-slate-700 hover:border-orange-300'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" x-model="selectedShipping" value="express" class="text-orange-500 focus:ring-orange-500">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">Express Delivery (Next-day)</p>
                                    </div>
                                </div>
                                <span class="font-medium text-slate-900 dark:text-white" x-text="'R ' + (85 * multiplier).toFixed(2)"></span>
                            </label>

                            <label class="flex items-center justify-between p-4 border rounded-lg cursor-pointer transition-all" :class="selectedShipping === 'same_day' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/10' : 'border-slate-200 dark:border-slate-700 hover:border-orange-300'">
                                <div class="flex items-center gap-3">
                                    <input type="radio" x-model="selectedShipping" value="same_day" class="text-orange-500 focus:ring-orange-500">
                                    <div>
                                        <p class="font-medium text-slate-900 dark:text-white">Same-Day Delivery (Gauteng 10AM cut-off)</p>
                                    </div>
                                </div>
                                <span class="font-medium text-slate-900 dark:text-white" x-text="'R ' + (120 * multiplier).toFixed(2)"></span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Delivery Address</label>
                            <textarea name="address" rows="4" required class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg p-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Please provide a complete address including city, province, and postal code.</p>
                        </div>
                    </div>

                    <!-- Payment Details (PayFast Sandbox) -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm transition-colors duration-300">
                        <h2 class="text-xl font-medium text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                            <i class="ph ph-credit-card text-blue-600 dark:text-blue-400"></i> Payment via PayFast Sandbox
                        </h2>
                        <div class="bg-green-50 dark:bg-green-900/20 text-green-800 dark:text-green-400 p-4 rounded border border-green-200 dark:border-green-800 mb-4 text-sm flex gap-3">
                            <i class="ph ph-shield-check mt-0.5 text-lg"></i>
                            <p>You will be securely redirected to PayFast's Sandbox testing environment. Funds will be deposited into the Escrow System once the transaction is complete.</p>
                        </div>
                    </div>
                    
                    <div class="hidden lg:block">
                        <button type="submit" form="checkout-form" class="w-full bg-slate-900 dark:bg-blue-600 text-white rounded-lg font-medium py-4 text-lg hover:bg-slate-800 dark:hover:bg-blue-700 transition-all shadow-md active:scale-[0.99] flex items-center justify-center gap-2">
                            Proceed to PayFast
                            <i class="ph ph-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="lg:w-1/3">
                <div class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 lg:sticky lg:top-8 shadow-sm transition-colors duration-300">
                    <h2 class="text-lg font-medium text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-slate-800 pb-4">Order Summary</h2>
                    
                    <div class="space-y-4 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                        <?php foreach($cart as $id => $item): ?>
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-sm font-medium text-slate-900 dark:text-white line-clamp-2"><?= htmlspecialchars($item['title']) ?></h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Qty: <?= $item['quantity'] ?></p>
                                </div>
                                <div class="font-medium text-slate-900 dark:text-white whitespace-nowrap ml-4">
                                    R <?= number_format($item['price'] * $item['quantity'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="border-t border-slate-200 dark:border-slate-800 mt-6 pt-6 space-y-4">
                        <div class="flex justify-between text-slate-600 dark:text-slate-400 text-sm">
                            <span>Subtotal</span>
                            <span x-text="'R ' + subtotal.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-400 text-sm">
                            <span>Shipping (UrgentGo)</span>
                            <span x-text="'R ' + shippingCost.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-400 text-sm">
                            <span>Platform Fee (5%)</span>
                            <span x-text="'R ' + platformFee.toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-lg font-medium text-slate-900 dark:text-white border-t border-slate-200 dark:border-slate-800 pt-4 mt-4">
                            <span>Grand Total</span>
                            <span x-text="'R ' + grandTotal.toFixed(2)"></span>
                        </div>
                    </div>

                    <div class="mt-8 lg:hidden">
                        <button type="submit" form="checkout-form" class="w-full bg-slate-900 dark:bg-blue-600 text-white rounded-lg font-medium py-4 text-lg hover:bg-slate-800 dark:hover:bg-blue-700 transition-all shadow-md active:scale-[0.99] flex items-center justify-center gap-2">
                            Proceed to PayFast
                            <i class="ph ph-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkoutCalculator', (initialSubtotal, itemsCount) => ({
            subtotal: initialSubtotal,
            totalItems: itemsCount,
            selectedShipping: 'economy',
            
            get multiplier() {
                if (this.totalItems <= 10) return 1;
                if (this.totalItems <= 20) return 1.5;
                return 2.0;
            },
            
            get parcelSizeLabel() {
                if (this.totalItems <= 10) return 'Small';
                if (this.totalItems <= 20) return 'Medium';
                return 'Large';
            },

            get shippingCost() {
                let base = 45;
                if (this.selectedShipping === 'express') base = 85;
                if (this.selectedShipping === 'same_day') base = 120;
                return base * this.multiplier;
            },

            get platformFee() {
                return this.subtotal * 0.05;
            },

            get grandTotal() {
                return this.subtotal + this.shippingCost + this.platformFee;
            }
        }));
    });
</script>
