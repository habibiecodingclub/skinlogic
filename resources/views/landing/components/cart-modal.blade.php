{{-- resources/views/landing/components/cart-modal.blade.php --}}
<style> [x-cloak] { display: none !important; } </style>

<div x-data="cartStore()"
     x-show="open"
     @open-cart-modal.window="openModal()"
     @cart-updated.window="fetchCart(false)"
     x-cloak
     class="relative z-[9999]" 
     role="dialog" 
     aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-0 sm:pl-10">
                
                {{-- Panel Slide --}}
                <div x-show="open"
                     x-transition:enter="transform transition ease-out duration-300"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in duration-200"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     @click.away="closeModal()"
                     class="pointer-events-auto w-screen max-w-md">
                    
                    <div class="flex h-full flex-col bg-white shadow-2xl relative">
                        
                        {{-- Loading Overlay (Saat Checkout Process) --}}
                        <div x-show="processing" 
                             class="absolute inset-0 bg-white/50 backdrop-blur-[1px] z-50 flex items-center justify-center">
                            <div class="bg-white p-4 rounded-2xl shadow-xl flex flex-col items-center animate-pulse">
                                <svg class="animate-spin h-8 w-8 text-primary mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm font-semibold text-gray-800">Memproses Transaksi...</span>
                            </div>
                        </div>

                        {{-- HEADER --}}
                        <div class="px-6 py-4 bg-white border-b border-gray-100 flex items-center justify-between z-10">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 font-poppins">Keranjang</h2>
                                <p class="text-xs text-gray-500" x-show="items.length > 0">
                                    <span x-text="items.length"></span> item tersimpan
                                </p>
                            </div>
                            <button @click="closeModal()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- SCROLLABLE CONTENT (Items + Form) --}}
                        <div class="flex-1 overflow-y-auto bg-gray-50/50" id="cart-scroll-area">
                            
                            {{-- Loading Cart --}}
                            <div x-show="loading" class="flex flex-col items-center justify-center h-64">
                                <div class="w-10 h-10 border-4 border-gray-200 border-t-primary rounded-full animate-spin mb-4"></div>
                                <p class="text-gray-500 text-sm">Memuat data...</p>
                            </div>

                            {{-- Empty State --}}
                            <div x-show="!loading && items.length === 0" class="flex flex-col items-center justify-center h-[60vh] text-center px-6">
                                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                                    <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2 font-poppins">Keranjang Kosong</h3>
                                <p class="text-gray-500 text-sm mb-8 leading-relaxed">Sepertinya Anda belum menambahkan<br>produk apapun ke keranjang.</p>
                                <button @click="closeModal()" class="px-8 py-3 bg-[#001a4d] text-white rounded-xl font-semibold hover:bg-blue-900 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                                    Mulai Belanja
                                </button>
                            </div>

                            {{-- Content if Items Exist --}}
                            <div x-show="!loading && items.length > 0" class="p-6 pb-24">
                                
                                {{-- 1. Product List --}}
                                <div class="space-y-4 mb-8">
                                    <template x-for="item in items" :key="item.product.slug">
                                        <div class="bg-white rounded-2xl p-3 shadow-sm border border-gray-100 relative group transition-all hover:shadow-md">
                                            
                                            {{-- Loading Overlay per Item --}}
                                            <div x-show="loadingItem === item.product.slug" 
                                                 class="absolute inset-0 bg-white/80 backdrop-blur-[1px] rounded-2xl flex items-center justify-center z-10">
                                                <svg class="animate-spin h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>

                                            <div class="flex gap-4">
                                                {{-- Image --}}
                                                <div class="w-20 h-20 flex-shrink-0 bg-gray-50 rounded-xl overflow-hidden border border-gray-100">
                                                    <img :src="'/images/' + item.product.image" 
                                                         :alt="item.product.name"
                                                         class="w-full h-full object-cover">
                                                </div>

                                                {{-- Details --}}
                                                <div class="flex-1 min-w-0 flex flex-col justify-between py-0.5">
                                                    <div class="flex justify-between items-start">
                                                        <h3 class="text-sm font-bold text-gray-900 line-clamp-2 pr-4 leading-tight" x-text="item.product.name"></h3>
                                                        <button @click="removeItem(item.product.slug)" class="text-gray-300 hover:text-red-500 transition-colors p-1 -mr-2 -mt-2">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="flex items-end justify-between mt-2">
                                                        <p class="text-sm font-bold text-[#001a4d]" x-text="formatRupiah(item.product.price)"></p>
                                                        
                                                        {{-- Qty Control --}}
                                                        <div class="flex items-center bg-gray-50 rounded-lg border border-gray-200 h-8">
                                                            <button @click="updateQty(item.product.slug, item.quantity - 1)" :disabled="item.quantity <= 1" class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-[#001a4d] disabled:opacity-30 transition-colors">-</button>
                                                            <span class="text-xs font-bold text-gray-900 w-6 text-center" x-text="item.quantity"></span>
                                                            <button @click="updateQty(item.product.slug, item.quantity + 1)" class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-[#001a4d] transition-colors">+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- 2. Shipping Form (Moved inside scroll area for better UX) --}}
                                <div class="bg-white rounded-2xl p-5 shadow-sm border border-blue-100">
                                    <h3 class="text-sm font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Data Pengiriman
                                    </h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <input type="text" x-model="form.name" placeholder="Nama Lengkap *" 
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm outline-none"
                                                :class="{'border-red-400 bg-red-50 focus:border-red-500 focus:ring-red-200': errors.name}">
                                            <p x-show="errors.name" class="text-[10px] text-red-500 mt-1 ml-1">Nama wajib diisi</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <input type="email" x-model="form.email" placeholder="Email *" 
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm outline-none"
                                                    :class="{'border-red-400 bg-red-50': errors.email}">
                                                <p x-show="errors.email" class="text-[10px] text-red-500 mt-1 ml-1">Email tidak valid</p>
                                            </div>
                                            <div>
                                                <input type="tel" x-model="form.phone" placeholder="WhatsApp (08xx) *" 
                                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm outline-none"
                                                    :class="{'border-red-400 bg-red-50': errors.phone}">
                                                <p x-show="errors.phone" class="text-[10px] text-red-500 mt-1 ml-1">No. WA wajib diisi</p>
                                            </div>
                                        </div>

                                        <div>
                                            <textarea x-model="form.address" rows="3" placeholder="Alamat Lengkap (Jalan, No, RT/RW, Kota) *" 
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm outline-none resize-none"
                                                :class="{'border-red-400 bg-red-50': errors.address}"></textarea>
                                            <p x-show="errors.address" class="text-[10px] text-red-500 mt-1 ml-1">Alamat lengkap wajib diisi</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Security Badge --}}
                                <div class="mt-6 flex items-center justify-center gap-2 text-[10px] text-gray-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    Enkripsi SSL aman & terpercaya
                                </div>
                            </div>
                        </div>

                        {{-- STICKY FOOTER (Totals & Action) --}}
                        <div x-show="items.length > 0" class="border-t border-gray-100 bg-white p-6 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-20">
                            
                            <div class="flex justify-between items-end mb-4">
                                <div>
                                    <p class="text-xs text-gray-400 mb-1">Total Pembayaran</p>
                                    <div class="flex items-baseline gap-2">
                                        <p class="text-xl font-bold text-[#001a4d]" x-text="formatRupiah(subtotal + 15000)"></p>
                                        <span class="text-xs text-gray-400 font-medium">(Termasuk Ongkir 15rb)</span>
                                    </div>
                                </div>
                            </div>

                            <button @click="processCheckout()" 
                                    :disabled="processing"
                                    class="w-full py-4 bg-[#001a4d] hover:bg-blue-900 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:shadow-blue-900/20 transform hover:-translate-y-0.5 transition-all disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2">
                                <span>Bayar Sekarang</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast Notification (Pengganti Alert) --}}
