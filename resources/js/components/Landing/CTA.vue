<script setup lang="ts">
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Phone, X, ShoppingCart, CheckCircle2, Upload, DollarSign } from 'lucide-vue-next';
import { useForm, usePage, Link } from '@inertiajs/vue3';
import { login, register } from '@/routes';

// Placeholder for group of guitars. 
const guitarGroupImage = "/images/cta-guitars-group.png"; 

const page = usePage();
const authUser = computed(() => page.props.auth.user);

// Modal States
const showCustomOrderModal = ref(false);
const orderSuccess = ref(false);

const openCustomOrder = () => {
    showCustomOrderModal.value = true;
    orderSuccess.value = false;
    
    // Reset form if logged in
    if (authUser.value) {
        form.customer_name = authUser.value.name;
    }
};

const closeModals = () => {
    showCustomOrderModal.value = false;
};

// Form Logic
const form = useForm({
    customer_name: '',
    customer_phone: '',
    guitar_type: 'electric' as any,
    orientation: 'right',
    notes: '',
    estimated_price: null as number | null,
    reference_photo: null as File | null,
});

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.reference_photo = target.files[0];
    }
};

const submitOrder = () => {
    form.post('/orders', {
        forceFormData: true,
        onSuccess: () => {
            orderSuccess.value = true;
        }
    });
};
</script>

