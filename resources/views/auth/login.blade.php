<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چوونەژوورەوە - ماركێتی شارەکەم</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{font-family:'Noto Sans Arabic',sans-serif;}</style>
</head>
<body class="bg-gradient-to-br from-slate-900 via-[#141a2b] to-indigo-950 min-h-screen flex items-center justify-center p-4">
    
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8 md:p-10 border border-gray-100">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4 shadow-inner">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-l from-indigo-600 to-blue-500 mb-2">
                ماركێتی شارەکەم
            </h1>
            <p class="text-sm text-gray-500 font-medium">بەخێربێیتەوە! تکایە بچۆ ژوورەوە بۆ بەڕێوەبردنی سیستەم</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-r-4 border-red-500 text-red-700 px-4 py-3 rounded-lg text-sm mb-6 flex items-center shadow-sm">
                <svg class="w-5 h-5 ml-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-gray-700">ئیمەیل</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none bg-gray-50 focus:bg-white placeholder-gray-400"
                       placeholder="ئیمەیلەکەت بنووسە...">
            </div>

            <div class="space-y-1.5">
                <label class="block text-sm font-semibold text-gray-700">وشەی نهێنی</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none bg-gray-50 focus:bg-white placeholder-gray-400"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <input type="checkbox" name="remember" 
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                    <span class="text-sm text-gray-600 group-hover:text-indigo-700 transition-colors">بمهێڵەوە چوونەژوورەوە</span>
                </label>
                
             <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
    وشەی نهێنیت لەبیرکردووە؟
</a>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 text-white rounded-xl py-3.5 text-sm font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 mt-2">
                چوونەژوورەوە
            </button>
        </form>
    </div>

</body>
</html>