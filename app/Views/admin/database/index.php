<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Raw Database Explorer</h1>
    <p class="text-zinc-500 dark:text-zinc-400">Select a table to view, edit, or delete its records directly.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($tables as $table): ?>
    <a href="/admin/database/table/<?= urlencode($table) ?>" class="bg-white dark:bg-zinc-900 rounded shadow-sm border border-zinc-200 dark:border-zinc-800 p-5 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors block">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 flex items-center justify-center text-zinc-500 dark:text-zinc-400">
                <i class="ph ph-database text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white m-0"><?= htmlspecialchars($table) ?></h3>
        </div>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 m-0">Manage records in the <span class="font-medium text-slate-700 dark:text-zinc-300"><?= htmlspecialchars($table) ?></span> table.</p>
    </a>
    <?php endforeach; ?>
</div>
