<div class="mb-8">
    <h1 class="text-3xl font-display font-bold text-zinc-900 dark:text-white mb-2">Manage Users</h1>
    <p class="text-zinc-500 dark:text-zinc-400">View and manage all registered accounts on the platform.</p>
</div>

<div class="bg-white dark:bg-darkCard rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-zinc-50 dark:bg-zinc-800/30 text-xs uppercase tracking-wider text-zinc-500 dark:text-zinc-400 border-b border-zinc-200 dark:border-zinc-800">
                    <th scope="col" class="px-6 py-4 font-medium">ID</th>
                    <th scope="col" class="px-6 py-4 font-medium">User Info</th>
                    <th scope="col" class="px-6 py-4 font-medium">Role</th>
                    <th scope="col" class="px-6 py-4 font-medium">Joined</th>
                    <th scope="col" class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                <?php foreach($users as $user): ?>
                <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/20 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400 font-mono" title="<?= htmlspecialchars($user['id']) ?>">
                        <?= substr($user['id'], 0, 8) ?>&hellip;
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="font-medium text-zinc-900 dark:text-zinc-200 flex items-center gap-2">
                            <?= htmlspecialchars($user['name']) ?>
                        </div>
                        <div class="text-zinc-500 dark:text-zinc-400 text-xs mt-0.5"><?= htmlspecialchars($user['email']) ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <?php if($user['role'] === 'admin'): ?>
                            <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-md bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">Admin</span>
                        <?php elseif($user['role'] === 'seller'): ?>
                            <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-md bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">Seller</span>
                        <?php elseif($user['role'] === 'banned'): ?>
                            <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-md bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Banned</span>
                        <?php else: ?>
                            <span class="px-2.5 py-1 inline-flex text-xs font-medium rounded-md bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300">Buyer</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-zinc-500 dark:text-zinc-400">
                        <?= date('Y-m-d', strtotime($user['created_at'])) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <?php if ($user['role'] !== 'admin' && $user['role'] !== 'banned'): ?>
                            <form action="/admin/users/ban" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to ban this user?');">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 focus:outline-none transition-colors p-1.5 hover:bg-red-50 dark:hover:bg-red-900/20 rounded font-medium flex items-center gap-1.5 ml-auto" title="Ban User">
                                    <i class="ph ph-prohibit text-lg"></i>
                                    Ban
                                </button>
                            </form>
                        <?php elseif ($user['role'] === 'banned'): ?>
                            <span class="text-zinc-400 dark:text-zinc-500 italic text-xs">Banned</span>
                        <?php else: ?>
                            <span class="text-zinc-400 dark:text-zinc-500 italic text-xs">&mdash;</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($users)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-zinc-500 dark:text-zinc-400">
                        <i class="ph ph-users text-4xl mb-3 opacity-50"></i>
                        <p>No users found.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
