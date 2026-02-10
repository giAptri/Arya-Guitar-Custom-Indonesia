<script setup lang="ts">
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { useForm, usePage, Link } from '@inertiajs/vue3';
import { X, ImageIcon, AlertCircle, CheckCircle2, Phone, ShoppingCart } from 'lucide-vue-next';
import { login, register } from '@/routes';

interface Product {
    id: number;
    name: string;
    description: string;
    long_description: string;
    price: string;
    image: string;
    type: 'electric' | 'acoustic' | 'bass';
}

const page = usePage();
const authUser = computed(() => page.props.auth.user);

const terbaru: Product[] = [
    { id: 1, name: 'Arya Stratosphere', type: 'electric', description: 'Gitar elektrik premium dengan pengerjaan asli Indonesia.', long_description: 'Arya Stratosphere dirancang untuk musisi profesional yang menginginkan tone jernih dan sustain maksimal. Menggunakan kayu mahogany pilihan dengan pickup custom Arya-Series.', price: 'Rp 3.500.000 - 5.000.000', image: 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f?q=80&w=400&auto=format&fit=crop' },
    { id: 2, name: 'Arya Flame', type: 'electric', description: 'Gitar elektrik paling populer untuk pecinta musik blues.', long_description: 'Varian Flame memberikan karakter suara yang hangat (warm) namun tetap memiliki punch yang kuat. Cocok untuk genre blues, rock, hingga fusion.', price: 'Rp 2.000.000 - 3.500.000', image: 'https://images.unsplash.com/photo-1550291652-6ea9114a47b1?q=80&w=400&auto=format&fit=crop' },
    { id: 3, name: 'Arya Sahaya', type: 'acoustic', description: 'Perpatauan nuansa modern dengan kualitas material terbaik.', long_description: 'Sahaya adalah master akustik kami. Dengan body auditorium dan material spruce, gitar ini menghasilkan resonansi yang sangat imersif.', price: 'Rp 3.000.000 - 4.500.000', image: 'https://images.unsplash.com/photo-1516924911020-74bd5092163b?q=80&w=400&auto=format&fit=crop' },
    { id: 4, name: 'Arya Neo', type: 'electric', description: 'Inovasi suara terbaru untuk generasi musik masa kini.', long_description: 'Desain ergonomis dengan hardware modern. Neo diciptakan bagi gitaris yang menyukai kecepatan pengerjaan dan kemudahan playability.', price: 'Rp 2.800.000 - 4.000.000', image: 'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?q=80&w=400&auto=format&fit=crop' },
    { id: 5, name: 'Arya Vintage', type: 'electric', description: 'Retro look dengan tone yang hangat dan mendalam.', long_description: 'Membawa Anda kembali ke era keemasan gitar elektrik. Finishing nitrocellulose lacquer dan tone vintage yang legendaris.', price: 'Rp 4.200.000 - 5.800.000', image: 'https://images.unsplash.com/photo-1556442291-dc72986f34f3?q=80&w=400&auto=format&fit=crop' },
];

const terlaris: Product[] = [
    { id: 6, name: 'Arya Bloom', type: 'acoustic', description: 'Gitar akustik yang didesain khusus bagi para pemula.', long_description: 'Bloom fokus pada kenyamanan. Neck yang slim membuat pemula lebih mudah menekan chord tanpa rasa sakit yang berlebihan.', price: 'Rp 1.000.000 - 2.500.000', image: 'https://images.unsplash.com/photo-1525201548942-d8b8bb66ec70?q=80&w=400&auto=format&fit=crop' },
    { id: 7, name: 'Arya Acoustic', type: 'acoustic', description: 'Gitar akustik dengan pengerjaan tangan buatan Indonesia.', long_description: 'Produk inti kami yang terjual ribuan unit. Ketahanan material dan kualitas suara yang stabil menjadi kunci utamanya.', price: 'Rp 1.200.000 - 1.800.000', image: 'https://images.unsplash.com/photo-1441113554389-c9964ad95275?q=80&w=400&auto=format&fit=crop' },
    { id: 8, name: 'Arya Bass Pro', type: 'bass', description: 'Gitar elektrik bass modern untuk pemain yang berpengalaman.', long_description: 'Low-end yang solid dan mid yang punchy. Dilengkapi dengan preamp aktif untuk memberikan fleksibilitas suara yang luas.', price: 'Rp 4.000.000 - 6.000.000', image: 'https://images.unsplash.com/photo-1549490349-8643362247b5?q=80&w=400&auto=format&fit=crop' },
    { id: 9, name: 'Arya Hollow', type: 'electric', description: 'Desain hollow body untuk resonansi suara yang unik.', long_description: 'Memberikan nuansa jazz dan blues yang otentik. Resonansi akustik alami dipadukan dengan elektronik premium.', price: 'Rp 5.500.000 - 7.000.000', image: 'https://images.unsplash.com/photo-1514649923863-ceaf75b7ec00?q=80&w=400&auto=format&fit=crop' },
];

const showFullTerbaru = ref(false);
const showFullTerlaris = ref(false);

const displayedTerbaru = computed(() => {
    return showFullTerbaru.value ? terbaru : terbaru.slice(0, 3);
});

const displayedTerlaris = computed(() => {
    return showFullTerlaris.value ? terlaris : terlaris.slice(0, 3);
});

// Modal States
const showDetailModal = ref(false);
const showCustomOrderModal = ref(false);
const selectedGuitar = ref<Product | null>(null);
const orderSuccess = ref(false);

const openDetail = (guitar: Product) => {
    selectedGuitar.value = guitar;
    showDetailModal.value = true;
};

const openCustomOrder = (guitar: Product) => {
    selectedGuitar.value = guitar;
    showCustomOrderModal.value = true;
    orderSuccess.value = false;
    
    // Reset form if logged in
    if (authUser.value) {
        form.guitar_id = guitar.id;
        form.guitar_type = guitar.type;
        form.customer_name = authUser.value.name;
    }
};

const closeModals = () => {
    showDetailModal.value = false;
    showCustomOrderModal.value = false;
};

// Form Logic
const form = useForm({
    customer_name: '',
    customer_phone: '',
    guitar_id: null as number | null,
    guitar_type: 'electric' as any,
    orientation: 'right',
    notes: '',
});

const submitOrder = () => {
    form.post('/orders', {
        onSuccess: () => {
            orderSuccess.value = true;
            setTimeout(() => {
                closeModals();
            }, 3000);
        }
    });
};
</script>

<template>
    <section class="bg-arya-dark py-24">
        <div class="container mx-auto px-4">
            <div class="mb-16 text-center">
                <h2 class="text-4xl font-bold text-white">
                    <span class="text-arya-gold">Katalog</span> Kami
                </h2>
            </div>

            <!-- Gitar Terbaru -->
            <div class="mb-20">
                <div class="mb-8 flex items-center justify-between">
                    <h3 class="text-sm font-bold tracking-widest text-white uppercase border-l-4 border-arya-gold pl-4">Gitar Terbaru</h3>
                    <button 
                        @click="showFullTerbaru = !showFullTerbaru"
                        class="text-xs font-semibold text-white/60 hover:text-arya-gold transition-colors uppercase"
                    >
                        {{ showFullTerbaru ? 'Tutup' : 'Lihat Semua >' }}
                    </button>
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 transition-all duration-500">
                    <div v-for="item in displayedTerbaru" :key="item.id" class="group relative overflow-hidden rounded-2xl bg-white/5 p-6 transition-all hover:bg-white/10 hover:-translate-y-2">
                        <div class="relative mb-6 h-48 w-full overflow-hidden rounded-xl cursor-pointer" @click="openDetail(item)">
                            <img :src="item.image" :alt="item.name" class="h-full w-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110" />
                        </div>
                        <h4 class="mb-2 text-xl font-bold text-white">{{ item.name }}</h4>
                        <p class="mb-4 text-sm text-white/50 line-clamp-2">{{ item.description }}</p>
                        <span class="mb-6 block text-sm font-bold text-arya-gold">{{ item.price }}</span>
                        
                        <div class="flex gap-2">
                            <Button @click="openDetail(item)" variant="outline" class="flex-1 border-white/10 text-black hover:bg-white/10 hover:text-white text-xs">
                                Detail
                            </Button>
                            <Button @click="openCustomOrder(item)" class="flex-1 bg-arya-gold font-bold text-black hover:bg-white/10 hover:text-white text-xs">
                                Custom Ini!
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gitar Terlaris -->
            <div class="mb-20">
                <div class="mb-8 flex items-center justify-between">
                    <h3 class="text-sm font-bold tracking-widest text-white uppercase border-l-4 border-arya-gold pl-4">Gitar Terlaris</h3>
                    <button 
                        @click="showFullTerlaris = !showFullTerlaris"
                        class="text-xs font-semibold text-white/60 hover:text-arya-gold transition-colors uppercase"
                    >
                        {{ showFullTerlaris ? 'Tutup' : 'Lihat Semua >' }}
                    </button>
                </div>
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 transition-all duration-500">
                    <div v-for="item in displayedTerlaris" :key="item.id" class="group relative overflow-hidden rounded-2xl bg-white/5 p-6 transition-all hover:bg-white/10 hover:-translate-y-2">
                        <div class="relative mb-6 h-48 w-full overflow-hidden rounded-xl cursor-pointer" @click="openDetail(item)">
                            <img :src="item.image" :alt="item.name" class="h-full w-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500 group-hover:scale-110" />
                        </div>
                        <h4 class="mb-2 text-xl font-bold text-white">{{ item.name }}</h4>
                        <p class="mb-4 text-sm text-white/50 line-clamp-2">{{ item.description }}</p>
                        <span class="mb-6 block text-sm font-bold text-arya-gold">{{ item.price }}</span>
                        
                        <div class="flex gap-2">
                            <Button @click="openDetail(item)" variant="outline" class="flex-1 border-white/10 text-black hover:bg-white/10 hover:text-white text-xs">
                                Detail
                            </Button>
                            <Button @click="openCustomOrder(item)" class="flex-1 bg-arya-gold font-bold text-black hover:bg-white/10 hover:text-white text-xs">
                                Custom Ini!
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div v-if="showDetailModal && selectedGuitar" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="closeModals"></div>
            <div class="relative w-full max-w-4xl bg-arya-dark border border-white/5 rounded-3xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
                <button @click="closeModals" class="absolute top-6 right-6 text-white/40 hover:text-white transition-colors z-10">
                    <X class="h-6 w-6" />
                </button>
                
                <div class="flex flex-col md:flex-row">
                    <div class="md:w-1/2 h-64 md:h-auto overflow-hidden">
                        <img :src="selectedGuitar.image" :alt="selectedGuitar.name" class="w-full h-full object-cover" />
                    </div>
                    <div class="md:w-1/2 p-10 flex flex-col justify-center">
                        <h2 class="text-3xl font-black text-white mb-2">{{ selectedGuitar.name }}</h2>
                        <span class="inline-block px-3 py-1 bg-arya-gold/10 text-arya-gold text-[10px] font-black uppercase tracking-widest rounded mb-6 w-fit">
                            {{ selectedGuitar.type }} Series
                        </span>
                        
                        <p class="text-white/60 mb-8 leading-relaxed">
                            {{ selectedGuitar.long_description }}
                        </p>
                        
                        <div class="space-y-6">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-white/30 uppercase tracking-widest mb-1">Range Harga Estimasi</span>
                                <span class="text-2xl font-black text-arya-gold">{{ selectedGuitar.price }}</span>
                            </div>
                            
                            <Button @click="openCustomOrder(selectedGuitar)" class="w-full bg-arya-gold text-black font-black py-6 rounded-xl hover:scale-[1.02] active:scale-[0.98] transition-all">
                                Pesan Custom Sekarang
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Order Modal -->
        <div v-if="showCustomOrderModal && selectedGuitar" class="fixed inset-0 z-[110] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-md" @click="closeModals"></div>
            <div class="relative w-full max-w-lg bg-arya-dark border border-white/5 rounded-3xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300">
                <button @click="closeModals" class="absolute top-6 right-6 text-white/40 hover:text-white transition-colors z-10">
                    <X class="h-6 w-6" />
                </button>

                <div class="p-10">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="h-14 w-14 bg-arya-gold/10 rounded-2xl flex items-center justify-center">
                            <ShoppingCart class="h-7 w-7 text-arya-gold" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-white leading-none">Custom Order</h2>
                            <p class="text-white/40 text-xs mt-1">Mengajukan pesanan untuk {{ selectedGuitar.name }}</p>
                        </div>
                    </div>

                    <!-- Not Logged In -->
                    <div v-if="!authUser" class="text-center py-6">
                        <div class="bg-red-500/10 border border-red-500/20 p-6 rounded-2xl mb-8">
                            <AlertCircle class="h-10 w-10 text-red-500 mx-auto mb-4" />
                            <p class="text-white font-bold leading-relaxed">
                                Anda harus register terlebih dahulu atau login untuk memesan gitar!
                            </p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <Link :href="login()">
                                <Button variant="outline" class="w-full border-white/10 text-black hover:bg-arya-gold hover:text-black font-bold py-6">Login</Button>
                            </Link>
                            <Link :href="register()">
                                <Button class="w-full bg-arya-gold text-black hover:bg-white hover:text-black font-bold py-6">Register</Button>
                            </Link>
                        </div>
                    </div>

                    <!-- Logged In -->
                    <div v-else>
                        <div v-if="orderSuccess" class="text-center py-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
                            <CheckCircle2 class="h-20 w-20 text-green-500 mx-auto mb-6" />
                            <h3 class="text-2xl font-black text-white mb-2">Pemesanan Berhasil!</h3>
                            <p class="text-white/40 text-sm">Pesanan Anda telah diterima. Tim admin akan segera memvalidasi pesanan Anda di dashboard.</p>
                        </div>
                        
                        <form v-else @submit.prevent="submitOrder" class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Nama Lengkap</label>
                                <input v-model="form.customer_name" required type="text" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm" placeholder="Contoh: Ari Arya" />
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Nomor WhatsApp</label>
                                <div class="relative">
                                    <Phone class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-white/20" />
                                    <input v-model="form.customer_phone" required type="tel" class="w-full bg-white/5 border border-white/10 rounded-xl pl-12 pr-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm" placeholder="Contoh: 08123456789" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Orientation</label>
                                    <select v-model="form.orientation" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm appearance-none cursor-pointer">
                                        <option value="right">Kanan (Standard)</option>
                                        <option value="left">Kiri (Lefty)</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Tipe Gitar</label>
                                    <select v-model="form.guitar_type" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm appearance-none cursor-pointer">
                                        <option value="electric">Electric</option>
                                        <option value="acoustic">Acoustic</option>
                                        <option value="bass">Bass</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-white/30 uppercase tracking-widest pl-1">Catatan Kustom (Material/Warna/Inlay)</label>
                                <textarea v-model="form.notes" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-arya-gold focus:ring-0 transition-all text-sm resize-none" placeholder="Tuliskan spesifikasi kustom yang Anda inginkan..."></textarea>
                            </div>

                            <Button type="submit" :disabled="form.processing" class="w-full bg-arya-gold text-black font-black py-6 rounded-xl hover:scale-[1.02] active:scale-[0.98] transition-all mt-4">
                                {{ form.processing ? 'Mengirim...' : 'Pesan Sekarang' }}
                            </Button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Scoped styles for the catalog animation and modals */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

input, select, textarea {
    outline: none;
}
</style>
