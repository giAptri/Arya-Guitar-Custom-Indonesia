<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Package, 
    ClipboardCheck, 
    Users, 
    LogOut,
    TrendingUp
} from 'lucide-vue-next';
import { logout, dashboard, home } from '@/routes';
import { computed } from 'vue';

const page = usePage();
const currentUrl = computed(() => page.url);

const menuItems = [
    { 
        title: 'Laporan Penjualan', 
        icon: TrendingUp, 
        href: '/dashboard', // Fixed for this demo, should be dashboard().url
        active: currentUrl.value === '/dashboard'
    },
    { 
        title: 'Kelola Gitar', 
        icon: Package, 
        href: '/admin/guitars',
        active: currentUrl.value.startsWith('/admin/guitars')
    },
    { 
        title: 'Pesanan Gitar', 
        icon: ClipboardCheck, 
        href: '/admin/orders',
        active: currentUrl.value.startsWith('/admin/orders')
    },
    { 
        title: 'Pelanggan Terbaru', 
        icon: Users, 
        href: '#', // Per user request: "sidebar pelanggan terbaru tidak perlu" - wait, user said "sidebar pelanggan terbaru tidak perlu" in the text, but the screenshot has it. 
        // Re-reading: "untuk sidebar pelanggan terbaru tidak perlu"
        // Okay, I will remove it from the menu if the user said it's not needed.
        active: false
    },
].filter(item => item.title !== 'Pelanggan Terbaru');

const logoutForm = useForm({});
const handleLogout = () => {
    logoutForm.post(logout().url);
};
</script>

<template>
    <aside class="fixed left-0 top-0 h-screen w-72 bg-black flex flex-col items-center py-10">
        <!-- Brand Logo -->
        <Link :href="home()" class="mb-16 px-8">
            <div class="flex items-center gap-2">
                <img src="https://raw.githubusercontent.com/Arya-Guitar/Arya-Guitar-Custom-Indonesia/refs/heads/main/logo-gold.png" alt="Arya Guitar" class="h-16 w-auto" @error="(e) => (e.target as HTMLImageElement).src = '/logo-gold-placeholder.png'" />
                <!-- Fallback to text if image fails -->
                <div v-if="false" class="flex flex-col">
                    <span class="text-2xl font-black text-arya-gold leading-none italic uppercase">Arya</span>
                    <span class="text-xs text-white/50 tracking-[0.3em] uppercase">Guitar</span>
                </div>
            </div>
        </Link>

        <!-- Navigation Menu -->
        <nav class="w-full px-4 flex-1">
            <div class="space-y-2">
                <Link 
                    v-for="item in menuItems" 
                    :key="item.title"
                    :href="item.href"
                    class="flex items-center gap-4 px-6 py-4 rounded-xl transition-all group"
                    :class="item.active 
                        ? 'bg-arya-gold/10 text-arya-gold border border-arya-gold/20' 
                        : 'text-white hover:bg-white/5 hover:text-arya-gold'"
                >
                    <component :is="item.icon" class="h-6 w-6" :class="item.active ? 'text-arya-gold' : 'text-white group-hover:text-arya-gold'" />
                    <span class="font-bold text-sm tracking-wide">{{ item.title }}</span>
                </Link>
            </div>
        </nav>

        <!-- Logout Button -->
        <div class="w-full px-8 mt-auto">
            <button 
                @click="handleLogout"
                class="flex w-full items-center justify-center gap-3 py-4 rounded-xl border border-arya-gold/30 text-arya-gold font-bold hover:bg-arya-gold hover:text-black transition-all"
            >
                <LogOut class="h-5 w-5 rotate-180" />
                <span>Logout</span>
            </button>
        </div>
    </aside>
</template>

<style scoped>
/* Custom sidebar styling to match exact look */
nav {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
nav::-webkit-scrollbar {
    display: none;
}
</style>
