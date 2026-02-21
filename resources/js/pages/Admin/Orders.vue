<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { 
    Search, 
    Image as ImageIcon,
    ExternalLink,
    Loader2
} from 'lucide-vue-next';
import { ref, computed, onMounted, watch } from 'vue';
import axios from 'axios';

const searchQuery = ref('');
const orders = ref([]);
const isLoading = ref(true);
const statusOptions = ['pending', 'produksi', 'selesai'];

const fetchOrders = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get('/admin/data/orders', {
            params: {
                search: searchQuery.value,
                per_page: 50 // Fetch enough for now
            }
        });
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
                <div class="p-8 flex items-center justify-between border-b border-gray-50 gap-4">
                    <div class="flex items-center gap-2 text-sm font-bold text-gray-400">
                        <span>Show</span>
                        <select class="bg-gray-50 border-none rounded-lg px-2 py-1 text-black focus:ring-0">
                            <option>7</option>
                            <option>10</option>
                            <option>20</option>
                        </select>
                        <span>entries</span>
                    </div>

                    <div class="flex items-center gap-4 flex-1 max-w-2xl px-8">
                        <div class="relative w-full">
                            <Search class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-300" />
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Search..." 
                                class="w-full bg-gray-50 border-none rounded-xl pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-arya-gold/20 transition-all"
                            />
                        </div>
                    </div>

                    <div class="hidden lg:block">
                        <button class="bg-[#2d5a7d] hover:bg-[#254a68] text-white px-6 py-3 rounded-xl font-bold text-sm">
                            Refresh Data
                        </button>
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
                                </td>
                                <td class="px-8 py-6 border-r border-gray-50">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs font-bold text-gray-600 w-20">Orientation:</span>
                                            <span class="text-xs text-black uppercase">{{ order.orientation }}</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <span class="text-xs font-bold text-gray-600 w-20 flex-shrink-0">Notes:</span>
                                            <span class="text-xs text-gray-500 italic line-clamp-2" :title="order.notes">{{ order.notes || '-' }}</span>
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
