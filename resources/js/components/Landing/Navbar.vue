<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { dashboard, login, logout } from '@/routes';
import AppLogo from '@/components/AppLogo.vue';
import { Button } from '@/components/ui/button';
import { Menu, LogOut } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const navItems = [
    { title: 'Beranda', href: '/' },
    { title: 'Katalog', href: '#catalog' },
    { title: 'Tentang Kami', href: '#about' },
    { title: 'Support', href: '#contact' },
];

const logoutForm = useForm({});
const handleLogout = () => {
    logoutForm.post(logout().url);
};
</script>

<template>
    <nav 
        class="fixed top-0 z-50 w-full transition-all duration-300"
        :class="isScrolled ? 'bg-arya-dark/95 py-3 shadow-lg backdrop-blur-sm' : 'bg-transparent py-6'"
    >
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4">
            <!-- Logo -->
            <Link :href="dashboard()" class="flex items-center gap-2">
                <AppLogo class="h-10 w-auto" />
            </Link>

            <!-- Desktop Navigation -->
            <div class="hidden items-center gap-8 lg:flex">
                <a 
                    v-for="item in navItems" 
                    :key="item.title" 
                    :href="item.href"
                    class="text-sm font-medium text-white/80 transition-colors hover:text-arya-gold"
                >
                    {{ item.title }}
                </a>
                <!-- Auth Links -->
                <template v-if="!$page.props.auth.user">
                    <Link :href="login()">
                        <Button class="bg-arya-gold font-black text-black hover:bg-white hover:text-black transition-all rounded-full px-8 shadow-[0_0_20px_rgba(248,184,3,0.3)] hover:shadow-[0_0_30px_rgba(248,184,3,0.6)] hover:-translate-y-1">
                            Masuk
                        </Button>
                    </Link>

                </template>
                <template v-else>
                    <Link v-if="$page.props.auth.user.role === 'admin'" :href="dashboard().url">
                        <Button class="bg-arya-gold font-bold text-black hover:bg-arya-gold/90">
                            Dashboard
                        </Button>
                    </Link>
                    <button 
                        @click="handleLogout"
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white transition-all hover:bg-red-500 hover:text-white"
                        title="Logout"
                    >
                        <LogOut class="h-5 w-5" />
                    </button>
                </template>
            </div>

            <!-- Mobile Toggle -->
            <button class="lg:hidden text-white">
                <Menu class="h-6 w-6" />
            </button>
        </div>
    </nav>
</template>
