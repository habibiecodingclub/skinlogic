{{-- resources/views/landing/components/reservation.blade.php --}}
<div x-data="reservationModalLogic" 
     @open-reservation.window="openModal()"
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

            {{-- Header Progress --}}
            <div x-show="step < 5" class="text-center mb-6">
                <h3 class="text-2xl font-bold text-[#001a4d]">Reservasi Online</h3>
                <p class="text-xs text-gray-400 mt-1">Langkah <span x-text="step"></span> dari 4</p>
                <div class="w-full bg-gray-200 h-1.5 rounded-full mt-3 overflow-hidden">
                    <div class="bg-[#c5a365] h-1.5 rounded-full transition-all duration-500" 
                         :style="'width: ' + (step * 25) + '%'">
                    </div>
                </div>
            </div>

            {{-- STEP 1: PILIH PERAWATAN --}}
            <div x-show="step === 1" x-transition>
                <h4 class="font-bold text-sm mb-3">1. Pilih Perawatan:</h4>
                <div class="space-y-2 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                    <template x-for="item in listPerawatan" :key="item.id">
                        <label class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition"
                               :class="isSelected(item.id) ? 'border-[#001a4d] bg-blue-50' : 'border-gray-200'">
                            <div class="flex items-center gap-3">
                                {{-- FIX: Bind checked state dengan isSelected --}}
                                <input type="checkbox" 
                                       :value="item.id" 
                                       :checked="isSelected(item.id)"
                                       @change="togglePerawatan(item)" 
                                       class="rounded text-[#001a4d] w-4 h-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800" x-text="item.Nama_Perawatan"></p>
                                    <p class="text-xs text-gray-500" x-text="formatRupiah(item.Harga)"></p>
                                </div>
                            </div>
                        </label>
                    </template>
                </div>
                <div class="mt-3 text-right">
                    <p class="text-xs text-gray-500">Total Estimasi:</p>
                    <p class="text-lg font-bold text-[#001a4d]" x-text="formatRupiah(totalHarga)"></p>
                </div>
            </div>

            {{-- STEP 2: PILIH JADWAL & TERAPIS --}}
            <div x-show="step === 2" x-transition>
                <h4 class="font-bold text-sm mb-3">2. Pilih Jadwal & Terapis:</h4>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" 
                               x-model="form.tanggal" 
                               @change="fetchSlots()" 
                               class="w-full px-4 py-3 rounded-lg border border-gray-200 text-sm focus:border-[#001a4d] outline-none">
                    </div>

                    <div x-show="form.tanggal">
                        <label class="block text-xs font-bold text-gray-700 mb-2">Jam</label>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="slot in availableSlots" :key="slot">
                                <button type="button" 
                                        @click="selectSlot(slot)"
                                        :class="form.jam === slot ? 'bg-[#001a4d] text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        class="text-xs py-2 rounded transition font-medium">
                                    <span x-text="slot"></span>
                                </button>
                            </template>
                        </div>
                        <p x-show="availableSlots.length === 0 && !isLoading" 
                           class="text-xs text-red-500 mt-2 text-center">
                            Slot penuh / Tanggal lewat.
                        </p>
                    </div>

                    <div x-show="form.jam" x-transition>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Pilih Terapis</label>
                        <select x-model="form.terapis_id" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 text-sm focus:border-[#001a4d] outline-none bg-white">
                            <option value="">-- Pilih Terapis --</option>
                            <template x-for="terapis in listTherapists" :key="terapis.id">
                                <option :value="terapis.id" x-text="terapis.name"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            {{-- STEP 3: DATA DIRI --}}
            <div x-show="step === 3" x-transition>
                <h4 class="font-bold text-sm mb-3">3. Data Diri:</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1">Nama Lengkap</label>
                        <input type="text" 
                               x-model="form.nama" 
                               placeholder="Sesuai KTP" 
                               class="w-full px-3 py-2 border rounded-md text-sm focus:border-[#001a4d] outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1">Email Aktif</label>
                        <input type="email" 
                               x-model="form.email" 
                               placeholder="contoh@email.com" 
                               class="w-full px-3 py-2 border rounded-md text-sm focus:border-[#001a4d] outline-none">
                        <p class="text-[10px] text-yellow-600 mt-1">*Kode booking akan dikirim ke email ini.</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1">No. WhatsApp</label>
                        <input type="number" 
                               x-model="form.telepon" 
                               placeholder="0812..." 
                               class="w-full px-3 py-2 border rounded-md text-sm focus:border-[#001a4d] outline-none">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 mb-1">Catatan</label>
                        <textarea x-model="form.catatan" 
                                  placeholder="Opsional" 
                                  class="w-full px-3 py-2 border rounded-md text-sm focus:border-[#001a4d] outline-none">
                        </textarea>
                    </div>
                </div>
            </div>

            {{-- STEP 4: KONFIRMASI --}}
            <div x-show="step === 4" x-transition>
                <div class="text-center space-y-4">
                    <div class="w-12 h-12 bg-blue-50 text-[#001a4d] rounded-full flex items-center justify-center mx-auto mb-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-lg text-[#001a4d]">Konfirmasi Data</h4>
                    
                    <div class="bg-gray-50 p-4 rounded-xl text-left text-sm space-y-3 border border-gray-200">
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Email Penerima Kode</p>
                            <p class="font-bold text-lg text-[#001a4d]" x-text="form.email"></p>
                            <p class="text-[10px] text-red-500 italic" x-show="!form.email.includes('@')">
                                Format email sepertinya salah!
                            </p>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2 border-t border-gray-200">
                            <div>
                                <p class="text-xs text-gray-400 uppercase">Tanggal</p>
                                <p class="font-semibold" x-text="form.tanggal"></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase">Jam</p>
                                <p class="font-semibold" x-text="form.jam"></p>
                            </div>
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <p class="text-xs text-gray-400 uppercase">Total Biaya</p>
                            <p class="font-bold text-[#c5a365]" x-text="formatRupiah(totalHarga)"></p>
                        </div>
                    </div>

                    <p class="text-[10px] text-gray-400">
                        Pastikan email benar. Kode Booking tidak akan terkirim jika email salah.
                    </p>
                </div>
            </div>

            {{-- STEP 5: SUKSES --}}
            <div x-show="step === 5" class="text-center py-6">
                <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-[#001a4d] mb-4">Reservasi Berhasil!</h4>
                
                <div class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-5 relative">
                    <div class="absolute top-0 right-0 mt-4 mr-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide border bg-yellow-100 text-yellow-700 border-yellow-200">
                            Menunggu
                        </span>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">Kode Booking</p>
                            <p class="text-xl font-mono font-bold text-[#001a4d]" x-text="bookingCode"></p>
                        </div>
                        <div class="h-px bg-gray-200 w-full"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Pelanggan</p>
                                <p class="font-bold text-sm text-gray-800" x-text="form.nama"></p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Terapis</p>
                                <p class="font-bold text-sm text-gray-800" x-text="getTherapistName(form.terapis_id)"></p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Tanggal</p>
                                <p class="font-semibold text-sm" x-text="form.tanggal"></p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase">Jam</p>
                                <p class="font-semibold text-sm" x-text="form.jam"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-4">
                    Kode telah dikirim ke <span x-text="form.email" class="font-bold"></span>
                </p>
                <button @click="closeModal()" 
                        class="mt-6 w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-bold text-sm">
                    Tutup
                </button>
            </div>

            {{-- Navigation Buttons --}}
            <div x-show="step < 5" class="mt-8 flex justify-between items-center gap-2">
                <button @click="step--" 
                        x-show="step > 1" 
                        class="text-sm text-gray-500 hover:text-[#001a4d] transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </button>

                <div x-show="step === 1" class="flex-1">
                    <button @click="$dispatch('open-reservation-detail')" 
                            class="text-xs text-[#c5a365] hover:text-[#a0824d] hover:underline font-medium">
                        Sudah booking? <br> Cek Status
                    </button>
                </div>
                
                <button @click="nextStep()" 
                        :disabled="!canProceed()" 
                        :class="!canProceed() ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#002a66] hover:shadow-lg'"
                        class="bg-[#001a4d] text-white px-6 py-2.5 rounded-full font-bold text-sm transition-all shadow-md flex items-center gap-2 ml-auto">
                    <span x-text="step === 4 ? 'Ya, Kirim' : 'Lanjut'"></span>
                    <svg x-show="step < 4" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reservationModalLogic', () => ({
            isOpen: false,
            isLoading: false,
            step: 1,
            
            listPerawatan: [],
            availableSlots: [],
            listTherapists: [],
            
            selectedPerawatans: [],
            totalHarga: 0,
            bookingCode: '',
            
            form: { 
                tanggal: '', 
                jam: '', 
                terapis_id: '', 
                nama: '', 
                telepon: '', 
                email: '', 
                catatan: '' 
            },

            openModal() { 
                this.isOpen = true; 
                this.fetchPerawatan();
            },
            
            closeModal() { 
                this.isOpen = false; 
                setTimeout(() => { 
                    this.resetForm();
                }, 300); 
            },

            resetForm() {
                this.step = 1; 
                this.form = { 
                    tanggal: '', 
                    jam: '', 
                    terapis_id: '', 
                    nama: '', 
                    telepon: '', 
                    email: '', 
                    catatan: '' 
                }; 
                this.selectedPerawatans = []; 
                this.totalHarga = 0;
                this.listTherapists = [];
                this.availableSlots = [];
                this.bookingCode = '';
            },

            getTherapistName(id) {
                const t = this.listTherapists.find(x => x.id == id);
                return t ? t.name : '-';
            },

            canProceed() {
                if (this.step === 1) return this.selectedPerawatans.length > 0;
                if (this.step === 2) return this.form.tanggal && this.form.jam && this.form.terapis_id;
                if (this.step === 3) return this.form.nama && this.form.email.includes('@') && this.form.telepon;
                if (this.step === 4) return true;
                return false;
            },

            nextStep() { 
                if (this.step === 4) {
                    this.submitReservation(); 
                } else {
                    this.step++;
                }
            },

            async fetchPerawatan() {
                try {
                    const res = await axios.get('/api/reservations/perawatans');
                    if(res.data.success) this.listPerawatan = res.data.perawatans;
                } catch(e) { console.error(e); }
            },

            async fetchSlots() {
                if(!this.form.tanggal) return;
                this.isLoading = true; 
                this.form.jam = ''; 
                this.form.terapis_id = '';
                this.listTherapists = [];
                
                try {
                    const res = await axios.get('/api/reservations/available-slots', { 
                        params: { tanggal: this.form.tanggal } 
                    });
                    if(res.data.success) this.availableSlots = res.data.available_slots;
                } catch(e) { 
                    this.availableSlots = []; 
                } finally { 
                    this.isLoading = false; 
                }
            },

            async selectSlot(slot) {
                this.form.jam = slot;
                this.form.terapis_id = ''; 
                await this.fetchTherapists();
            },

            async fetchTherapists() {
                if(!this.form.tanggal || !this.form.jam) return;
                this.isLoading = true;
                try {
                    const res = await axios.get('/api/reservations/available-therapists', { 
                        params: { tanggal: this.form.tanggal, jam: this.form.jam } 
                    });
                    if(res.data.success) this.listTherapists = res.data.available_therapists;
                } catch(e) { 
                    this.listTherapists = []; 
                    console.error(e);
                } finally { 
                    this.isLoading = false; 
                }
            },

            togglePerawatan(item) {
                let harga = parseFloat(item.Harga);
                const idx = this.selectedPerawatans.findIndex(p => p.id === item.id);
                
                if (idx > -1) { 
                    // Item sudah ada, hapus dari array
                    this.selectedPerawatans.splice(idx, 1); 
                    this.totalHarga -= harga; 
                } else { 
                    // Item belum ada, tambahkan ke array
                    this.selectedPerawatans.push(item); 
                    this.totalHarga += harga; 
                }
                
                // Pastikan tidak negatif
                if(this.totalHarga < 0) this.totalHarga = 0;
            },
            
            isSelected(id) { 
                return this.selectedPerawatans.some(p => p.id === id); 
            },

            formatRupiah(n) { 
                return new Intl.NumberFormat('id-ID', { 
                    style: 'currency', 
                    currency: 'IDR', 
                    minimumFractionDigits: 0 
                }).format(n); 
            },

            async submitReservation() {
                this.isLoading = true;
                const payload = {
                    pelanggan: { 
                        nama: this.form.nama, 
                        email: this.form.email, 
                        nomor_telepon: this.form.telepon, 
                        status: "Non Member" 
                    },
                    tanggal_reservasi: this.form.tanggal,
                    jam_reservasi: this.form.jam,
                    terapis_id: this.form.terapis_id, 
                    perawatans: this.selectedPerawatans.map(p => ({ id: p.id, qty: 1 })),
                    catatan: this.form.catatan
                };
                
                try {
                    const res = await axios.post('/api/reservations', payload);
                    if (res.data.success) { 
                        this.bookingCode = res.data.data.reservation_code; 
                        this.step = 5; 
                    }
                } catch (e) { 
                    let msg = 'Gagal reservasi.';
                    if (e.response && e.response.status === 409) {
                        alert("Mohon maaf! Slot & Terapis tersebut baru saja diambil orang lain.");
                        this.step = 2; 
                        this.fetchSlots(); 
                    } 
                    else if(e.response && e.response.data && e.response.data.message) {
                        msg = e.response.data.message;
                        alert(msg); 
                    } else {
                        alert(msg);
                    }
                } finally { 
                    this.isLoading = false; 
                }
            }
        }));
    });
</script>