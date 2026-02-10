<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/Landing/Navbar.vue';
import Hero from '@/components/Landing/Hero.vue';
import ProductCatalog from '@/components/Landing/ProductCatalog.vue';
import CTA from '@/components/Landing/CTA.vue';
import Features from '@/components/Landing/Features.vue';
import Testimonial from '@/components/Landing/Testimonial.vue';
import Footer from '@/components/Landing/Footer.vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);

const brands = [
    { name: 'Ibanez', src: 'https://upload.wikimedia.org/wikipedia/commons/e/e0/Ibanez_logo.svg' },
    { name: 'Fender', src: 'https://upload.wikimedia.org/wikipedia/commons/e/ef/Fender_logo.svg' },
    { name: 'Gibson', src: 'https://upload.wikimedia.org/wikipedia/commons/c/cd/Gibson_logo.svg' },
    { name: 'Mandalika', src: 'https://upload.wikimedia.org/wikipedia/id/2/23/Logo_Sirkuit_Mandalika.png' },
    { name: 'Yamaha', src: 'https://upload.wikimedia.org/wikipedia/commons/8/82/Yamaha_Logo.svg' },
];
</script>

<template>
    <Head title="Arya Guitar Custom Indonesia">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-[#0a0a0a] selection:bg-[#f8b803] selection:text-black">
        <Navbar />

        <main>
            <Hero />

            <!-- Brand Section (Marquee) -->
            <section class="bg-[#0a0a0a] py-24 overflow-hidden border-y border-white/5">
                <div class="container mx-auto px-4 mb-16">
                    <div class="text-center">
                        <h2 class="text-4xl font-bold text-white tracking-tight">
                            <span class="text-arya-gold">Brand</span> Inspirasi
                        </h2>
                        <p class="mt-4 text-white/40 text-sm italic">Gitar kami terinspirasi dari standar industri musik dunia</p>
                    </div>
                </div>

                <!-- Marquee Container -->
                <div class="relative flex overflow-hidden py-4">
                    <!-- Standard Marquee Flow -->
                    <div class="flex animate-marquee whitespace-nowrap items-center">
                        <div v-for="i in 4" :key="i" class="flex items-center">
                            <div v-for="brand in brands" :key="brand.name + '-' + i" class="mx-16">
                                <img 
                                    :src="brand.src" 
                                    :alt="brand.name" 
                                    class="h-12 w-auto filter invert brightness-200 opacity-40 hover:opacity-100 hover:scale-110 transition-all duration-500 cursor-pointer object-contain flex-shrink-0"
                                    loading="eager"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <ProductCatalog />
            <CTA />
            <Features />
            <Testimonial />
        </main>

        <Footer />
    </div>
</template>

<style>
html {
    scroll-behavior: smooth;
    background-color: #0a0a0a;
}

@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.animate-marquee {
  animation: marquee 30s linear infinite;
  display: flex;
  width: max-content;
}

.animate-marquee:hover {
    animation-play-state: paused;
}
</style>
