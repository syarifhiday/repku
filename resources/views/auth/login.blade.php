<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repku — Train Hard, Track Harder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center" style="font-family: Arial, Helvetica, sans-serif;">
    <div class="w-full max-w-md px-6 text-center">
        <h1 class="text-5xl font-black tracking-tight mb-2">
            REP<span style="color:#FFD500;">KU</span>
        </h1>
        <p class="text-gray-400 uppercase text-xs tracking-widest mb-10 font-bold">Train Hard. Track Harder.</p>

        <div style="background:#161616; border:3px solid #2A2A2A;" class="p-8">
            <p class="mb-6 text-sm text-gray-300">Masuk untuk mulai program latihanmu, di gym atau di rumah.</p>
            <a href="{{ route('auth.google') }}"
               class="flex items-center justify-center gap-3 w-full py-3"
               style="background:#FFD500; color:#0A0A0A; font-weight:800; text-transform:uppercase; letter-spacing:0.05em; border:3px solid #0A0A0A;">
                <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#0A0A0A" d="M44.5 20H24v8.5h11.8C34.6 33.6 30 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.3 0 6.3 1.2 8.6 3.2l6-6C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.1-2.7-.5-4z"/></svg>
                Masuk dengan Google
            </a>
        </div>

        <p class="text-xs text-gray-500 mt-8">Login hanya tersedia melalui akun Google.</p>
    </div>
</body>
</html>