<div x-data="{ show: false, message: '', type: 'success' }" 
     @cart-toast.window="show = true; message = $event.detail.message; type = $event.detail.type || 'success'; setTimeout(() => show = false, 3000)"
     x-show="show"
     x-cloak
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-y-full opacity-0 scale-90"
     x-transition:enter-end="translate-y-0 opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="translate-y-0 opacity-100 scale-100"
     x-transition:leave-end="translate-y-full opacity-0 scale-90"
     class="fixed bottom-6 left-1/2 transform -translate-x-1/2 px-6 py-3.5 rounded-full shadow-2xl z-[10000] flex items-center gap-3 min-w-[320px] justify-center border border-white/10 backdrop-blur-md"
     :class="type === 'error' ? 'bg-red-500 text-white' : 'bg-[#001a4d] text-white'">
    
    {{-- Icon Success --}}
    <svg x-show="type === 'success'" class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{-- Icon Error --}}
    <svg x-show="type === 'error'" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    
    <span class="font-medium text-sm tracking-wide" x-text="message"></span>
</div>

<script>
function cartStore() {
    return {
        open: false,
        loading: false,
        loadingItem: null,
        processing: false,
        items: [],
        subtotal: 0,
        form: { name: '', email: '', phone: '', address: '' },
        errors: {},

        openModal() {
            this.open = true;
            document.body.style.overflow = 'hidden';
            this.fetchCart(true);
        },

        closeModal() {
            this.open = false;
            document.body.style.overflow = '';
        },
        
        formatRupiah(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        },

        async fetchCart(showLoading = false) {
            if (showLoading) this.loading = true;
            try {
                const res = await fetch('{{ route("cart.get") }}');
                const data = await res.json();
                this.items = data.items || [];
                this.subtotal = data.subtotal || 0;
                
                window.dispatchEvent(new CustomEvent('update-cart-badge', { 
                    detail: { count: data.count || 0 } 
                }));
            } catch (e) {
                console.error(e);
            } finally {
                this.loading = false;
            }
        },

        async updateQty(slug, qty) {
            if (qty < 1) return;
            this.loadingItem = slug;
            try {
                await fetch('{{ route("cart.update") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ slug, quantity: qty })
                });
                await this.fetchCart(false);
            } catch (e) { console.error(e); } finally { this.loadingItem = null; }
        },

        async removeItem(slug) {
            // Menggunakan Toast custom untuk konfirmasi bisa kompleks, pakai confirm biasa sudah cukup UX-nya, 
            // tapi kita bikin lebih smooth tanpa reload
            if (!confirm('Hapus produk ini dari keranjang?')) return;
            
            this.loadingItem = slug;
            try {
                await fetch('{{ route("cart.remove") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ slug })
                });
                await this.fetchCart(false);
                window.dispatchEvent(new CustomEvent('cart-toast', { 
                    detail: { message: 'Produk berhasil dihapus', type: 'success' } 
                }));
            } catch (e) { console.error(e); } finally { this.loadingItem = null; }
        },

        validateForm() {
            this.errors = {};
            if (!this.form.name.trim()) this.errors.name = true;
            // Regex email basic
            if (!this.form.email.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) this.errors.email = true;
            // Regex HP Indonesia (08...)
            if (!this.form.phone.trim() || !/^08[0-9]{8,11}$/.test(this.form.phone.replace(/[^0-9]/g, ''))) this.errors.phone = true;
            if (!this.form.address.trim()) this.errors.address = true;
            return Object.keys(this.errors).length === 0;
        },

        async processCheckout() {
            if (!this.validateForm()) {
                // Scroll ke form jika ada error
                const scrollArea = document.getElementById('cart-scroll-area');
                scrollArea.scrollTop = scrollArea.scrollHeight;
                
                window.dispatchEvent(new CustomEvent('cart-toast', { 
                    detail: { message: 'Mohon lengkapi data pengiriman', type: 'error' } 
                }));
                return;
            }

            this.processing = true;
            try {
                const res = await fetch('{{ route("checkout") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify(this.form)
                });
                
                const data = await res.json();
                
                if (data.snap_token) {
                    this.closeModal(); // Tutup modal cart
                    window.snap.pay(data.snap_token, {
                        onSuccess: () => { 
                            window.dispatchEvent(new CustomEvent('cart-toast', { detail: { message: 'Pembayaran Berhasil!', type: 'success' } }));
                            setTimeout(() => window.location.reload(), 2000); 
                        },
                        onPending: () => { 
                            window.dispatchEvent(new CustomEvent('cart-toast', { detail: { message: 'Menunggu Pembayaran...', type: 'success' } }));
                            setTimeout(() => window.location.reload(), 2000); 
                        },
                        onError: () => { 
                            window.dispatchEvent(new CustomEvent('cart-toast', { detail: { message: 'Pembayaran Gagal', type: 'error' } }));
                        },
                        onClose: () => { console.log('closed'); }
                    });
                } else {
                    throw new Error(data.error || 'Terjadi kesalahan sistem');
                }
            } catch (e) {
                window.dispatchEvent(new CustomEvent('cart-toast', { detail: { message: e.message, type: 'error' } }));
            } finally {
                this.processing = false;
            }
        }
    }
}
</script>

<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" 
        data-client-key="{{ config('midtrans.client_key') }}"></script>