<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Table: <?= htmlspecialchars($table) ?></h1>
    <a href="/admin/database/form/<?= urlencode($table) ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">+ Insert Record</a>
</div>

<div class="mb-4">
    <a href="/admin/database" class="text-blue-600 hover:underline">&larr; Back to Tables</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <?php foreach($columns as $col): ?>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <?= htmlspecialchars($col['Field']) ?>
                        </th>
                    <?php endforeach; ?>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
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
                <tr class="hover:bg-gray-50">
                    <?php foreach($columns as $col): 
                        $val = $row[$col['Field']];
                        $displayVal = $val;
                        if (strlen($displayVal ?? '') > 50) $displayVal = substr($displayVal, 0, 50) . '...';
                    ?>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" title="<?= htmlspecialchars($val ?? 'NULL') ?>">
                            <?= $val === null ? '<em class="text-gray-300">NULL</em>' : htmlspecialchars($displayVal) ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <?php $pkValue = $row[$pkField] ?? null; ?>
                        <?php if($pkValue): ?>
                            <a href="/admin/database/form/<?= urlencode($table) ?>/<?= urlencode($pkValue) ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            
                            <form action="/admin/database/delete/<?= urlencode($table) ?>" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this record?');">
                                <input type="hidden" name="_pk_field" value="<?= htmlspecialchars($pkField) ?>">
                                <input type="hidden" name="_pk_value" value="<?= htmlspecialchars($pkValue) ?>">
                                <button type="submit" class="text-red-600 hover:text-red-900 focus:outline-none">Delete</button>
                            </form>
                        <?php else: ?>
                            <span class="text-gray-400">No PK</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($records)): ?>
                <tr>
                    <td colspan="<?= count($columns) + 1 ?>" class="px-6 py-4 text-center text-gray-500">No records found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if($total_pages > 1): ?>
    <div class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing page <span class="font-medium"><?= $page ?></span> of <span class="font-medium"><?= $total_pages ?></span>
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <?php if($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">Previous</a>
                    <?php endif; ?>
                    
                    <?php if($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">Next</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
