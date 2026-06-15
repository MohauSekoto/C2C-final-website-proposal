<!-- app/Views/product_detail_content.php -->
<div class="bg-white dark:bg-slate-950 min-h-screen py-12 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex text-sm text-slate-500 dark:text-slate-400 mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="ph ph-caret-right text-xs mx-1"></i>
                        <a href="/products" class="hover:text-slate-900 dark:hover:text-white transition-colors">Products</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="ph ph-caret-right text-xs mx-1"></i>
                        <span class="text-slate-900 dark:text-white font-medium"><?= htmlspecialchars($product['title']) ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col lg:flex-row transition-colors duration-300">
            
            <!-- Left Column: Image -->
            <div class="lg:w-1/2 bg-slate-100 dark:bg-slate-800 relative flex items-center justify-center min-h-[400px] lg:min-h-[600px] p-8 lg:p-12 transition-colors duration-300">
                <?php if (!empty($product['image_url'])): ?>
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="object-contain w-full h-full max-h-[500px] rounded drop-shadow-xl transition-transform hover:scale-105 duration-700 ease-out">
                <?php else: ?>
                    <div class="text-slate-400 dark:text-slate-500 flex flex-col items-center">
                        <i class="ph ph-image text-6xl mb-4 opacity-50"></i>
                        <span class="font-medium tracking-wide">No Image Available</span>
                    </div>
                <?php endif; ?>

                <!-- Badges -->
                <div class="absolute top-6 left-6 flex flex-col gap-2">
                    <?php if($product['stock_quantity'] <= 0): ?>
                        <span class="bg-red-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm tracking-widest uppercase">Out of Stock</span>
                    <?php elseif($product['is_on_sale']): ?>
                        <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm tracking-widest uppercase">Sale</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="lg:w-1/2 p-8 lg:p-12 flex flex-col">
                <div class="mb-6 border-b border-slate-100 dark:border-slate-800 pb-6 transition-colors duration-300">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 tracking-widest uppercase px-2.5 py-1 bg-blue-50 dark:bg-blue-900/20 rounded-md transition-colors duration-300">
                            <?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?>
                        </span>
                        
                        <div class="flex items-center text-sm text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800 px-3 py-1 rounded-md transition-colors duration-300">
                            <i class="ph ph-storefront text-base mr-1.5"></i>
                            <span><?= htmlspecialchars($product['seller_name'] ?? 'Unknown Seller') ?></span>
                        </div>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-semibold tracking-tight text-slate-900 dark:text-white mb-4 transition-colors duration-300 leading-tight">
                        <?= htmlspecialchars($product['title']) ?>
                    </h1>
                    
                    <?php if(isset($product['avg_rating']) && $product['review_count'] > 0): ?>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex text-yellow-400">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="ph <?= $i <= round($product['avg_rating']) ? 'ph-star-fill' : 'ph-star' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="text-sm text-slate-500 dark:text-slate-400">(<?= number_format($product['avg_rating'], 1) ?> / 5 from <?= $product['review_count'] ?> reviews)</span>
                    </div>
                    <?php endif; ?>
                    
                    <div class="flex items-baseline gap-4">
                        <span class="text-4xl font-light text-slate-900 dark:text-white transition-colors duration-300">
                            R <?= number_format($product['price'], 2) ?>
                        </span>
                    </div>
                </div>
                
                <div class="prose prose-slate dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 mb-10 leading-relaxed transition-colors duration-300">
                    <p><?= nl2br(htmlspecialchars($product['description'] ?? 'No description available.')) ?></p>
                </div>
                
                <!-- Action Area -->
                <div class="mt-auto bg-slate-50 dark:bg-slate-800/50 p-6 rounded-xl border border-slate-100 dark:border-slate-700/50 transition-colors duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors duration-300">Availability</span>
                        <?php if($product['stock_quantity'] > 0): ?>
                            <span class="flex items-center text-sm font-medium text-emerald-600 dark:text-emerald-400 transition-colors duration-300">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                                <?= $product['stock_quantity'] ?> in stock
                            </span>
                        <?php else: ?>
                            <span class="flex items-center text-sm font-medium text-red-600 dark:text-red-400 transition-colors duration-300">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                Out of stock
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-6">
                        <!-- Add to Cart Form -->
                        <form action="/cart/add" method="POST" class="flex-grow flex gap-4">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="title" value="<?= htmlspecialchars($product['title']) ?>">
                            <input type="hidden" name="price" value="<?= $product['price'] ?>">
                            
                            <div class="w-24 relative flex items-center">
                                <label for="quantity" class="sr-only">Quantity</label>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?? 1 ?>" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?> class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-center font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all py-3 shadow-sm disabled:opacity-50">
                            </div>
                            
                            <button type="submit" <?= $product['stock_quantity'] <= 0 ? 'disabled' : '' ?> class="flex-grow flex items-center justify-center gap-2 bg-slate-900 dark:bg-blue-600 text-white px-8 py-3 rounded-lg font-medium hover:bg-slate-800 dark:hover:bg-blue-700 transition-all active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:active:scale-100 shadow-md">
                                <i class="ph ph-shopping-cart-simple text-xl"></i>
                                Add to Cart
                            </button>
                        </form>

                        <!-- Wishlist Form -->
                        <?php if(isset($_SESSION['user_id'])): ?>
                        <form action="/wishlist/toggle" method="POST" class="flex-shrink-0">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="redirect" value="/product/<?= $product['id'] ?>">
                            <button type="submit" aria-label="Toggle wishlist" class="flex items-center justify-center w-12 h-12 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-red-500 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-200 dark:hover:border-red-800 transition-colors active:scale-95 shadow-sm">
                                <i class="ph ph-heart text-2xl"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="mt-12 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 lg:p-12 transition-colors duration-300">
            <h2 class="text-2xl font-semibold text-slate-900 dark:text-white mb-8 tracking-tight">Customer Reviews</h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Reviews List -->
                <div class="lg:col-span-2 space-y-8">
                    <?php if(!empty($reviews)): ?>
                        <?php foreach($reviews as $review): ?>
                            <div class="border-b border-slate-100 dark:border-slate-800 pb-8 last:border-0 last:pb-0">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="font-medium text-slate-900 dark:text-white"><?= htmlspecialchars($review['reviewer_name']) ?></span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400"><?= date('M j, Y', strtotime($review['created_at'])) ?></span>
                                </div>
                                <div class="flex text-yellow-400 mb-3">
                                    <?php for($i=1; $i<=5; $i++): ?>
                                        <i class="ph <?= $i <= $review['rating'] ? 'ph-star-fill' : 'ph-star' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-slate-600 dark:text-slate-400 leading-relaxed"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                            <i class="ph ph-chat-circle-dots text-4xl mb-3 opacity-50"></i>
                            <p>No reviews yet. Be the first to review this product!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Write a Review Form -->
                <div class="lg:col-span-1">
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-6 border border-slate-100 dark:border-slate-700/50">
                        <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-4">Write a Review</h3>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <form action="/product/<?= $product['id'] ?>/review" method="POST" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Rating</label>
                                    <select name="rating" required class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                                        <option value="5">5 Stars - Excellent</option>
                                        <option value="4">4 Stars - Good</option>
                                        <option value="3">3 Stars - Average</option>
                                        <option value="2">2 Stars - Poor</option>
                                        <option value="1">1 Star - Terrible</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Comment</label>
                                    <textarea name="comment" rows="4" required class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg px-4 py-2.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-400" placeholder="Share your thoughts..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-blue-600 text-white font-medium py-2.5 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">Submit Review</button>
                            </form>
                        <?php else: ?>
                            <div class="text-sm text-slate-500 dark:text-slate-400">
                                Please <a href="/login" class="text-blue-600 dark:text-blue-400 font-medium hover:underline">log in</a> to write a review.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
