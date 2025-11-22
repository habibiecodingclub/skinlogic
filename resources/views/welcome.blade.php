<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang di landing page</title>

    {{-- Ini akan memuat app.css (yang berisi Tailwind) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">

    {{-- Navigasi Bar Sederhana --}}
    <nav class="bg-blue-500 shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="text-2xl font-bold text-gray-800">TokoPOS</div>
            <div>
                {{-- Link ke admin panel yang sudah ada --}}
                <a href="/admin" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Login Admin
                </a>
            </div>
        </div>
    </nav>

    {{-- Bagian Hero (Judul Utama) --}}
    <main class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="text-center p-12 bg-white rounded-lg shadow-xl">
            <h1 class="text-5xl font-extrabold text-gray-900 bg-cyan-500 text-white">
                anggap aja ini landing page
            </h1>
            <p class="mt-4 text-xl text-gray-600">
                Kelola bisnis Anda dengan mudah, cepat, dan efisien.
            </p>
            <a href="/admin" class="mt-8 inline-block px-8 py-3 bg-blue-600 text-white text-lg font-semibold rounded-lg hover:bg-blue-700">
                Mulai Sekarang
            </a>
        </div>
    </main>

    {{-- Footer Sederhana --}}
    <footer class="bg-gray-800 text-white p-6 text-center">
        <p>&copy; 2025 Proyek POS Anda. Dibuat dengan Laravel & Tailwind CSS.</p>
    </footer>

</body>
</html>
