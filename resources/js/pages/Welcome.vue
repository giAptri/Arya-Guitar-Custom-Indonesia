<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/Landing/Navbar.vue';
import Hero from '@/components/Landing/Hero.vue';
import ProductCatalog from '@/components/Landing/ProductCatalog.vue';
import CTA from '@/components/Landing/CTA.vue';
import Features from '@/components/Landing/Features.vue';
import Testimonial from '@/components/Landing/Testimonial.vue';
import Footer from '@/components/Landing/Footer.vue';
import BrandInspiration from '@/components/Landing/BrandInspiration.vue';

withDefaults(
    defineProps<{
        canRegister: boolean;
        guitars: any[];
    }>(),
    {
        canRegister: true,
        guitars: () => [],
    },
);
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
            <ProductCatalog :guitars="guitars" />
            <CTA />
            <BrandInspiration />
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
</style>
