<div class="w-full max-w-md">
    <div id="offline-banner" style="display: none;" class="fixed top-0 left-0 w-full bg-red-600 text-white text-center py-2.5 z-50 font-bold text-sm shadow-md">
        ⚠️ تۆ ئێستا لە دۆخی ئۆفلاین دایت! سیستەمەکە لۆکاڵی کار دەکات.
    </div>

    <div class="bg-white rounded-3xl shadow-2xl w-full p-8 md:p-10 border border-gray-100 position-relative">
        
        <div class="flex justify-center mb-6">
            <div id="db-status-badge" class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm border transition-all duration-300">
                <span id="db-status-dot" class="w-2.5 h-2.5 rounded-full animate-pulse"></span>
                <span id="db-status-text">پشکنینی داتابەیس...</span>
            </div>
        </div>
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4 shadow-inner">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            
            <h1 id="login-title" class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-l from-indigo-600 to-blue-500 mb-2">
                ماركێتی شارەکەم
            </h1>
            <p id="login-desc" class="text-sm text-gray-500 font-medium">بەخێربێیتەوە! تکایە بچۆ ژوورەوە بۆ بەڕێوەبردنی سیستەم</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border-r-4 border-red-500 text-red-700 px-4 py-3 rounded-lg text-sm mb-6 flex items-center shadow-sm">
            <svg class="w-5 h-5 ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>
                @if($errors->has('email')) 
                    {{ $errors->first('email') }} 
                @elseif($errors->has('password')) 
                    {{ $errors->first('password') }} 
                @endif
            </span>
        </div>
        @endif

        <form wire:submit.prevent="login" class="space-y-5">
            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-gray-700">ئیمەیل</label>
                <input type="email" id="email" wire:model="email" required autofocus
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none bg-gray-50 focus:bg-white placeholder-gray-400"
                       placeholder="ئیمەیلەکەت بنووسە...">
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-gray-700">وشەی نهێنی</label>
                <input type="password" id="password" wire:model="password" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none bg-gray-50 focus:bg-white placeholder-gray-400"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" wire:model="remember" 
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                    <span class="text-sm text-gray-600 group-hover:text-indigo-700 transition-colors">بمهێڵەوە چوونەژوورەوە</span>
                </label>
                
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                    وشەی نهێنیت لەبیرکردووە؟
                </a>
                @endif
            </div>

            <button type="submit" id="submit-btn" class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white rounded-xl py-3.5 text-sm font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 mt-2">
                چوونەژوورەوە
            </button>
        </form>
    </div>

    <script>
        let isReallyOnline = navigator.onLine;

        async function checkConnectivity() {
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 3000);
                const res = await fetch('/ping', { method: 'GET', cache: 'no-store', signal: controller.signal });
                clearTimeout(timeoutId);
                isReallyOnline = res.ok;
            } catch (e) {
                isReallyOnline = false;
            }
            updateConnectionStatus();
        }

        function updateConnectionStatus() {
            const banner = document.getElementById('offline-banner');
            const title = document.getElementById('login-title');
            const desc = document.getElementById('login-desc');
            const btn = document.getElementById('submit-btn');
            
            // گۆڕاوەکانی باجەکەی داتابەیس
            const dbBadge = document.getElementById('db-status-badge');
            const dbDot = document.getElementById('db-status-dot');
            const dbText = document.getElementById('db-status-text');

            if (isReallyOnline) {
                // دۆخی ئۆنلاین (MySQL)
                banner.style.display = 'none';
                title.innerText = "ماركێتی شارەکەم";
                title.className = "text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-l from-indigo-600 to-blue-500 mb-2";
                desc.innerText = "بەخێربێیتەوە! تکایە بچۆ ژوورەوە بۆ بەڕێوەبردنی سیستەم";
                btn.className = "w-full bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white rounded-xl py-3.5 text-sm font-bold shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 mt-2";
                btn.innerText = "چوونەژوورەوە";

                // شێوازی باج بۆ MySQL
                dbBadge.className = "inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm border border-green-200 bg-green-50 text-green-700";
                dbDot.className = "w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse";
                dbText.innerText = "داتابەیس: MySQL (سەرەکی)";
            } else {
                // دۆخی ئۆفلاین (SQLite)
                banner.style.display = 'block';
                title.innerText = "چوونەژوورەوە بۆ Offline";
                title.className = "text-3xl font-bold text-red-600 mb-2";
                desc.innerText = "تەنها ئەو بەکارهێنەرانەی مۆڵەتنراون دەتوانن بچنە ژوورەوە";
                btn.className = "w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl py-3.5 text-sm font-bold shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 mt-2";
                btn.innerText = "چوونەژوورەوە (ئۆفلاین)";

                // شێوازی باج بۆ SQLite
                dbBadge.className = "inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold shadow-sm border border-amber-200 bg-amber-50 text-amber-700";
                dbDot.className = "w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse";
                dbText.innerText = "داتابەیس: SQLite (ناوەخۆیی)";
            }
        }

        window.addEventListener('online', updateConnectionStatus);
        window.addEventListener('offline', updateConnectionStatus);
        document.addEventListener("DOMContentLoaded", checkConnectivity);
        setInterval(checkConnectivity, 6000);
    </script>
</div>