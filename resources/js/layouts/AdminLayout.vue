<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronLeft } from 'lucide-vue-next';
import AdminSidebar from '@/components/Admin/AdminSidebar.vue';
import { home } from '@/routes';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <div class="min-h-screen bg-[#fcfcfc] flex">
        <!-- Sidebar -->
        <AdminSidebar />

        <!-- Main Content Area -->
        <div class="flex-1 ml-72 flex flex-col">
            <!-- Header -->
            <header class="h-20 px-12 flex items-center justify-between">
                <Link :href="home()" class="flex items-center gap-2 text-arya-gold font-bold text-sm tracking-wide">
                    <ChevronLeft class="h-4 w-4" />
                    Kembali ke Beranda
                </Link>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <span class="block text-sm font-black text-black leading-none">{{ user.name }}</span>
                    </div>
                    <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-arya-gold">
                        <img 
                            :src="user.avatar || 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100&auto=format&fit=crop'" 
                            alt="User Profile" 
                            class="h-full w-full object-cover" 
                        />
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 px-12 pb-12">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
/* Global dashboard styles if needed */
body {
    background-color: #fcfcfc;
}
</style>
