{{-- resources/views/components/reservation-detail.blade.php --}}
<div x-data="checkStatusModalLogic" 
     @open-reservation-detail.window="openModal()"
     x-show="isOpen" 
     style="display: none;" 
     class="fixed inset-0 z-[100] overflow-y-auto" 
     role="dialog" 
     aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="isOpen" 
         x-transition.opacity 
         class="fixed inset-0 bg-black/60 backdrop-blur-sm" 
         @click="closeModal()">
    </div>

    {{-- Modal Panel --}}
    <div class="flex min-h-full items-center justify-center p-4">
        <div x-show="isOpen" 
             x-transition 
             class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl p-8 font-poppins overflow-hidden">
            
            {{-- Loading Overlay --}}
            <div x-show="isLoading" class="absolute inset-0 bg-white/80 z-50 flex items-center justify-center">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-[#001a4d] mb-2"></div>
                    <p class="text-xs text-gray-500 font-bold">Memproses...</p>
                </div>
            </div>

            {{-- Close Button --}}
            <button @click="closeModal()" 
                    class="absolute top-4 right-4 text-gray-300 hover:text-gray-500 z-10 text-2xl leading-none">
                ✕
            </button>

            {{-- Header --}}
            <h3 class="text-2xl font-bold text-[#001a4d] text-center mb-6">
                Cek Status Reservasi
            </h3>
            
            {{-- Search Input --}}
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">
                        Kode Booking (RES-XXXX)
                    </label>
                    <div class="flex gap-2">
                        <input type="text" 
                               x-model="checkCode" 
                               @keyup.enter="checkStatus()"
                               placeholder="Contoh: RES-000012" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 text-sm focus:border-[#001a4d] outline-none uppercase font-mono">
                        <button @click="checkStatus()" 
                                class="bg-[#001a4d] hover:bg-[#002a66] text-white px-6 rounded-lg font-bold shadow-md transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Result Display --}}
            <div x-show="checkResult" class="mt-6">
                <div class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-5 relative overflow-hidden group hover:border-[#001a4d] transition-colors">
                    
                    {{-- Status Badge --}}
                    <div class="absolute top-0 right-0 mt-4 mr-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border"
                              :class="{
                                  'bg-yellow-100 text-yellow-700 border-yellow-200': checkResult?.status === 'menunggu',
                                  'bg-green-100 text-green-700 border-green-200': checkResult?.status === 'dikonfirmasi' || checkResult?.status === 'selesai',
                                  'bg-blue-100 text-blue-700 border-blue-200': checkResult?.status === 'dikerjakan',
                                  'bg-red-100 text-red-700 border-red-200': checkResult?.status === 'batal'
                              }" 
                              x-text="checkResult?.status_label">
                        </span>
                    </div>
    
                    {{-- Ticket Content --}}
                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">Kode Booking</p>
                            <p class="text-xl font-mono font-bold text-[#001a4d]" 
                               x-text="checkResult?.reservation_code || checkCode">
                            </p>
                        </div>
                        <div class="h-px bg-gray-200 w-full"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Pelanggan</p>
                                <p class="font-bold text-sm text-gray-800" 
                                   x-text="checkResult?.pelanggan?.nama || '-'">
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Terapis</p>
                                <p class="font-bold text-sm text-gray-800" 
                                   x-text="checkResult?.terapis?.nama || '-'">
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Tanggal</p>
                                <p class="font-semibold text-sm" 
                                   x-text="checkResult?.tanggal || '-'">
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Jam</p>
                                <p class="font-semibold text-sm" 
                                   x-text="checkResult?.jam || '-'">
                                </p>
                            </div>
                        </div>

                        {{-- Perawatan List --}}
                        <div x-show="checkResult?.perawatans && checkResult.perawatans.length > 0" 
                             class="pt-3 border-t border-gray-200">
                            <p class="text-[10px] text-gray-400 uppercase mb-2">Perawatan</p>
                            <template x-for="(p, idx) in checkResult?.perawatans" :key="idx">
                                <div class="flex justify-between text-xs mb-1">
                                    <span x-text="p.nama"></span>
                                    <span class="font-semibold" x-text="formatRupiah(p.harga)"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Footer Actions --}}
            <div class="mt-8 text-center border-t pt-4">
                <p class="text-xs text-gray-400 mb-2">Belum punya reservasi?</p>
                <button @click="$dispatch('open-reservation')"
                        class="text-sm font-bold text-[#001a4d] hover:underline">
                    Buat Reservasi Baru
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkStatusModalLogic', () => ({
            isOpen: false,
            isLoading: false,
            checkCode: '',
            checkResult: null,

            openModal() { 
                this.isOpen = true; 
            },
            
            closeModal() { 
                this.isOpen = false; 
                setTimeout(() => { 
                    this.resetForm();
                }, 300); 
            },

            resetForm() {
                this.checkCode = '';
                this.checkResult = null;
            },

            formatRupiah(n) { 
                return new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR', 
                    minimumFractionDigits: 0 
                }).format(n); 
            },

            async checkStatus() {
                if(!this.checkCode) {
                    alert('Masukkan kode booking!');
                    return;
                }

                this.isLoading = true;
                this.checkResult = null;

                try {
                    const res = await axios.get('/api/reservations/' + this.checkCode);
                    if(res.data.success) {
                        this.checkResult = res.data.data;
                    }
                } catch(e) {
                    alert('Data reservasi tidak ditemukan.');
                    console.error(e);
                } finally {
                    this.isLoading = false;
                }
            }
        }));
    });
</script>