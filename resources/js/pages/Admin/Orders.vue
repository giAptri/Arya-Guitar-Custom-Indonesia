<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { 
    Search, 
    Image as ImageIcon,
    ExternalLink
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

const searchQuery = ref('');

const orders = ref([
    { id: 1, guitar_name: 'Arya Stratosphere', custom_image: null, custom_text: 'Body mahogany dengan warna biru laut doff', customer_name: 'Jhon Doe', whatsapp: '628123456789', status: 'Produksi' },
    { id: 2, guitar_name: 'Arya Flame', custom_image: null, custom_text: 'Bridge gold plated, knob warna hitam', customer_name: 'Jane Smith', whatsapp: '628987654321', status: 'Pending' },
    { id: 3, guitar_name: 'Arya Sahaya', custom_image: null, custom_text: 'Inlay custom bentuk bintang emas', customer_name: 'Budi Santoso', whatsapp: '628554433221', status: 'Produksi' },
    { id: 4, guitar_name: 'Arya Bloom', custom_image: null, custom_text: 'Fretboard rosewood, dryer warna chrome', customer_name: 'Alice Wonder', whatsapp: '628112233445', status: 'Selesai' },
]);

const statusOptions = ['Produksi', 'Pending', 'Selesai'];

const filteredOrders = computed(() => {
    return orders.value.filter(o => 
        o.guitar_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        o.customer_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const getStatusClass = (status: string) => {
    switch (status) {
        case 'Produksi': return 'bg-[#d6e9f8] text-[#3b82f6]';
        case 'Pending': return 'bg-[#fef3c7] text-[#f59e0b]';
        case 'Selesai': return 'bg-[#dcfce7] text-[#22c55e]';
        default: return 'bg-gray-100 text-gray-400';
    }
};

const handleStatusChange = (order: any, newStatus: string) => {
    order.status = newStatus;
};

const openWhatsApp = (phone: string) => {
    window.open(`https://wa.me/${phone}`, '_blank');
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
                            <tr v-for="order in filteredOrders" :key="order.id" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6 font-bold text-black border-r border-gray-50">{{ order.guitar_name }}</td>
                                <td class="px-8 py-6 border-r border-gray-50">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 flex-shrink-0 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                            <ImageIcon class="h-5 w-5" />
                                        </div>
                                        <span class="text-xs text-gray-500 italic">{{ order.custom_text }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 border-r border-gray-50 text-center">
                                    <span class="block font-bold text-black text-sm">{{ order.customer_name }}</span>
                                    <button 
                                        @click="openWhatsApp(order.whatsapp)"
                                        class="mt-1 text-[10px] text-green-600 hover:text-green-700 font-bold flex items-center justify-center gap-1 mx-auto"
                                    >
                                        {{ order.whatsapp }}
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
