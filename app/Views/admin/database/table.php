<div class="flex justify-between items-center mb-6">
    <div>
        <div class="mb-2">
            <a href="/admin/database" class="text-zinc-500 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 text-sm font-medium flex items-center gap-1 transition-colors"><i class="ph ph-arrow-left"></i> Back to Tables</a>
        </div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-3"><i class="ph ph-table text-blue-500"></i> <?= htmlspecialchars($table) ?></h1>
    </div>
    <a href="/admin/database/form/<?= urlencode($table) ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm font-medium transition-colors flex items-center gap-2"><i class="ph ph-plus-circle text-lg"></i> Insert Record</a>
</div>

<div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800">
            <thead class="bg-zinc-50 dark:bg-zinc-800/50">
                <tr>
                    <?php foreach($columns as $col): ?>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                            <?= htmlspecialchars($col['Field']) ?>
                        </th>
                    <?php endforeach; ?>
                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 bg-white dark:bg-zinc-900">
                <?php 
                    // Find Primary Key field
                    $pkField = 'id';
                    foreach($columns as $col) {
                        if($col['Key'] === 'PRI') {
                            $pkField = $col['Field'];
                            break;
                        }
                    }
                ?>
                <?php foreach($records as $row): ?>
                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors group">
                    <?php foreach($columns as $col): 
                        $val = $row[$col['Field']];
                        $displayVal = $val;
                        if (strlen($displayVal ?? '') > 50) $displayVal = substr($displayVal, 0, 50) . '...';
                    ?>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-600 dark:text-zinc-300" title="<?= htmlspecialchars($val ?? 'NULL') ?>">
                            <?= $val === null ? '<em class="text-zinc-400 dark:text-zinc-500 italic">NULL</em>' : htmlspecialchars($displayVal) ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <?php $pkValue = $row[$pkField] ?? null; ?>
                        <?php if($pkValue): ?>
                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="/admin/database/form/<?= urlencode($table) ?>/<?= urlencode($pkValue) ?>" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors flex items-center gap-1" title="Edit"><i class="ph ph-pencil-simple text-lg"></i></a>
                                
                                <form action="/admin/database/delete/<?= urlencode($table) ?>" method="POST" class="inline m-0" onsubmit="return confirm('Are you sure you want to permanently delete this record?');">
                                    <input type="hidden" name="_pk_field" value="<?= htmlspecialchars($pkField) ?>">
                                    <input type="hidden" name="_pk_value" value="<?= htmlspecialchars($pkValue) ?>">
                                    <button type="submit" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 transition-colors flex items-center gap-1 focus:outline-none" title="Delete"><i class="ph ph-trash text-lg"></i></button>
                                </form>
                            </div>
                        <?php else: ?>
                            <span class="text-zinc-400 dark:text-zinc-600 text-xs">No PK</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($records)): ?>
                <tr>
                    <td colspan="<?= count($columns) + 1 ?>" class="px-6 py-8 text-center text-zinc-500 dark:text-zinc-400">
                        <i class="ph ph-database text-4xl mb-2 text-zinc-300 dark:text-zinc-700 block"></i>
                        No records found in this table.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if($total_pages > 1): ?>
    <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
        <p class="text-sm text-zinc-600 dark:text-zinc-400">
            Showing page <span class="font-semibold text-slate-900 dark:text-white"><?= $page ?></span> of <span class="font-semibold text-slate-900 dark:text-white"><?= $total_pages ?></span>
        </p>
        <div class="flex gap-2">
            <?php if($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="px-3 py-1.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-sm font-medium transition-colors flex items-center gap-1"><i class="ph ph-caret-left"></i> Prev</a>
            <?php endif; ?>
            
            <?php if($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>" class="px-3 py-1.5 rounded bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-sm font-medium transition-colors flex items-center gap-1">Next <i class="ph ph-caret-right"></i></a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
