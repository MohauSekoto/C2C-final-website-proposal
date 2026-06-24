<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-center">
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg p-12 shadow-sm animate-[scale-in_0.3s_ease-out]">
        <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="ph ph-check-circle text-5xl"></i>
        </div>
        <h2 class="text-3xl font-display font-medium text-zinc-900 dark:text-white mb-4">Action Successful</h2>
        <p class="text-zinc-500 dark:text-zinc-400 mb-8"><?= htmlspecialchars($message ?? 'Database updated successfully.') ?></p>
        
        <div class="flex items-center justify-center gap-2 text-sm text-zinc-500">
            <i class="ph ph-spinner animate-spin"></i>
            <span>Redirecting back to dashboard...</span>
        </div>
    </div>
</div>

<script>
    setTimeout(() => {
        window.location.href = '/dashboard';
    }, 2000);
</script>
