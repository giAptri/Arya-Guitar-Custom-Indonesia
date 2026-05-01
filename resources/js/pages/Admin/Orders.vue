<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { 
    Search, 
    Image as ImageIcon,
    ExternalLink,
    Loader2,
    X,
    ZoomIn,
    Eye,
    Guitar,
    User,
    FileText,
    Calendar,
    DollarSign,
    Phone
} from 'lucide-vue-next';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';

interface Order {
    id: number;
    order_code: string;
    guitar_type: string;
    orientation: string;
    pickup_config: string | null;
    notes: string;
    estimated_price: number | null;
    status: string;
    reference_photo: string | null;
    customer_name: string;
    customer_phone: string;
    created_at: string;
    updated_at: string;
    guitar?: { name: string };
    customer?: { name: string; phone: string };
}

const searchQuery = ref('');
const orders = ref<Order[]>([]);
const isLoading = ref(true);
const statusOptions = ['pending', 'produksi', 'selesai'];

// ── Filter Periode ────────────────────────────────────────────
const currentYear  = new Date().getFullYear();
const currentMonth = new Date().getMonth() + 1;

const filterYear  = ref<number | ''>(currentYear);
const filterMonth = ref<number | ''>(currentMonth);

const monthsList = [
    { value: 1,  label: 'Januari' },
    { value: 2,  label: 'Februari' },
    { value: 3,  label: 'Maret' },
    { value: 4,  label: 'April' },
    { value: 5,  label: 'Mei' },
    { value: 6,  label: 'Juni' },
    { value: 7,  label: 'Juli' },
    { value: 8,  label: 'Agustus' },
    { value: 9,  label: 'September' },
    { value: 10, label: 'Oktober' },
    { value: 11, label: 'November' },
    { value: 12, label: 'Desember' },
];

const yearsList = Array.from({ length: 5 }, (_, i) => currentYear - i);

const activePeriodLabel = computed(() => {
    if (!filterMonth.value && !filterYear.value) return 'Semua Periode';
    const ml = filterMonth.value
        ? monthsList.find(m => m.value === filterMonth.value)?.label
        : null;
    const yl = filterYear.value ? String(filterYear.value) : null;
    return [ml, yl].filter(Boolean).join(' ');
});

const isFilterActive = computed(() =>
    filterMonth.value !== '' || filterYear.value !== ''
);

function resetFilter() {
    filterYear.value  = '';
    filterMonth.value = '';
    fetchOrders();
}

// Lightbox state for reference photos
const lightboxPhoto = ref<string | null>(null);
const openLightbox = (url: string) => { lightboxPhoto.value = url; };
const closeLightbox = () => { lightboxPhoto.value = null; };

// Detail modal state
const detailOrder = ref<Order | null>(null);
const openDetail = (order: Order) => { detailOrder.value = order; };
const closeDetail = () => { detailOrder.value = null; };

// Helpers
const formatPrice = (price: number | null) => {
    if (!price) return '-';
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
};
const formatDate = (dateStr: string) => {
    if (!dateStr) return '-';
    return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const fetchOrders = async () => {
    isLoading.value = true;
    try {
        const params: Record<string, any> = {
            search:   searchQuery.value,
            per_page: 100,
        };
        if (filterYear.value)  params.year  = filterYear.value;
        if (filterMonth.value) params.month = filterMonth.value;

        const response = await axios.get('/admin/data/orders', { params });
        orders.value = response.data.data;
    } catch (error) {
        console.error('Error fetching orders:', error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchOrders();
});

// Debounce search
let searchTimeout: ReturnType<typeof setTimeout>;
watch(searchQuery, () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        fetchOrders();
    }, 500);
});

const getStatusClass = (status: string) => {
    switch (status) {
        case 'produksi': return 'bg-[#d6e9f8] text-[#3b82f6]';
        case 'pending': return 'bg-[#fef3c7] text-[#f59e0b]';
        case 'selesai': return 'bg-[#dcfce7] text-[#22c55e]';
        default: return 'bg-gray-100 text-gray-400';
    }
};

const handleStatusChange = async (order: any, newStatus: string) => {
    const originalStatus = order.status;
    order.status = newStatus; // Optimistic update
    
    try {
        await axios.put(`/admin/data/orders/${order.id}/status`, {
            status: newStatus
        });
        // Success notification could go here
    } catch (error) {
        console.error('Error updating status:', error);
        order.status = originalStatus; // Revert on error
        alert('Gagal memperbarui status pesanan.');
    }
};

