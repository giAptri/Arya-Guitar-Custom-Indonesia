<script setup lang="ts">
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { useForm, usePage, Link } from '@inertiajs/vue3';
import { X, ImageIcon, AlertCircle, CheckCircle2, Phone, ShoppingCart, ArrowRight } from 'lucide-vue-next';
import { login, register } from '@/routes';

import { AppPageProps } from '@/types/index';

// Define Product Interface matching DB Model
interface Product {
    id: number;
    name: string;
    description?: string;
    short_desc?: string;
    detail_desc?: string;
    price?: string; // formatted in backend or frontend
    base_price?: number;
    image?: string; // from storage
    image_path?: string;
    type?: 'electric' | 'acoustic' | 'bass';
    slug?: string;
}

const props = defineProps<{
    guitars: Product[];
}>();

const page = usePage<AppPageProps>();
const authUser = computed(() => page.props.auth.user);

// Use props.guitars for the catalog
const catalogGuitars = computed(() => {
    return props.guitars.map(g => ({
        ...g,
        // Ensure image URL is correct (handle storage paths)
        image: g.image_path ? `/storage/${g.image_path}` : 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f?q=80&w=400&auto=format&fit=crop',
        description: g.short_desc || 'Gitar kualitas premium asli Indonesia.',
        long_description: g.detail_desc || 'Deskripsi lengkap belum tersedia untuk produk ini. Hubungi kami untuk detail lebih lanjut.',
        price: new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(g.base_price || 0),
        type: 'electric' // Default or fetch from DB if column exists
    }));
});

// Modal States
const showDetailModal = ref(false);
const showCustomOrderModal = ref(false);
const selectedGuitar = ref<any>(null);
const orderSuccess = ref(false);

const openDetail = (guitar: any) => {
    selectedGuitar.value = guitar;
    showDetailModal.value = true;
};

const openCustomOrder = (guitar: any) => {
    selectedGuitar.value = guitar;
    showCustomOrderModal.value = true;
    orderSuccess.value = false;
    
    // Reset form if logged in
    if (authUser.value) {
        form.guitar_id = guitar.id;
        form.guitar_type = guitar.type || 'electric';
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
            // No auto-close, let user click WhatsApp
        }
    });
};
</script>

<template>
    <section id="catalog" class="bg-[#0a0a0a] py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <h2 class="text-4xl font-bold text-white">
                    <span class="text-arya-gold">Katalog</span> Kami
                </h2>
            </div>

            <!-- Gitar Terbaru (Using DB Data) -->
            <div class="mb-12">
                <div class="mb-8 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-arya-gold uppercase tracking-wider">
                        GITAR <span class="text-white">TERBARU</span>
                    </h3>
                    <a href="#" class="text-xs font-medium text-white/50 hover:text-arya-gold transition-colors flex items-center gap-1">
                        Lihat Lainnya &gt;
                    </a>
                </div>
                
                <div v-if="catalogGuitars.length === 0" class="text-white/40 text-center py-10 italic">
                    Belum ada data gitar. Silakan tambahkan di dashboard admin.
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <div v-for="item in catalogGuitars" :key="item.id" 
                         class="group relative flex flex-col overflow-hidden rounded-[2rem] bg-[#1a1a1a] p-8 border border-white/5 hover:border-arya-gold/30 transition-all duration-300">
                        
                        <!-- Image Container -->
                        <div class="relative mb-6 h-64 w-full flex items-center justify-center cursor-pointer overflow-hidden" @click="openDetail(item)">
                            <img :src="item.image" :alt="item.name" class="h-full w-auto object-contain drop-shadow-2xl group-hover:scale-105 transition-transform duration-500" />
                        </div>
                        
                        <!-- Content -->
                        <div class="flex flex-col flex-1">
                            <div class="mb-6">
                                <h4 class="mb-2 text-2xl font-bold text-arya-gold tracking-tight group-hover:text-white transition-colors cursor-pointer" @click="openDetail(item)">{{ item.name }}</h4>
                                <p class="text-sm text-white/60 leading-relaxed line-clamp-2 h-10">{{ item.description }}</p>
                            </div>
                            
                            <div class="mt-auto flex items-end justify-between">
                                <span class="text-lg font-bold text-white tracking-wider">{{ item.price }}</span>
                                
                                <button @click="openDetail(item)" class="h-12 w-12 rounded-full bg-white flex items-center justify-center hover:bg-gray-200 transition-all duration-300 shadow-lg group-hover:shadow-arya-gold/20">
                                    <ArrowRight class="h-6 w-6 text-arya-gold font-bold" />
                                </button>
                            </div>
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
                        
                        <p class="text-white/60 mb-8 leading-relaxed text-justify">
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
                        <div class="h-14 w-14 bg-transparent rounded-2xl flex items-center justify-center">
                            <img src="/images/logo-guitars.png" alt="Arya Guitar Logo" class="h-12 w-auto object-contain" />
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
                            <p class="text-red-100 font-bold leading-relaxed">
                                Anda harus register terlebih dahulu atau login untuk memesan gitar!
                            </p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <Link :href="login()">
                                <Button variant="outline" class="w-full bg-transparent border-arya-gold/30 text-white hover:bg-arya-gold hover:text-white font-bold py-6 transition-all">Login</Button>
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
                            <h3 class="text-2xl font-black text-white mb-2">Pemesanan Berhasil!</h3>
                            <p class="text-white/40 text-sm mb-6">Pesanan Anda telah diterima. Silakan konfirmasi via WhatsApp untuk mempercepat proses.</p>
                            
                            <!-- Order Summary -->
                            <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-8 text-left space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-white/40">Kode Pesanan:</span>
                                    <span class="text-white font-bold">{{ page.props.flash.new_order.order_code }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-white/40">Gitar:</span>
                                    <span class="text-arya-gold font-bold">{{ page.props.flash.new_order.guitar?.name || page.props.flash.new_order.guitar_type }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-white/40">Pemesan:</span>
                                    <span class="text-white font-bold">{{ page.props.flash.new_order.customer.name }}</span>
                                </div>
                            </div>

                            <a 
                                :href="`https://wa.me/6285788241715?text=Halo%2C%20saya%20${encodeURIComponent(page.props.flash.new_order.customer.name)}%20ingin%20konfirmasi%20pesanan%20${encodeURIComponent(page.props.flash.new_order.guitar?.name || 'Custom Guitar')}%20dengan%20kode%20${encodeURIComponent(page.props.flash.new_order.order_code)}.`"
                                target="_blank"
                                class="inline-flex items-center justify-center gap-2 w-full bg-green-500 text-white font-black py-4 rounded-xl hover:bg-green-600 transition-all shadow-lg shadow-green-500/20"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Konfirmasi ke WhatsApp
                            </a>
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

option {
    color: black;
    background-color: white;
}
</style>
