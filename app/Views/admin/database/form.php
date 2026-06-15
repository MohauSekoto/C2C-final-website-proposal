<div class="mb-6">
    <a href="/admin/database/table/<?= urlencode($table) ?>" class="text-zinc-500 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-blue-400 text-sm font-medium flex items-center gap-1 transition-colors w-fit"><i class="ph ph-arrow-left"></i> Back to <?= htmlspecialchars($table) ?></a>
</div>

<div class="bg-white dark:bg-zinc-900 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-800 max-w-4xl mx-auto overflow-hidden">
    <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 flex items-center gap-3">
        <i class="ph ph-<?= $record ? 'pencil-simple' : 'plus-circle' ?> text-2xl text-blue-500"></i>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white"><?= $record ? 'Edit Record' : 'Insert Record' ?> <span class="text-zinc-400 dark:text-zinc-500 font-normal text-lg">(<?= htmlspecialchars($table) ?>)</span></h2>
    </div>
    
    <div class="p-6">
        <form action="/admin/database/save/<?= urlencode($table) ?>" method="POST" class="space-y-6">
            <?php 
                $pkField = 'id';
                $pkValue = null;
            ?>
            
            <div class="grid grid-cols-1 gap-6">
                <?php foreach($columns as $col): 
                    $field = $col['Field'];
                    $type = $col['Type'];
                    $isPk = $col['Key'] === 'PRI';
                    if ($isPk) {
                        $pkField = $field;
                        $pkValue = $record ? $record[$field] : null;
                    }
                    $value = $record ? $record[$field] : ($col['Default'] ?? '');
                    
                    // Determine input type
                    $inputType = 'text';
                    if (strpos($type, 'int') !== false || strpos($type, 'decimal') !== false) $inputType = 'number';
                    if (strpos($type, 'date') !== false || strpos($type, 'timestamp') !== false) $inputType = 'text'; // keep as text to allow raw SQL like CURRENT_TIMESTAMP or easy editing
                    
                    $isEnum = strpos($type, 'enum') !== false;
                ?>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-zinc-300 mb-1.5 flex items-center gap-2">
                        <?= htmlspecialchars($field) ?> 
                        <span class="text-xs text-zinc-400 font-normal bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded"><?= htmlspecialchars($type) ?></span>
                        <?php if($isPk): ?> <span class="text-xs text-blue-500 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-1.5 py-0.5 rounded border border-blue-100 dark:border-blue-800">Primary Key</span> <?php endif; ?>
                    </label>
                    
                    <?php if($isPk && $record): ?>
                        <!-- Primary key usually shouldn't be edited once set, but we allow it as hidden -->
                        <input type="text" name="data[<?= htmlspecialchars($field) ?>]" value="<?= htmlspecialchars($value) ?>" class="mt-1 block w-full border border-zinc-200 dark:border-zinc-800 rounded-lg shadow-sm py-2 px-3 bg-zinc-50 dark:bg-zinc-800/50 text-zinc-500 dark:text-zinc-400 cursor-not-allowed focus:outline-none" readonly>
                    <?php elseif($isEnum): 
                        // Extract enum values
                        preg_match('/enum\((.*)\)/', $type, $matches);
                        $enumValues = [];
                        if(isset($matches[1])) {
                            $enumValues = str_getcsv(str_replace("'", "", $matches[1]));
                        }
                    ?>
                        <select name="data[<?= htmlspecialchars($field) ?>]" class="mt-1 block w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-slate-900 dark:text-white rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors">
                            <option value="">-- NULL --</option>
                            <?php foreach($enumValues as $enumVal): ?>
                                <option value="<?= htmlspecialchars($enumVal) ?>" <?= $value === $enumVal ? 'selected' : '' ?>><?= htmlspecialchars($enumVal) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif(strpos($type, 'text') !== false || strpos($type, 'json') !== false): ?>
                        <textarea name="data[<?= htmlspecialchars($field) ?>]" rows="4" class="mt-1 block w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-slate-900 dark:text-white rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors"><?= htmlspecialchars($value ?? '') ?></textarea>
                    <?php else: ?>
                        <input type="<?= $inputType ?>" <?= $inputType === 'number' ? 'step="any"' : '' ?> name="data[<?= htmlspecialchars($field) ?>]" value="<?= htmlspecialchars($value ?? '') ?>" class="mt-1 block w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-slate-900 dark:text-white rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors placeholder-zinc-400 dark:placeholder-zinc-600">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            
            <input type="hidden" name="_pk_field" value="<?= htmlspecialchars($pkField) ?>">
            <?php if($record): ?>
            <input type="hidden" name="_pk_value" value="<?= htmlspecialchars($pkValue) ?>">
            <?php endif; ?>
            
            <div class="pt-6 border-t border-zinc-200 dark:border-zinc-800 flex justify-end gap-3 mt-8">
                <a href="/admin/database/table/<?= urlencode($table) ?>" class="bg-white dark:bg-zinc-800 py-2 px-4 border border-zinc-300 dark:border-zinc-700 rounded-lg shadow-sm text-sm font-medium text-slate-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-zinc-900">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center items-center gap-2 py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-zinc-900">
                    <i class="ph ph-floppy-disk text-lg"></i> Save Record
                </button>
            </div>
        </form>
    </div>
</div>
