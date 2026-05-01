<script setup lang="ts">
import { Head, usePage, router } from '@inertiajs/vue3';
import AdminLayout from '@/layouts/AdminLayout.vue';
import { computed, ref, watch } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const props = defineProps<{
    stats: Array<{ title: string; value: number }>;
    salesChart: Array<{ label: string; height: string; income: number; formatted_income: string }>;
    selectedYear: number;
    selectedMonth: number;
}>();

// State filter periode
const currentYear = new Date().getFullYear();
const selectedYear = ref(props.selectedYear ?? currentYear);
const selectedMonth = ref(props.selectedMonth ?? new Date().getMonth() + 1);

const years = Array.from({ length: 5 }, (_, i) => currentYear - i);

const months = [
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

const selectedMonthLabel = computed(() => {
    return months.find(m => m.value === selectedMonth.value)?.label ?? '';
});

// Terapkan filter ke backend via Inertia
function applyFilter() {
    router.get('/dashboard', {
        year: selectedYear.value,
        month: selectedMonth.value,
    }, {
        preserveState: false,
        replace: true,
    });
}

// URL Export dengan periode yang dipilih
const excelUrl = computed(() =>
    `/dashboard/export/excel?year=${selectedYear.value}&month=${selectedMonth.value}`
);
const pdfUrl = computed(() =>
    `/dashboard/export/pdf?year=${selectedYear.value}&month=${selectedMonth.value}`
);
</script>

<template>
    <Head title="Laporan Penjualan" />

    <AdminLayout>
        <div class="mt-8">
            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <h1 class="text-3xl font-black text-black">
                    Selamat datang kembali <span class="text-arya-gold">{{ user.name }}</span> !
                </h1>

                <div class="flex gap-3">
                    <a :href="excelUrl" target="_blank"
                        class="px-5 py-2.5 bg-green-600 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-green-700 transition-all flex items-center gap-2 hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </a>
                    <a :href="pdfUrl" target="_blank"
                        class="px-5 py-2.5 bg-red-600 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-red-700 transition-all flex items-center gap-2 hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Export PDF
                    </a>
                </div>
            </div>

            <!-- Filter Periode -->
            <div class="mt-8 bg-white p-6 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-50">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-arya-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-black text-black uppercase tracking-wider">Filter Periode</span>
                    </div>

                    <div class="flex flex-wrap gap-3 items-center">
                        <!-- Pilih Bulan -->
                        <select
                            v-model="selectedMonth"
                            id="filter-month"
                            class="px-4 py-2 text-sm font-semibold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-arya-gold transition-colors cursor-pointer bg-white text-gray-700 hover:border-arya-gold">
                            <option v-for="m in months" :key="m.value" :value="m.value">{{ m.label }}</option>
                        </select>

                        <!-- Pilih Tahun -->
                        <select
                            v-model="selectedYear"
                            id="filter-year"
                            class="px-4 py-2 text-sm font-semibold border-2 border-gray-200 rounded-xl focus:outline-none focus:border-arya-gold transition-colors cursor-pointer bg-white text-gray-700 hover:border-arya-gold">
                            <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
                        </select>

                        <!-- Tombol Terapkan -->
                        <button
                            id="btn-apply-filter"
                            @click="applyFilter"
                            class="px-6 py-2 bg-arya-gold text-black text-sm font-black rounded-xl shadow hover:brightness-110 transition-all hover:-translate-y-0.5 active:scale-95">
                            Terapkan
                        </button>
                    </div>

                    <!-- Info Periode Aktif -->
                    <div class="ml-auto">
                        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-amber-50 border border-amber-200 rounded-full text-xs font-bold text-amber-700">
                            <span class="w-2 h-2 rounded-full bg-arya-gold animate-pulse inline-block"></span>
                            {{ selectedMonthLabel }} {{ selectedYear }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-3 gap-8 mt-8">
                <div v-for="stat in props.stats" :key="stat.title"
                    class="bg-white p-8 rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-50 flex flex-col items-center">
                    <span class="text-sm font-black text-black uppercase tracking-wider mb-2">{{ stat.title }}</span>
                    <span class="text-6xl font-black text-arya-gold leading-none">{{ stat.value }}</span>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="mt-10 bg-white p-12 rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-gray-50">
                <h2 class="text-2xl font-black text-black text-center mb-2 uppercase tracking-widest">
                    Penjualan Bulanan
                </h2>
                <p class="text-center text-sm text-gray-400 font-semibold mb-12">
                    Tahun {{ selectedYear }}
                </p>

                <!-- Chart Area -->
                <div class="h-64 flex items-end justify-between px-2 gap-2 mt-8 mb-4">
                    <div v-for="(bar, index) in props.salesChart" :key="index"
                        class="w-full h-full flex items-end justify-center relative group">
                        <!-- Bar -->
                        <div class="w-full rounded-t-sm transition-all duration-500 hover:brightness-110 relative"
                            :class="bar.income > 0 ? 'bg-arya-gold' : 'bg-gray-100'"
                            :style="{ height: bar.height === '0%' ? '4px' : bar.height }">
                            <!-- Tooltip -->
                            <div v-if="bar.income > 0"
                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-10">
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
select {
    appearance: auto;
}
</style>
