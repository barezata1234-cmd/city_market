<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چوونەژوورەوە - ماركێتی شارەکەم</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body{font-family:'Noto Sans Arabic',sans-serif;}</style>
</head>
<body class="bg-[#141a2b] min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-sm p-8">
        <div class="text-center mb-6">
            <div class="text-2xl font-bold text-gray-800">ماركێتی شارەکەم</div>
            <div class="text-sm text-gray-400 mt-1">چوونەژوورەوە بۆ بەڕێوەبردنی سیستەم</div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-xs text-gray-500">ئیمەیل</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <div>
                <label class="text-xs text-gray-500">وشەی نهێنی</label>
                <input type="password" name="password" required
                       class="w-full border rounded-lg px-3 py-2 text-sm mt-1">
            </div>
            <label class="flex items-center gap-2 text-xs text-gray-500">
                <input type="checkbox" name="remember"> بمهێڵەوە چوونەژوورەوە
            </label>
            <button type="submit" class="w-full bg-[#141a2b] text-white rounded-lg py-2.5 text-sm font-semibold">
                چوونەژوورەوە
            </button>
        </form>
    </div>
</body>
</html>