<div class="max-w-xl mx-auto mt-10 bg-white dark:bg-zinc-900 p-10 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-800">
    <div class="text-center mb-8">
        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph ph-storefront text-3xl"></i>
        </div>
        <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Start Selling on KasiBuy</h2>
        <p class="text-zinc-500 dark:text-zinc-400">Set up your storefront and reach thousands of buyers today.</p>
    </div>
    
    <form action="/register-store" method="POST" class="space-y-6">
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-zinc-300 mb-2 flex items-center gap-2"><i class="ph ph-tag text-zinc-400"></i> Store Name</label>
            <input type="text" name="store_name" required placeholder="e.g. Mpho's Tech Hub" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-slate-900 dark:text-white rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-zinc-400 dark:placeholder-zinc-600">
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-zinc-300 mb-2 flex items-center gap-2"><i class="ph ph-map-pin text-zinc-400"></i> Location</label>
            <input type="text" name="location" required placeholder="e.g. Soweto, GP" class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-slate-900 dark:text-white rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-zinc-400 dark:placeholder-zinc-600">
        </div>
        
        <div>
            <label class="block text-sm font-semibold text-slate-700 dark:text-zinc-300 mb-2 flex items-center gap-2"><i class="ph ph-text-align-left text-zinc-400"></i> Store Description</label>
            <textarea name="store_description" rows="4" required placeholder="Tell buyers what your store is all about..." class="w-full border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-slate-900 dark:text-white rounded-lg shadow-sm py-2.5 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors placeholder-zinc-400 dark:placeholder-zinc-600"></textarea>
        </div>
        
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition-colors flex items-center justify-center gap-2 text-lg mt-4">
            Create Store <i class="ph ph-arrow-right"></i>
        </button>
    </form>
</div>
