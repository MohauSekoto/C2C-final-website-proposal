<!-- app/Views/login.php -->
<div class="min-h-[80vh] bg-slate-50 dark:bg-slate-950 flex flex-col justify-center py-12 sm:px-6 lg:px-8 transition-colors duration-300" x-data="{ mounted: false }" x-init="setTimeout(() => mounted = true, 100)">
    <div class="sm:mx-auto sm:w-full sm:max-w-md transform transition-all duration-700 ease-out" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
        <h1 class="mt-6 text-center text-3xl font-semibold tracking-tight text-slate-900 dark:text-white transition-colors duration-300">
            Sign in to <span class="text-slate-900 dark:text-white transition-colors duration-300">Kasi</span><span class="text-yellow-500">Buy</span>
        </h1>
        <p class="mt-3 text-center text-sm text-slate-500 dark:text-slate-400 transition-colors duration-300">
            New to the platform?
            <a href="/register" class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-1">
                Create an account
            </a>
        </p>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md transform transition-all duration-700 delay-150 ease-out" :class="mounted ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
        <div class="bg-white dark:bg-slate-900 py-10 px-4 sm:rounded-lg sm:px-10 border border-slate-200 dark:border-slate-800 shadow-sm transition-shadow duration-300">
            
            <?php if (isset($error) && $error): ?>
                <div class="mb-6 bg-red-50/50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30 rounded-xl p-4 flex items-start gap-3" role="alert">
                    <svg class="h-5 w-5 text-red-500 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-sm text-red-800 dark:text-red-400 font-medium leading-relaxed"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <form class="space-y-5" action="/login" method="POST">
                <!-- CSRF Token for Security -->
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors duration-300">
                        Email address
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required aria-required="true" class="appearance-none block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg placeholder-slate-400 dark:placeholder-slate-500 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-blue-500 focus:border-transparent sm:text-sm transition-all duration-200">
                </div>

                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 transition-colors duration-300">
                            Password
                        </label>
                        <a href="#" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 rounded px-1">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <input id="password" name="password" :type="show ? 'text' : 'password'" autocomplete="current-password" required aria-required="true" class="appearance-none block w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg placeholder-slate-400 dark:placeholder-slate-500 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900 dark:focus:ring-blue-500 focus:border-transparent sm:text-sm transition-all duration-200 pr-10">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 focus:outline-none" aria-label="Toggle password visibility">
                            <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center pt-1">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-slate-900 dark:text-blue-500 dark:bg-slate-800 focus:ring-slate-900 dark:focus:ring-blue-500 border-slate-300 dark:border-slate-600 rounded cursor-pointer transition-colors">
                    <label for="remember-me" class="ml-2.5 block text-sm text-slate-600 dark:text-slate-400 cursor-pointer select-none transition-colors duration-300">
                        Remember me for 30 days
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 rounded-md shadow-sm text-sm font-medium text-white bg-slate-900 dark:bg-blue-600 hover:bg-slate-800 dark:hover:bg-blue-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 dark:focus:ring-blue-500 dark:focus:ring-offset-slate-900 transition-colors duration-200">
                        Sign in
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800 transition-colors duration-300">
                <a href="/admin/login" class="group flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-lg text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7z"></path></svg>
                    Sign in as Administrator
                </a>
            </div>
        </div>
    </div>
</div>
