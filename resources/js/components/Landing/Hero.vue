<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Play } from 'lucide-vue-next';
</script>

<template>
    <section class="relative flex min-h-screen items-center justify-center overflow-hidden bg-arya-dark pt-20">
        <!-- Background Decorative Elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-arya-gold/10 blur-[100px]"></div>
            <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-arya-gold/5 blur-[100px]"></div>
        </div>

        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-12 lg:flex-row">
                <!-- Text Content -->
                <div class="flex-1 text-center lg:text-left">
                    <h1 class="mb-6 text-5xl font-extrabold leading-tight text-white lg:text-7xl">
                        Kreasikan <span class="text-arya-gold italic">Gitar Mu</span>  Sekarang!
                    </h1>
                    <p class="mb-10 max-w-xl text-lg text-white/80 mx-auto lg:mx-0">
                        Bikin Gitar Impianmu sendiri dengan desain yang kamu sukai.
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-4 lg:justify-start">
                        <Button size="lg" class="bg-arya-gold px-8 py-7 text-lg font-bold text-black hover:bg-arya-gold/90 shadow-[0_0_20px_rgba(248,184,3,0.3)]">
                            Custom Sekarang!
                        </Button>
                    </div>
                </div>

                <!-- Hero Image Holder -->
                <div class="relative flex-1">
                    <div class="relative aspect-square w-full max-w-2xl overflow-hidden rounded-full border-4 border-white/5 bg-gradient-to-br from-arya-gold/20 to-transparent p-4 shadow-2xl">
                        <!-- We will use an AI-generated or placeholder image here -->
                        <img 
                            src="https://images.unsplash.com/photo-1550291652-6ea9114a47b1?q=80&w=1000&auto=format&fit=crop" 
                            alt="Custom Guitar" 
                            class="h-full w-full object-cover rounded-full mix-blend-overlay grayscale hover:grayscale-0 transition-all duration-700"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
