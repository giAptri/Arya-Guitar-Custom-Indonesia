<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const stats = [
    { title: 'Total Produk', value: '20' },
    { title: 'Pesanan Bulan Ini', value: '13' },
    { title: 'Jumlah Pelanggan', value: '50' },
];

const salesData = [
    { label: 'Jan', height: '40%' },
    { label: 'Feb', height: '60%' },
    { label: 'Mar', height: '80%' },
    { label: 'Apr', height: '65%' },
    { label: 'Mei', height: '65%' },
    { label: 'Jun', height: '70%' },
    { label: 'Jul', height: '45%' },
    { label: 'Agu', height: '75%' },
    { label: 'Sep', height: '42%' },
    { label: 'Okt', height: '55%' },
    { label: 'Nov', height: '65%' },
    { label: 'Des', height: '85%' },
];
</script>

<template>
    <Head title="Laporan Penjualan" />

    <AdminLayout>
        <div class="mt-8">
            <h1 class="text-3xl font-black text-black">
                Selamat datang kembali <span class="text-arya-gold">{{ user.name }}</span> !
            </h1>

            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-8 mt-12">
                <div v-for="stat in stats" :key="stat.title" class="bg-white p-8 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-50 flex flex-col items-center">
                    <span class="text-sm font-black text-black uppercase tracking-wider mb-2">{{ stat.title }}</span>
                    <span class="text-6xl font-black text-arya-gold leading-none">{{ stat.value }}</span>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="mt-16 bg-white p-12 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-50">
                <h2 class="text-2xl font-black text-black text-center mb-16 uppercase tracking-widest">Penjualan Bulanan</h2>
                
                <div class="h-80 flex items-end justify-between px-8 relative gap-2">
                    <!-- Y-Axis labels (dummy) -->
                    <div class="absolute left-0 bottom-0 top-0 flex flex-col justify-between text-[10px] font-bold text-gray-300">
                        <span>50</span>
                        <span>40</span>
                        <span>30</span>
                        <span>20</span>
                        <span>10</span>
                        <span>0</span>
                    </div>

                    <div v-for="(bar, index) in salesData" :key="index" class="flex flex-col items-center flex-1">
                        <div class="w-full bg-arya-gold rounded-t-md transition-all hover:brightness-110" :style="{ height: bar.height }"></div>
                        <span class="mt-4 text-[10px] font-black text-gray-400 uppercase tracking-tighter">{{ bar.label }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
/* Adjustments for the clean dashboard look */
</style>
