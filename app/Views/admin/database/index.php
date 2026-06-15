<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Raw Database Explorer</h1>
    <p class="text-zinc-500 dark:text-zinc-400">Select a table to view, edit, or delete its records directly.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($tables as $table): ?>
    <a href="/admin/database/table/<?= urlencode($table) ?>" class="group bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 p-6 hover:shadow-md hover:border-blue-500/50 transition-all duration-300 block relative overflow-hidden">
        <!-- Accent line -->
        <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500 transition-transform origin-bottom scale-y-0 group-hover:scale-y-100 duration-300"></div>
        
        <div class="flex items-center gap-4 mb-3">
            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                <i class="ph ph-database text-xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($table) ?></h3>
        </div>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 group-hover:text-zinc-600 dark:group-hover:text-zinc-300 transition-colors">Manage records in the <span class="font-medium text-slate-700 dark:text-zinc-300"><?= htmlspecialchars($table) ?></span> table.</p>
    </a>
    <?php endforeach; ?>
</div>
