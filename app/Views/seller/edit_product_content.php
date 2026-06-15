<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 text-dark dark:text-white">
    <div class="mb-8 flex items-center gap-4">
        <a href="/dashboard" class="text-zinc-400 hover:text-primary transition-colors p-2 rounded hover:bg-zinc-100 dark:hover:bg-zinc-800">
            <i class="ph ph-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="font-display text-3xl font-medium tracking-tight">Edit Product</h1>
            <p class="text-zinc-500 mt-1 text-sm">Update your listing details.</p>
        </div>
    </div>

    <form action="/seller/product/edit/<?= $product['id'] ?>" method="POST" enctype="multipart/form-data" class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded shadow-sm p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Product Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="<?= htmlspecialchars($product['title']) ?>" required class="w-full rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-dark dark:text-white focus:ring-primary focus:border-primary shadow-sm px-4 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-dark dark:text-white focus:ring-primary focus:border-primary shadow-sm px-4 py-2">
                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Price (R) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required class="w-full rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-dark dark:text-white focus:ring-primary focus:border-primary shadow-sm px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Stock Quantity</label>
                        <input type="number" name="stock_quantity" value="<?= $product['stock_quantity'] ?>" min="0" required class="w-full rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-dark dark:text-white focus:ring-primary focus:border-primary shadow-sm px-4 py-2">
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <input type="checkbox" name="is_on_sale" id="is_on_sale" <?= $product['is_on_sale'] ? 'checked' : '' ?> class="rounded border-zinc-300 text-primary focus:ring-primary w-5 h-5 bg-zinc-50 dark:bg-zinc-800 dark:border-zinc-600">
                    <label for="is_on_sale" class="text-sm font-medium text-zinc-700 dark:text-zinc-300 select-none cursor-pointer">
                        Mark this product as "On Sale"
                    </label>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Description</label>
                    <textarea name="description" rows="5" class="w-full rounded border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-dark dark:text-white focus:ring-primary focus:border-primary shadow-sm px-4 py-2"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">Product Image</label>
                    <?php if (!empty($product['image_url'])): ?>
                        <div class="mb-4 w-32 h-32 rounded bg-zinc-100 overflow-hidden border border-zinc-200 dark:border-zinc-700">
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    
                    <div class="border-2 border-dashed border-zinc-300 dark:border-zinc-700 rounded p-6 text-center hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors cursor-pointer" onclick="document.getElementById('image_upload').click()">
                        <i class="ph ph-image text-4xl text-zinc-400 mb-2"></i>
                        <p class="text-sm text-zinc-500">Click to upload a new image (replaces old)</p>
                        <p class="text-xs text-zinc-400 mt-1">PNG, JPG, WEBP up to 2MB</p>
                        <input type="file" name="image" id="image_upload" accept="image/*" class="hidden">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-10 flex justify-end gap-4 border-t border-zinc-200 dark:border-zinc-800 pt-6">
            <a href="/dashboard" class="px-6 py-3 rounded text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors">Cancel</a>
            <button type="submit" class="bg-primary text-white px-8 py-3 rounded text-sm font-medium hover:bg-blue-700 transition-all duration-300 shadow-lg shadow-primary/25 active:scale-95 inline-flex items-center gap-2">
                <i class="ph ph-check"></i> Update Product
            </button>
        </div>
    </form>
</div>