<template>
    <section class="bg-black py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-12 lg:gap-24">
                <!-- Left Column: Image -->
                <div class="relative flex justify-center lg:justify-end animate-in fade-in slide-in-from-left duration-1000">
                    <div class="relative w-full max-w-lg">
                        <!-- Glow Effect behind guitars -->
                        <div class="absolute inset-0 bg-arya-gold/20 blur-[100px] rounded-full"></div>
                        <img 
                            :src="guitarGroupImage" 
                            alt="Koleksi Gitar Arya Custom" 
                            class="relative z-10 w-full h-auto object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-700"
                        />
                    </div>
                </div>

                <!-- Right Column: Content -->
                <div class="text-center lg:text-left space-y-8 animate-in fade-in slide-in-from-right duration-1000">
                    <h2 class="text-4xl lg:text-5xl font-bold text-white leading-tight">
                        Kreasikan <span class="text-arya-gold">Gitar Mu</span> <br />
                        Sekarang!
                    </h2>
                    
                    <p class="text-white/60 text-lg leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Kreasikan <span class="text-arya-gold font-bold">Gitar</span> Sesuai Keinginanmu disini. Konsultasi Sekarang untuk Mendapatkan Hasil <span class="text-arya-gold font-bold">Gitar</span> yang Maksimal. Pesan Sekarang dan Miliki Gitar Kesayangan mu
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
                        <Button @click="openCustomOrder" class="bg-[#d4a017] hover:bg-[#b88b14] text-white font-bold rounded-full h-14 px-8 text-base shadow-[0_0_20px_rgba(212,160,23,0.3)] hover:shadow-[0_0_30px_rgba(212,160,23,0.5)] transition-all duration-300 w-full sm:w-auto">
                            Kreasikan Sekarang!
                        </Button>
                        
                        <a href="https://wa.me/6285788241715" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto">
                            <Button variant="outline" class="w-full border-green-500 text-green-500 hover:bg-green-500 hover:text-white font-bold rounded-full h-14 px-8 text-base transition-all duration-300 flex items-center justify-center gap-2">
                                Konsultasi Sekarang
                                <Phone class="h-4 w-4 fill-current" />
                            </Button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Order Modal -->
        <div v-if="showCustomOrderModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="closeModals"></div>
            <div class="relative w-full max-w-lg bg-arya-dark border border-white/5 rounded-3xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300 max-h-[90vh] overflow-y-auto">
                <button @click="closeModals" class="absolute top-6 right-6 text-white/40 hover:text-white transition-colors z-10">
                    <X class="h-6 w-6" />
                </button>

                <div class="p-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-14 w-14 bg-transparent rounded-2xl flex items-center justify-center">
                            <img src="/images/logo-guitars.png" alt="Arya Guitar Logo" class="h-12 w-auto object-contain" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-white leading-none">Custom Creation</h2>
                            <p class="text-white/40 text-xs mt-1">Wujudkan gitar impianmu bersama kami</p>
                        </div>
                    </div>

                    <!-- Not Logged In -->
                    <div v-if="!authUser" class="text-center py-6">
                        <div class="bg-red-500/10 border border-red-500/20 p-6 rounded-2xl mb-8">
                            <p class="text-white font-bold leading-relaxed mb-2">
                                Anda harus login terlebih dahulu!
                            </p>
                            <p class="text-white/40 text-sm">Silakan login atau register untuk mulai mengkreasikan gitar Anda.</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <Link :href="login()">
                                <Button variant="outline" class="w-full border-white/10 text-white hover:bg-arya-gold hover:text-white font-bold py-6">Login</Button>
                            </Link>
                            <Link :href="register()">
                                <Button class="w-full bg-arya-gold text-black hover:bg-white hover:text-black font-bold py-6">Register</Button>
                            </Link>
                        </div>
                    </div>

                    <!-- Logged In -->
                    <div v-else>
                        <div v-if="orderSuccess && page.props.flash.new_order" class="text-center py-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
                            <CheckCircle2 class="h-20 w-20 text-green-500 mx-auto mb-6" />
                            <h3 class="text-2xl font-black text-white mb-2">Permintaan Terkirim!</h3>
                            <p class="text-white/40 text-sm mb-6">Tim kami akan menganalisa permintaan Anda. Silakan konfirmasi via WhatsApp.</p>
                            
                            <!-- Order Summary -->
                            <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-8 text-left space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-white/40">Kode:</span>
                                    <span class="text-white font-bold">{{ page.props.flash.new_order.order_code }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-white/40">Bajet:</span>
                                    <span class="text-arya-gold font-bold">
                                        {{ page.props.flash.new_order.estimated_price ? new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(page.props.flash.new_order.estimated_price) : '-' }}
                                    </span>
                                </div>
                            </div>

                            <a 
                                :href="`https://wa.me/6285788241715?text=Halo%2C%20saya%20${encodeURIComponent(page.props.flash.new_order.customer_name)}%20ingin%20diskusi%20custom%20order%20${encodeURIComponent(page.props.flash.new_order.order_code)}.`"
                                target="_blank"
                                class="inline-flex items-center justify-center gap-2 w-full bg-green-500 text-white font-black py-4 rounded-xl hover:bg-green-600 transition-all shadow-lg shadow-green-500/20"
                            >
                                <Phone class="h-5 w-5 fill-current" />
                                Konfirmasi ke WhatsApp
                            </a>
                        </div>
                        
                        <form v-else @submit.prevent="submitOrder" class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Nama Lengkap</label>
                                <input v-model="form.customer_name" required type="text" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm" placeholder="Nama Anda" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Nomor WhatsApp</label>
                                <div class="relative">
                                    <Phone class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-white/20" />
                                    <input v-model="form.customer_phone" required type="tel" class="w-full bg-white/5 border border-white/10 rounded-xl pl-12 pr-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm" placeholder="08..." />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Tipe Gitar</label>
                                    <select v-model="form.guitar_type" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm appearance-none cursor-pointer">
                                        <option value="electric">Electric</option>
                                        <option value="acoustic">Acoustic</option>
                                        <option value="bass">Bass</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Orientation</label>
                                    <select v-model="form.orientation" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm appearance-none cursor-pointer">
                                        <option value="right">Kanan (Standard)</option>
                                        <option value="left">Kiri (Lefty)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- New Fields -->
                             <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Estimasi Bajet (IDR)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/40 text-sm font-bold">Rp</span>
                                    <input v-model="form.estimated_price" type="number" min="0" class="w-full bg-white/5 border border-white/10 rounded-xl pl-12 pr-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm" placeholder="Contoh: 5000000" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Foto Referensi (Opsional)</label>
                                <div class="relative group">
                                    <input type="file" @change="handleFileUpload" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
                                    <div class="w-full bg-white/5 border border-dashed border-white/20 rounded-xl px-4 py-4 text-center group-hover:border-arya-gold/50 transition-colors">
                                        <div v-if="form.reference_photo" class="text-arya-gold text-sm font-bold flex items-center justify-center gap-2">
                                            <CheckCircle2 class="h-4 w-4" />
                                            {{ form.reference_photo.name }}
                                        </div>
                                        <div v-else class="text-white/40 text-sm flex items-center justify-center gap-2">
                                            <Upload class="h-4 w-4" />
                                            Klik untuk upload foto
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Detail Kustom</label>
                                <textarea v-model="form.notes" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm resize-none" placeholder="Jelaskan spesifikasi yang Anda inginkan..."></textarea>
                            </div>

                            <Button type="submit" :disabled="form.processing" class="w-full bg-arya-gold text-black font-black py-6 rounded-xl hover:scale-[1.02] active:scale-[0.98] transition-all mt-4">
                                {{ form.processing ? 'Mengirim...' : 'Kreasikan Sekarang' }}
                            </Button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
input, select, textarea {
    outline: none;
}
option {
    color: black;
    background-color: white;
}
</style>
