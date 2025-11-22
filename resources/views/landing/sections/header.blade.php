<header x-data="{ open: false }" class="absolute top-0 left-0 w-full z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <!-- Logo -->
        <div class="text-white text-xl font-bold tracking-wide">
            SkinLogic
        </div>

        <!-- Navigation -->
        <nav class="hidden md:flex space-x-6 text-white font-medium">
            <a href="#home">Home</a>
            <a href="#produk">Produk</a>
            <a href="#perawatan">Perawatan</a>
            <a href="#tentang">Tentang Kami</a>
            <a href="#artikel">Artikel</a>
            <a href="#reservasi" class="bg-white text-skinlogic px-4 py-2 rounded-full">
                Reservasi Sekarang
            </a>
        </nav>

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button @click="open = !open" class="text-white focus:outline-none">
                <!-- icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="md:hidden bg-black bg-opacity-80 text-white px-6 py-4 space-y-4">
        <a href="#home" class="block">Home</a>
        <a href="#produk" class="block">Produk</a>
        <a href="#perawatan" class="block">Perawatan</a>
        <a href="#tentang" class="block">Tentang Kami</a>
        <a href="#artikel" class="block">Artikel</a>
        <a href="#reservasi" class="block bg-white text-skinlogic px-4 py-2 rounded-full">
            Reservasi Sekarang
        </a>
    </div>
</header>
