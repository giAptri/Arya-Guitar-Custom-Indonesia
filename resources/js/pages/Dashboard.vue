<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);


const props = defineProps<{
    stats: Array<{ title: string; value: number }>;
    salesChart: Array<{ label: string; height: string; income: number; formatted_income: string }>;
}>();

</script>

<template>
    <Head title="Laporan Penjualan" />

    <AdminLayout>
        <div class="mt-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h1 class="text-3xl font-black text-black">
                    Selamat datang kembali <span class="text-arya-gold">{{ user.name }}</span> !
                </h1>

                <div class="flex gap-3">
                    <a href="/dashboard/export/excel" target="_blank" class="px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-green-700 transition-all flex items-center gap-2 hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                    <a href="/dashboard/export/pdf" target="_blank" class="px-5 py-2.5 bg-red-600 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-red-700 transition-all flex items-center gap-2 hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Export PDF
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-8 mt-12">
                <div v-for="stat in props.stats" :key="stat.title" class="bg-white p-8 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-50 flex flex-col items-center">
                    <span class="text-sm font-black text-black uppercase tracking-wider mb-2">{{ stat.title }}</span>
                    <span class="text-6xl font-black text-arya-gold leading-none">{{ stat.value }}</span>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="mt-16 bg-white p-12 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-50">
                <h2 class="text-2xl font-black text-black text-center mb-16 uppercase tracking-widest">Penjualan Bulanan (Live Data)</h2>
                
                <!-- Chart Area -->
                <div class="h-64 flex items-end justify-between px-2 gap-2 mt-8 mb-4">
                    <div v-for="(bar, index) in props.salesChart" :key="index" class="w-full h-full flex items-end justify-center relative group">
                        <!-- Bar -->
                        <div class="w-full bg-arya-gold rounded-t-sm transition-all duration-500 hover:brightness-110 relative" :style="{ height: bar.height }">
                            <!-- Tooltip -->
                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-10">
                                <div class="bg-black text-white text-xs py-1 px-2 rounded whitespace-nowrap font-bold shadow-lg">
                                    {{ bar.formatted_income }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Labels Area -->
                <div class="flex justify-between px-2 gap-2">
                    <div v-for="(bar, index) in props.salesChart" :key="index" class="w-full text-center">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter block">{{ bar.label }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
/* Adjustments for the clean dashboard look */
</style>
