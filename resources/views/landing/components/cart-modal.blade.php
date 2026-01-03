<div x-data="cartStore()"
     x-show="open"
     @open-cart-modal.window="open = true; fetchCart()"
     @cart-updated.window="fetchCart()"
     x-cloak
     class="relative z-[9999]" 
     aria-labelledby="slide-over-title" 
     role="dialog" 
     aria-modal="true">

    {{-- Backdrop (Gelap di belakang) --}}
    <div x-show="open"
         x-transition:enter="ease-in-out duration-500"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in-out duration-500"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                
                {{-- Panel Slide --}}
                <div x-show="open"
                     x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     @click.away="open = false"
                     class="pointer-events-auto w-screen max-w-md">
                    
                    <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                        
                        {{-- Header Cart --}}
                        <div class="flex items-start justify-between px-4 py-6 sm:px-6 border-b border-gray-100">
                            <h2 class="text-lg font-bold text-gray-900 font-poppins" id="slide-over-title">Keranjang Belanja</h2>
                            <div class="ml-3 flex h-7 items-center">
                                <button type="button" @click="open = false" class="relative -m-2 p-2 text-gray-400 hover:text-gray-500">
                                    <span class="absolute -inset-0.5"></span>
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Isi Cart --}}
                        <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                            
                            {{-- State: Loading --}}
                            <div x-show="loading" class="flex justify-center py-10">
                                <svg class="animate-spin h-8 w-8 text-pink-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                            {{-- State: Kosong --}}
                            <div x-show="!loading && items.length === 0" class="text-center py-10">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                </div>
                                <p class="text-gray-500">Keranjang masih kosong.</p>
                                <button @click="open = false" class="mt-4 text-pink-600 font-medium hover:text-pink-700">Mulai Belanja &rarr;</button>
                            </div>

                            {{-- List Items --}}
                            <ul x-show="!loading && items.length > 0" role="list" class="-my-6 divide-y divide-gray-200">
                                <template x-for="(item, index) in items" :key="index">
                                    <li class="flex py-6">
                                        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                            {{-- Handle Image (Asumsi path image dari backend) --}}
                                            <img :src="'/images/' + item.product.image" :alt="item.product.name" class="h-full w-full object-cover object-center">
                                        </div>

                                        <div class="ml-4 flex flex-1 flex-col">
                                            <div>
                                                <div class="flex justify-between text-base font-medium text-gray-900">
                                                    <h3 x-text="item.product.name"></h3>
                                                    <p class="ml-4" x-text="formatRupiah(item.total)"></p>
                                                </div>
                                                <p class="mt-1 text-sm text-gray-500" x-text="item.product.category"></p>
                                            </div>
                                            <div class="flex flex-1 items-end justify-between text-sm">
                                                {{-- Quantity Control --}}
                                                <div class="flex items-center border border-gray-300 rounded-lg">
                                                    <button @click="updateQty(item.product.slug, item.quantity - 1)" class="px-2 py-1 hover:bg-gray-100">-</button>
                                                    <span class="px-2 text-gray-900" x-text="item.quantity"></span>
                                                    <button @click="updateQty(item.product.slug, item.quantity + 1)" class="px-2 py-1 hover:bg-gray-100">+</button>
                                                </div>

                                                <button @click="removeItem(item.product.slug)" type="button" class="font-medium text-red-500 hover:text-red-600">Hapus</button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>

                        {{-- Footer Checkout --}}
                        <div x-show="items.length > 0" class="border-t border-gray-200 px-4 py-6 sm:px-6 bg-gray-50">
                            <div class="flex justify-between text-base font-medium text-gray-900 mb-4">
                                <p>Subtotal</p>
                                <p x-text="formatRupiah(subtotal)"></p>
                            </div>
                            
                            {{-- Form Singkat Checkout --}}
                            <div class="space-y-3 mb-4">
                                <input type="text" x-model="form.name" placeholder="Nama Lengkap" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-pink-500 focus:ring-pink-500">
                                <input type="email" x-model="form.email" placeholder="Email" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-pink-500 focus:ring-pink-500">
                                <input type="text" x-model="form.phone" placeholder="No. WhatsApp" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-pink-500 focus:ring-pink-500">
                                <textarea x-model="form.address" placeholder="Alamat Lengkap" rows="2" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-pink-500 focus:ring-pink-500"></textarea>
                            </div>

                            <button @click="processCheckout()" 
                                    :disabled="processing || !isFormValid"
                                    class="flex w-full items-center justify-center rounded-xl border border-transparent bg-slate-900 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all">
                                <span x-show="!processing">Checkout Sekarang</span>
                                <span x-show="processing">Memproses...</span>
                            </button>
                            <div class="mt-6 flex justify-center text-center text-sm text-gray-500">
                                <p>
                                    atau
                                    <button @click="open = false" type="button" class="font-medium text-pink-600 hover:text-pink-500">
                                        Lanjut Belanja
                                        <span aria-hidden="true"> &rarr;</span>
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT ALPINE.JS LOGIC --}}
<script>
    function cartStore() {
        return {
            open: false,
            loading: false,
            processing: false,
            items: [],
            subtotal: 0,
            form: {
                name: '',
                email: '',
                phone: '',
                address: ''
            },
            
            get isFormValid() {
                return this.form.name && this.form.email && this.form.phone && this.form.address;
            },

            formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
            },

            async fetchCart() {
                this.loading = true;
                try {
                    const res = await fetch('{{ route("cart.get") }}');
                    const data = await res.json();
                    this.items = data.items;
                    this.subtotal = data.subtotal;
                } catch (e) {
                    console.error(e);
                }
                this.loading = false;
            },

            async updateQty(slug, qty) {
                if (qty < 1) return; 
                try {
                    await fetch('{{ route("cart.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ slug, quantity: qty })
                    });
                    this.fetchCart();
                } catch (e) { console.error(e); }
            },

            async removeItem(slug) {
                if(!confirm('Hapus produk ini?')) return;
                try {
                    await fetch('{{ route("cart.remove") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ slug })
                    });
                    this.fetchCart();
                    // Update badge count in header if needed
                    window.dispatchEvent(new CustomEvent('cart-count-updated'));
                } catch (e) { console.error(e); }
            },

            async processCheckout() {
                this.processing = true;
                try {
                    const res = await fetch('{{ route("checkout") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.form)
                    });
                    
                    const data = await res.json();
                    
                    if (data.snap_token) {
                        this.open = false; // Close modal
                        
                        // Trigger Midtrans Snap
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result){ alert("Pembayaran Berhasil!"); window.location.reload(); },
                            onPending: function(result){ alert("Menunggu Pembayaran!"); window.location.reload(); },
                            onError: function(result){ alert("Pembayaran Gagal!"); },
                            onClose: function(){ alert('Anda menutup popup pembayaran tanpa menyelesaikan pembayaran'); }
                        });
                    } else {
                        alert(data.error || 'Terjadi kesalahan');
                    }
                } catch (e) {
                    console.error(e);
                    alert('Gagal memproses checkout');
                }
                this.processing = false;
            }
        }
    }
</script>

{{-- PENTING: SCRIPT MIDTRANS (Hanya perlu diload sekali) --}}
{{-- Pastikan CLIENT_KEY sesuai dengan .env --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>