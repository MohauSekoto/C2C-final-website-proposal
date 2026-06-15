<div class="max-w-md mx-auto mt-10 bg-white dark:bg-darkCard p-8 rounded-lg shadow-lg border border-gray-200 dark:border-gray-800">
    <h2 class="text-2xl font-bold mb-6 text-center">Create an Account</h2>
    
    <?php if(isset($error) && $error): ?>
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-500 text-red-600 dark:text-red-400 p-3 rounded mb-6 text-sm">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="/register" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-1">Full Name</label>
            <input type="text" name="name" required minlength="2" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" required class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Password</label>
            <input type="password" name="password" required minlength="8" class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded focus:ring-2 focus:ring-blue-500 outline-none">
            <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters long.</p>
        </div>
        <button type="submit" class="w-full bg-primary text-white py-2 rounded hover:bg-blue-600 shadow-sm transition-colors font-medium mt-4">Register</button>
    </form>
</div>