const openWhatsApp = (phone: string) => {
    // Format phone number to clean non-numeric characters
    const cleanPhone = phone.replace(/\D/g, '');
    // Ensure Indonesia country code
    const finalPhone = cleanPhone.startsWith('0') ? '62' + cleanPhone.slice(1) : cleanPhone;
    window.open(`https://wa.me/${finalPhone}`, '_blank');
};
</script>

<template>
    <Head title="Pesanan Gitar" />

    <AdminLayout>
        <div class="mt-8">
            <h1 class="text-3xl font-black text-black text-center">
                Validasi <span class="text-arya-gold">pesanan</span> mu!
            </h1>

            <div class="mt-12 bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-50 overflow-hidden">
                <!-- Toolbar -->
                <div class="p-6 flex flex-col gap-4 border-b border-gray-50">
                    <!-- Row 1: Search + Refresh -->
                    <div class="flex items-center gap-4">
                        <div class="relative flex-1">
                            <Search class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-300" />
                            <input
                                id="orders-search"
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari kode pesanan, nama pelanggan..."
                                class="w-full bg-gray-50 border-none rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-arya-gold/20 transition-all"
                            />
                        </div>
                        <button
                            id="btn-refresh-orders"
                            @click="fetchOrders"
                            class="bg-[#2d5a7d] hover:bg-[#254a68] text-white px-6 py-3 rounded-xl font-bold text-sm transition-colors flex-shrink-0 flex items-center gap-2">
                            Refresh Data
                        </button>
                    </div>

                    <!-- Row 2: Filter Periode -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Ikon Kalender -->
                        <div class="flex items-center gap-1.5 text-xs font-black text-gray-400 uppercase tracking-wider flex-shrink-0">
                            <Calendar class="h-4 w-4 text-arya-gold" />
                            Filter Periode:
                        </div>

                        <!-- Pilih Bulan -->
                        <select
                            id="filter-order-month"
                            v-model.number="filterMonth"
                            class="px-3 py-2 text-sm font-semibold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-arya-gold transition-colors cursor-pointer bg-white text-gray-700 hover:border-arya-gold">
                            <option value="">Semua Bulan</option>
                            <option v-for="m in monthsList" :key="m.value" :value="m.value">{{ m.label }}</option>
                        </select>

                        <!-- Pilih Tahun -->
                        <select
                            id="filter-order-year"
                            v-model.number="filterYear"
                            class="px-3 py-2 text-sm font-semibold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-arya-gold transition-colors cursor-pointer bg-white text-gray-700 hover:border-arya-gold">
                            <option value="">Semua Tahun</option>
                            <option v-for="y in yearsList" :key="y" :value="y">{{ y }}</option>
                        </select>

                        <!-- Tombol Terapkan -->
                        <button
                            id="btn-apply-order-filter"
                            @click="fetchOrders"
                            class="px-5 py-2 bg-arya-gold text-black text-sm font-black rounded-xl hover:brightness-110 transition-all hover:-translate-y-0.5 active:scale-95">
                            Terapkan
                        </button>

                        <!-- Tombol Reset -->
                        <button
                            v-if="isFilterActive"
                            id="btn-reset-order-filter"
                            @click="resetFilter"
                            class="px-4 py-2 bg-gray-100 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-200 transition-all flex items-center gap-1.5">
                            <X class="h-3.5 w-3.5" />
                            Reset
                        </button>

                        <!-- Badge Periode Aktif -->
                        <div class="ml-auto">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 border border-amber-200 rounded-full text-xs font-bold text-amber-700">
                                <span class="w-2 h-2 rounded-full bg-arya-gold animate-pulse inline-block"></span>
                                {{ activePeriodLabel }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-arya-gold text-white uppercase text-[10px] font-black tracking-widest">
                                <th class="px-8 py-4 border-r border-white/20 w-1/4">Nama Gitar</th>
                                <th class="px-8 py-4 border-r border-white/20 w-1/3">Custom</th>
                                <th class="px-8 py-4 border-r border-white/20">Pelanggan</th>
                                <th class="px-8 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-if="isLoading">
                                <td colspan="4" class="px-8 py-12 text-center text-gray-400">
                                    <Loader2 class="h-8 w-8 animate-spin mx-auto mb-2" />
                                    Loading data...
                                </td>
                            </tr>
                            <tr v-else-if="orders.length === 0">
                                <td colspan="4" class="px-8 py-12 text-center text-gray-400">
                                    Belum ada pesanan yang masuk via custom order.
                                </td>
                            </tr>
                            <tr v-else v-for="order in orders" :key="order.id" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6 font-bold text-black border-r border-gray-50">
                                    {{ order.guitar?.name || 'Unknown Guitar' }}
                                    <span class="block text-[10px] uppercase font-normal text-gray-400 mt-1">{{ order.guitar_type }} Series</span>
                                    <button 
                                        @click="openDetail(order)"
                                        class="mt-2 inline-flex items-center gap-1.5 text-[11px] font-bold text-[#2d5a7d] hover:text-[#1e3f57] bg-[#2d5a7d]/10 hover:bg-[#2d5a7d]/20 px-3 py-1.5 rounded-lg transition-all"
                                    >
                                        <Eye class="h-3.5 w-3.5" />
                                        Detail
                                    </button>
                                </td>
                                <td class="px-8 py-6 border-r border-gray-50">
                                    <div class="flex gap-4">
                                        <!-- Reference Photo Thumbnail -->
                                        <div class="flex-shrink-0">
                                            <div 
                                                v-if="order.reference_photo" 
                                                @click="openLightbox(order.reference_photo)"
                                                class="relative w-16 h-16 rounded-xl overflow-hidden border-2 border-gray-100 cursor-pointer group hover:border-arya-gold transition-colors shadow-sm"
                                            >
                                                <img :src="order.reference_photo" alt="Foto Referensi" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                                    <ZoomIn class="h-4 w-4 text-white opacity-0 group-hover:opacity-100 transition-opacity" />
                                                </div>
                                            </div>
                                            <div v-else class="w-16 h-16 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center bg-gray-50">
                                                <ImageIcon class="h-5 w-5 text-gray-300" />
                                            </div>
                                        </div>
                                        <!-- Details -->
                                        <div class="flex flex-col gap-1 justify-center">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-gray-600 w-20">Orientation:</span>
                                                <span class="text-xs text-black uppercase">{{ order.orientation }}</span>
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <span class="text-xs font-bold text-gray-600 w-20 flex-shrink-0">Notes:</span>
                                                <span class="text-xs text-gray-500 italic line-clamp-2" :title="order.notes">{{ order.notes || '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 border-r border-gray-50 text-center">
                                    <span class="block font-bold text-black text-sm">{{ order.customer?.name || 'Unknown' }}</span>
                                    <button 
                                        @click="openWhatsApp(order.customer?.phone || '')"
                                        class="mt-1 text-[10px] text-green-600 hover:text-green-700 font-bold flex items-center justify-center gap-1 mx-auto"
                                    >
                                        {{ order.customer?.phone || '-' }}
                                        <ExternalLink class="h-3 w-3" />
                                    </button>
                                </td>
                                <td class="px-8 py-6">
                                    <select 
                                        @change="handleStatusChange(order, ($event.target as HTMLSelectElement).value)"
                                        class="w-full rounded-xl px-4 py-2 text-xs font-black uppercase text-center focus:ring-0 cursor-pointer transition-colors border-none"
                                        :class="getStatusClass(order.status)"
                                    >
                                        <option v-for="opt in statusOptions" :key="opt" :value="opt" :selected="order.status === opt">
                                            {{ opt }}
                                        </option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Detail Modal -->
        <div v-if="detailOrder" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-md" @click="closeDetail"></div>
            <div class="relative w-full max-w-2xl bg-[#1a1a2e] border border-white/10 rounded-3xl overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-300 max-h-[90vh] overflow-y-auto">
                <!-- Header -->
                <div class="sticky top-0 z-10 bg-gradient-to-r from-[#d4a017] to-[#b88b14] px-8 py-5 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-white">Detail Pesanan</h2>
                        <p class="text-white/70 text-xs mt-0.5 font-mono">{{ detailOrder.order_code }}</p>
                    </div>
                    <button @click="closeDetail" class="text-white/60 hover:text-white bg-white/10 hover:bg-white/20 rounded-full p-2 transition-colors">
                        <X class="h-5 w-5" />
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    <!-- Status Badge -->
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Status Pesanan</span>
                        <span 
                            class="px-4 py-1.5 rounded-full text-xs font-black uppercase"
                            :class="{
                                'bg-yellow-500/20 text-yellow-400': detailOrder.status === 'pending',
                                'bg-blue-500/20 text-blue-400': detailOrder.status === 'produksi',
                                'bg-green-500/20 text-green-400': detailOrder.status === 'selesai',
                            }"
                        >
                            {{ detailOrder.status }}
                        </span>
                    </div>

                    <!-- Guitar Info Section -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5 space-y-4">
                        <div class="flex items-center gap-2 mb-3">
                            <Guitar class="h-4 w-4 text-arya-gold" />
                            <span class="text-xs font-black text-white/50 uppercase tracking-widest">Informasi Gitar</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-[10px] text-white/30 uppercase font-bold mb-1">Nama Gitar</span>
                                <span class="text-white font-bold text-sm">{{ detailOrder.guitar?.name || 'Custom Guitar' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-white/30 uppercase font-bold mb-1">Tipe</span>
                                <span class="text-white font-bold text-sm uppercase">{{ detailOrder.guitar_type }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-white/30 uppercase font-bold mb-1">Orientation</span>
                                <span class="text-white font-bold text-sm uppercase">{{ detailOrder.orientation === 'right' ? 'Kanan (Standard)' : 'Kiri (Lefty)' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-white/30 uppercase font-bold mb-1">Pickup Config</span>
                                <span class="text-white font-bold text-sm">{{ detailOrder.pickup_config || '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Budget Section -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <DollarSign class="h-4 w-4 text-arya-gold" />
                            <span class="text-xs font-black text-white/50 uppercase tracking-widest">Estimasi Bajet</span>
                        </div>
                        <span class="text-2xl font-black text-arya-gold">{{ formatPrice(detailOrder.estimated_price) }}</span>
                    </div>

                    <!-- Notes Section -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <FileText class="h-4 w-4 text-arya-gold" />
                            <span class="text-xs font-black text-white/50 uppercase tracking-widest">Detail Kustom / Notes</span>
                        </div>
                        <p class="text-white/70 text-sm leading-relaxed whitespace-pre-wrap">{{ detailOrder.notes || 'Tidak ada catatan.' }}</p>
                    </div>

                    <!-- Reference Photo Section -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <ImageIcon class="h-4 w-4 text-arya-gold" />
                            <span class="text-xs font-black text-white/50 uppercase tracking-widest">Foto Referensi</span>
                        </div>
                        <div v-if="detailOrder.reference_photo" class="relative group cursor-pointer" @click="openLightbox(detailOrder.reference_photo); closeDetail()">
                            <img :src="detailOrder.reference_photo" alt="Foto Referensi" class="w-full max-h-64 object-contain rounded-xl border border-white/10" />
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors rounded-xl flex items-center justify-center">
                                <ZoomIn class="h-6 w-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" />
                            </div>
                        </div>
                        <div v-else class="flex items-center justify-center py-6 border-2 border-dashed border-white/10 rounded-xl">
                            <span class="text-white/30 text-sm">Tidak ada foto referensi</span>
                        </div>
                    </div>

                    <!-- Customer Section -->
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5 space-y-4">
                        <div class="flex items-center gap-2 mb-3">
                            <User class="h-4 w-4 text-arya-gold" />
                            <span class="text-xs font-black text-white/50 uppercase tracking-widest">Informasi Pelanggan</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-[10px] text-white/30 uppercase font-bold mb-1">Nama</span>
                                <span class="text-white font-bold text-sm">{{ detailOrder.customer?.name || detailOrder.customer_name || '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-[10px] text-white/30 uppercase font-bold mb-1">WhatsApp</span>
                                <button 
                                    @click="openWhatsApp(detailOrder.customer?.phone || detailOrder.customer_phone || '')"
                                    class="text-green-400 hover:text-green-300 font-bold text-sm flex items-center gap-1.5 transition-colors"
                                >
                                    <Phone class="h-3.5 w-3.5" />
                                    {{ detailOrder.customer?.phone || detailOrder.customer_phone || '-' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="flex items-center gap-2 text-white/20 text-[10px] pt-2">
                        <Calendar class="h-3 w-3" />
                        <span>Dibuat: {{ formatDate(detailOrder.created_at) }}</span>
                        <span class="mx-1">•</span>
                        <span>Diperbarui: {{ formatDate(detailOrder.updated_at) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Lightbox Modal -->
        <div v-if="lightboxPhoto" class="fixed inset-0 z-[60] flex items-center justify-center p-4" @click.self="closeLightbox">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="closeLightbox"></div>
            <div class="relative max-w-3xl max-h-[85vh] animate-in fade-in zoom-in duration-200">
                <button @click="closeLightbox" class="absolute -top-3 -right-3 z-10 bg-white text-black rounded-full p-1.5 shadow-lg hover:bg-gray-100 transition-colors">
                    <X class="h-5 w-5" />
                </button>
                <img :src="lightboxPhoto" alt="Foto Referensi" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl object-contain" />
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='C19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.5rem center;
    background-size: 1em;
}
</style>
