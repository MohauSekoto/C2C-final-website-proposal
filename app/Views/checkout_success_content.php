<!-- app/Views/checkout_success_content.php -->
<div x-data="{ countdown: 3 }" x-init="setInterval(() => { countdown--; if(countdown === 0) window.location.href = '/profile?tab=orders'; }, 1000)" class="bg-white dark:bg-slate-950 min-h-screen flex items-center justify-center py-12 px-4 transition-colors duration-300">
    <div class="max-w-2xl w-full">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-10 md:p-16 text-center shadow-sm relative overflow-hidden transition-colors duration-300">
            
            <!-- Decorative Background Element -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-64 bg-green-500/10 blur-3xl rounded-full pointer-events-none"></div>

            <div class="relative z-10">
                <div class="w-24 h-24 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center mx-auto mb-8 shadow-[0_0_0_8px_rgba(34,197,94,0.1)] dark:shadow-[0_0_0_8px_rgba(34,197,94,0.05)] transition-colors duration-300">
                    <i class="ph ph-check-circle text-5xl"></i>
                </div>

                <h1 class="text-3xl md:text-4xl font-display font-semibold tracking-tight text-slate-900 dark:text-white mb-4 transition-colors duration-300">
                    Payment Successful!
                </h1>
                
                <p class="text-lg text-slate-600 dark:text-slate-400 mb-8 max-w-lg mx-auto leading-relaxed transition-colors duration-300">
                    Thank you for your purchase. Your payment has been securely processed and placed into our escrow system.
                    <?php if($order_id): ?>
                        <br><br>
                        <span class="inline-block bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-lg font-mono text-sm border border-slate-200 dark:border-slate-700">Order Reference: #<?= htmlspecialchars($order_id) ?></span>
                    <?php endif; ?>
                </p>

                <div class="bg-blue-50 dark:bg-blue-900/20 text-blue-800 dark:text-blue-400 p-4 rounded-lg inline-block font-medium animate-pulse mb-8">
                    Redirecting to Order Tracking in <span x-text="countdown"></span>...
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="/profile?tab=orders" class="w-full sm:w-auto bg-slate-900 dark:bg-blue-600 text-white rounded-xl font-medium px-8 py-4 hover:bg-slate-800 dark:hover:bg-blue-700 transition-all shadow-md active:scale-[0.98] flex items-center justify-center gap-2">
                        <i class="ph ph-package"></i>
                        Track Order Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
