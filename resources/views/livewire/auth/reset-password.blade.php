<div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 md:p-10 border border-gray-100 z-10">
    
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4 shadow-inner">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-5.618 2.04M12 21a9.003 9.003 0 008.313-12.455M12 21a9.003 9.003 0 01-8.313-12.455"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">وشەی نهێنی نوێ دابنێ</h1>
        <p class="text-sm text-gray-500 font-medium px-4">تکایە زانیارییەکان پڕبکەرەوە بۆ نوێکردنەوەی پاسۆردەکەت.</p>
    </div>

    <form wire:submit.prevent="resetPassword" class="space-y-5">
        
        <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700 text-right">ئیمەیل</label>
            <input type="email" wire:model="email" class="w-full border @error('email') border-red-500 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm outline-none bg-gray-50 focus:bg-white text-right" placeholder="ئیمەیلەکەت بنووسە...">
            @error('email') <span class="text-red-500 text-xs font-medium block mt-1 text-right">{{ $message }}</span> @enderror
        </div>

        <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700 text-right">وشەی نهێنی نوێ</label>
            <input type="password" wire:model="password" class="w-full border @error('password') border-red-500 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm outline-none bg-gray-50 focus:bg-white text-right" placeholder="••••••••">
            @error('password') <span class="text-red-500 text-xs font-medium block mt-1 text-right">{{ $message }}</span> @enderror
        </div>

        <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700 text-right">دووبارەکردنەوەی وشەی نهێنی</label>
            <input type="password" wire:model="password_confirmation" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none bg-gray-50 focus:bg-white text-right" placeholder="••••••••">
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white rounded-xl py-3.5 text-sm font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 mt-2 flex items-center justify-center">
            <span wire:loading.remove>تۆمارکردنی وشەی نهێنی نوێ</span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                چاوەڕێ بکە...
            </span>
        </button>
    </form>
</div>