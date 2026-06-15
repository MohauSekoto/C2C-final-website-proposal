<h1 class="text-3xl font-bold mb-6">Raw Database Explorer</h1>
<p class="text-gray-600 mb-8">Select a table to view, edit, or delete its records directly.</p>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach($tables as $table): ?>
    <a href="/admin/database/table/<?= urlencode($table) ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow border-l-4 border-blue-500 block">
        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?= htmlspecialchars($table) ?></h3>
        <p class="text-sm text-gray-500">Manage records in the <?= htmlspecialchars($table) ?> table.</p>
    </a>
    <?php endforeach; ?>
</div>
