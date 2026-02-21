<script setup lang="ts">
import { Guitar, Edit3, Settings2 } from 'lucide-vue-next';

const features = [
    {
        title: 'Karya Unggul',
        description: 'Setiap gitar kami dikerjakan teliti oleh pengrajin spesialis kami.',
        icon: Guitar
    },
    {
        title: 'Custom Personal',
        description: 'Gitar kita dibuat sesuai kepribadian masing-masing.',
        icon: Edit3
    },
    {
        title: 'Layanan Lanjutan',
        description: 'Dukungan penuh dari kami untuk pemeliharaan & perbaikan gitar anda.',
        icon: Settings2
    }
];
</script>

<template>
    <section id="about" class="bg-arya-dark py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-20 text-center">
                <h2 class="text-4xl font-bold text-white leading-tight">
                    Kenapa <span class="text-arya-gold">Kami?</span>
                </h2>
            </div>

            <div class="grid gap-12 md:grid-cols-3">
                <div v-for="item in features" :key="item.title" class="flex flex-col items-center text-center">
                    <div class="mb-8 flex h-40 w-40 items-center justify-center rounded-[2rem] bg-white/90 border border-arya-gold/10 hover:bg-arya-gold/10 hover:border-arya-gold transition-all duration-500 shadow-lg text-arya-gold">
                        <component :is="item.icon" class="h-20 w-20" />
                    </div>
                    <h3 class="mb-4 text-xl font-bold text-white">{{ item.title }}</h3>
                    <p class="max-w-xs text-sm leading-relaxed text-white/50">{{ item.description }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
