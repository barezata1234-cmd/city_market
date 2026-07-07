<div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 md:p-10 border border-gray-100 z-10">
    
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4 shadow-inner">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">وشەی نهێنیت لەبیرکردووە؟</h1>
        <p class="text-sm text-gray-500 font-medium px-4">تەنها ئیمەیلەکەت بنووسە و ئێمە بەستەری گۆڕینی وشەی نهێنیت بۆ دەنێرین.</p>
    </div>

    @if ($status)
        <div class="bg-green-50 border-r-4 border-green-500 text-green-700 px-4 py-3 rounded-lg text-sm mb-6 flex items-center shadow-sm">
            <svg class="w-5 h-5 ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ $status }}</span>
        </div>
    @endif

    <form wire:submit.prevent="sendResetLink" class="space-y-5">
        
        <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-gray-700 text-right">ئیمەیل</label>
            <input type="email" wire:model="email" autofocus
                   class="w-full border @error('email') border-red-500 @else border-gray-200 @enderror rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none bg-gray-50 focus:bg-white placeholder-gray-400 text-right"
                   placeholder="ئیمەیلەکەت بنووسە...">
            
            @error('email') 
                <span class="text-red-500 text-xs font-medium block mt-1 text-right">{{ $message }}</span> 
            @enderror
        </div>

        <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white rounded-xl py-3.5 text-sm font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 mt-2 flex items-center justify-center">
            <span wire:loading.remove>ناردنی بەستەری نوێکردنەوە</span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                چاوەڕێ بکە...
            </span>
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition-colors gap-1">
                گەڕانەوە بۆ چوونەژوورەوە
            </a>
        </div>
    </form>
</div>