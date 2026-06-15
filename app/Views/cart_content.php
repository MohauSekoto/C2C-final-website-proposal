<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-dark dark:text-white">
    <div class="flex items-center justify-between mb-10 border-b border-zinc-200 dark:border-zinc-800 pb-6">
        <h1 class="font-display text-4xl font-medium tracking-tight">Shopping Cart</h1>
        <span class="text-sm font-medium text-zinc-500 bg-zinc-100 dark:bg-zinc-800 px-3 py-1 rounded-full"><?= isset($cart) ? count($cart) : 0 ?> Items</span>
    </div>

    <?php if(empty($cart)): ?>
        <div class="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-16 text-center shadow-sm">
            <i class="ph ph-shopping-cart-simple text-6xl text-zinc-300 dark:text-zinc-700 mb-6"></i>
            <h2 class="text-xl font-medium mb-2 text-slate-900 dark:text-white">Your cart is empty</h2>
            <p class="text-zinc-500 mb-8 max-w-md mx-auto">Looks like you haven't added any products to your cart yet.</p>
            <a href="/products" class="bg-primary text-white px-8 py-3 rounded text-sm font-medium hover:bg-blue-700 transition-all shadow-lg shadow-primary/20 inline-block">Continue Shopping</a>
        </div>
    <?php else: ?>
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-sm overflow-hidden relative">
            <!-- Receipt Top Edge Decoration -->
            <div class="h-2 w-full bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSI4Ij48cGF0aCBkPSJNMCAwbDEwIDggMTAtOHoiIGZpbGw9IiNlN2U1ZTQiLz48L3N2Zz4=')] dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSI4Ij48cGF0aCBkPSJNMCAwbDEwIDggMTAtOHoiIGZpbGw9IiMyNzI3MmEiLz48L3N2Zz4=')] bg-repeat-x"></div>
            
            <div class="p-6 sm:p-10">
                <!-- Receipt Header -->
                <div class="text-center mb-8 pb-8 border-b border-dashed border-zinc-300 dark:border-zinc-700">
                    <h2 class="text-xl font-display font-medium text-slate-900 dark:text-white mb-1">KasiBuy Platform</h2>
                    <p class="text-sm text-zinc-500 font-mono">ORDER SUMMARY RECEIPT</p>
                    <p class="text-xs text-zinc-400 mt-2"><?= date('F j, Y - H:i') ?></p>
                </div>

                <!-- Items List -->
                <div class="space-y-6">
                    <?php 
                    $total = 0;
                    foreach($cart as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 group">
                            <div class="flex-grow">
                                <div class="flex items-start justify-between">
                                    <h3 class="font-medium text-lg text-slate-900 dark:text-white pr-4"><?= htmlspecialchars($item['title']) ?></h3>
                                    <span class="font-mono text-slate-900 dark:text-white font-medium whitespace-nowrap">R <?= number_format($subtotal, 2) ?></span>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <p class="text-sm text-zinc-500 font-mono">R <?= number_format($item['price'], 2) ?> &times; <?= $item['quantity'] ?></p>
                                    <form action="/cart/remove" method="POST" class="inline">
                                        <input type="hidden" name="product_id" value="<?= $id ?>">
                                        <button class="text-red-500/70 hover:text-red-500 text-sm font-medium transition-colors flex items-center gap-1 opacity-0 group-hover:opacity-100 focus:opacity-100">
                                            <i class="ph ph-trash"></i> Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Receipt Calculations -->
                <div class="mt-8 pt-8 border-t border-dashed border-zinc-300 dark:border-zinc-700 space-y-3 font-mono text-sm text-zinc-600 dark:text-zinc-400">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="text-slate-900 dark:text-white">R <?= number_format($total, 2) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping Fee</span>
                        <span class="text-zinc-400 italic">Calculated at checkout</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Platform Commission</span>
                        <span class="text-zinc-400 italic">Calculated at checkout</span>
                    </div>
                </div>

                <!-- Receipt Total -->
                <div class="mt-6 pt-6 border-t-2 border-slate-900 dark:border-white">
                    <div class="flex justify-between items-end">
                        <span class="text-lg font-medium text-slate-900 dark:text-white uppercase tracking-wider">Subtotal Total</span>
                        <span class="text-3xl font-display font-medium text-slate-900 dark:text-white">R <?= number_format($total, 2) ?></span>
                    </div>
                </div>

                <!-- Checkout Actions -->
                <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="/products" class="px-6 py-3 rounded text-sm font-medium border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors text-center">Continue Shopping</a>
                    <a href="/checkout" class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-8 py-3 rounded text-sm font-medium hover:bg-slate-800 dark:hover:bg-zinc-100 transition-all text-center flex items-center justify-center gap-2 shadow-lg shadow-slate-900/20">
                        Proceed to Checkout <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Receipt Bottom Edge Decoration -->
            <div class="h-2 w-full bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSI4Ij48cGF0aCBkPSJNMCA4bDEwLThMMjAgOHoiIGZpbGw9IiNlN2U1ZTQiLz48L3N2Zz4=')] dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSI4Ij48cGF0aCBkPSJNMCA4bDEwLThMMjAgOHoiIGZpbGw9IiMyNzI3MmEiLz48L3N2Zz4=')] bg-repeat-x absolute bottom-0 left-0"></div>
        </div>
    <?php endif; ?>
</div>
