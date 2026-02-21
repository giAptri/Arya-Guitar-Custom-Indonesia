<script setup lang="ts">
const brands = [
    { name: 'Ibanez', src: '/images/brand/ibanez.png' },
    { name: 'Fender', src: '/images/brand/fender.png' },
    { name: 'Gibson', src: '/images/brand/gibsun.png' },
    { name: 'Mandalika', src: '/images/brand/mandalika.png' },
    { name: 'Yamaha', src: '/images/brand/yamaha.png' },
];
</script>

<template>
    <section class="bg-[#0a0a0a] py-24 border-y border-white/5">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-white tracking-tight">
                    Inspirasi <span class="text-arya-gold">Brand</span>
                </h2>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-12 lg:gap-20 opacity-80">
                <div v-for="brand in brands" :key="brand.name" class="group">
                     <img 
                        :src="brand.src" 
                        :alt="brand.name" 
                        class="h-12 lg:h-16 w-auto object-contain filter brightness-0 invert sepia saturate-[5] hue-rotate-[5deg] opacity-50 group-hover:opacity-100 transition-all duration-500"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Removed marquee styles */
</style>
