<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-screen bg-[#0a0a0a]">
        <!-- Left Side: Image & Text -->
        <div class="relative hidden w-0 flex-1 lg:block">
            <img 
                src="https://images.unsplash.com/photo-1510915361894-db8b60106cb1?q=80&w=2000&auto=format&fit=crop" 
                alt="Guitar workshop" 
                class="absolute inset-0 h-full w-full object-cover opacity-60 grayscale"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0a0a] via-transparent to-transparent"></div>
            
            <div class="absolute inset-0 flex flex-col items-start justify-center p-20">
                <Link :href="home()" class="mb-12">
                    <AppLogo class="h-16 w-auto" />
                </Link>
                
                <h1 class="mb-6 max-w-lg text-6xl font-extrabold leading-tight text-white">
                    Kreasikan <span class="text-arya-gold">Gitar Mu</span> <br /> 
                    Sekarang!
                </h1>
                
                <p class="max-w-md text-lg text-white/60">
                    Kreasikan Gitar Sesuai Keinginanmu disini. Konsultasi Sekarang untuk Mendapatkan Hasil Gitar yang Maksimal. Pesan Sekarang dan Miliki Gitar Kesayangan mu
                </p>
            </div>
        </div>

        <!-- Right Side: Auth Form -->
        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-[480px]">
                <div class="rounded-[2.5rem] bg-[#f9f5eb] p-10 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.5)] lg:p-14">
                    <div class="text-center">
                        <p class="mb-2 text-[10px] font-bold uppercase tracking-[0.2em] text-[#1b1b18]/40">
                            {{ description || "LET'S GET YOU STARTED" }}
                        </p>
                        <h2 class="text-3xl font-bold tracking-tight text-[#1b1b18]">
                            {{ title }}
                        </h2>
                    </div>

                    <div class="mt-10">
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
