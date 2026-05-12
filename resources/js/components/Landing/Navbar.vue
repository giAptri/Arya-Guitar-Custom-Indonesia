<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { dashboard, login, logout } from '@/routes';
import AppLogo from '@/components/AppLogo.vue';
import { Button } from '@/components/ui/button';
import { Menu, LogOut, X } from 'lucide-vue-next';
import { ref, onMounted, onUnmounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

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
            <button @click="toggleMobileMenu" class="lg:hidden text-white transition-transform hover:scale-110">
                <Menu v-if="!isMobileMenuOpen" class="h-6 w-6" />
                <X v-else class="h-6 w-6" />
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div 
            v-show="isMobileMenuOpen"
            class="absolute top-full left-0 w-full bg-arya-dark border-t border-white/10 p-4 lg:hidden shadow-lg flex flex-col gap-4 animate-in slide-in-from-top-2 duration-300"
        >
            <a 
                v-for="item in navItems" 
                :key="item.title" 
                :href="item.href"
                @click="isMobileMenuOpen = false"
                class="text-sm font-medium text-white/80 transition-colors hover:text-arya-gold py-2 block"
            >
                {{ item.title }}
            </a>
            
            <div class="pt-4 border-t border-white/10 flex flex-col gap-3">
                <template v-if="!$page.props.auth.user">
                    <Link :href="login()" class="w-full">
                        <Button class="w-full bg-arya-gold font-black text-black hover:bg-white hover:text-black transition-all rounded-full shadow-[0_0_20px_rgba(248,184,3,0.3)]">
                            Masuk
                        </Button>
                    </Link>
                </template>
                <template v-else>
                    <Link v-if="$page.props.auth.user.role === 'admin'" :href="dashboard().url" class="w-full">
                        <Button class="w-full bg-arya-gold font-bold text-black hover:bg-arya-gold/90">
                            Dashboard
                        </Button>
                    </Link>
                    <button 
                        @click="handleLogout"
                        class="w-full flex items-center justify-center gap-2 rounded-lg bg-red-500/10 px-4 py-2 text-red-500 transition-colors hover:bg-red-500 hover:text-white"
                    >
                        <LogOut class="h-4 w-4" />
                        <span>Logout</span>
                    </button>
                </template>
            </div>
        </div>
    </nav>
</template>
