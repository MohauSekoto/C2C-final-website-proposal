<div class="mb-6">
    <a href="/admin/database/table/<?= urlencode($table) ?>" class="text-blue-600 hover:underline">&larr; Back to <?= htmlspecialchars($table) ?></a>
</div>

<div class="bg-white rounded-lg shadow max-w-4xl mx-auto overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h2 class="text-2xl font-bold"><?= $record ? 'Edit Record' : 'Insert Record' ?> (<?= htmlspecialchars($table) ?>)</h2>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <?= htmlspecialchars($field) ?> 
                        <span class="text-xs text-gray-400 font-normal">(<?= htmlspecialchars($type) ?>)</span>
                        <?php if($isPk): ?> <span class="text-xs text-red-500">Primary Key</span> <?php endif; ?>
                    </label>
                    
                    <?php if($isPk && $record): ?>
                        <!-- Primary key usually shouldn't be edited once set, but we allow it as hidden -->
                        <input type="text" name="data[<?= htmlspecialchars($field) ?>]" value="<?= htmlspecialchars($value) ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 text-gray-500 cursor-not-allowed" readonly>
                    <?php elseif($isEnum): 
                        // Extract enum values
                        preg_match('/enum\((.*)\)/', $type, $matches);
                        $enumValues = [];
                        if(isset($matches[1])) {
                            $enumValues = str_getcsv(str_replace("'", "", $matches[1]));
                        }
                    ?>
                        <select name="data[<?= htmlspecialchars($field) ?>]" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">-- NULL --</option>
                            <?php foreach($enumValues as $enumVal): ?>
                                <option value="<?= htmlspecialchars($enumVal) ?>" <?= $value === $enumVal ? 'selected' : '' ?>><?= htmlspecialchars($enumVal) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php elseif(strpos($type, 'text') !== false || strpos($type, 'json') !== false): ?>
                        <textarea name="data[<?= htmlspecialchars($field) ?>]" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"><?= htmlspecialchars($value ?? '') ?></textarea>
                    <?php else: ?>
                        <input type="<?= $inputType ?>" <?= $inputType === 'number' ? 'step="any"' : '' ?> name="data[<?= htmlspecialchars($field) ?>]" value="<?= htmlspecialchars($value ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            
            <input type="hidden" name="_pk_field" value="<?= htmlspecialchars($pkField) ?>">
            <?php if($record): ?>
            <input type="hidden" name="_pk_value" value="<?= htmlspecialchars($pkValue) ?>">
            <?php endif; ?>
            
            <div class="pt-5 border-t border-gray-200 flex justify-end">
                <a href="/admin/database/table/<?= urlencode($table) ?>" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Record
                </button>
            </div>
        </form>
    </div>
</div>
