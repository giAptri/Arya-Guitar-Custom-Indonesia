<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Phone } from 'lucide-vue-next';
</script>

<template>
    <section class="bg-arya-dark py-12">
        <div class="container mx-auto px-4">
            <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-arya-gold/5 to-white/5 p-12 lg:p-24 shadow-2xl border border-white/5">
                <!-- Background Decoration -->
                <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-arya-gold/10 blur-[100px]"></div>
                
                <div class="relative z-10 flex flex-col items-center justify-between gap-12 lg:flex-row">
                    <div class="flex-1 text-center lg:text-left">
                        <h2 class="mb-6 text-4xl font-bold text-white lg:text-6xl">
                            Kreasikan <span class="text-arya-gold">Gitar Mu</span> <br /> 
                            Sekarang!
                        </h2>
                        <p class="mb-8 text-lg text-white/50">
                            Kreativitas gitar berkualitas tinggi untuk musik mu <br class="hidden lg:block" /> 
                            selanjutnya dimulai di sini. Desain gitar mu sendiri sekarang juga!
                        </p>
                        <div class="flex flex-wrap items-center justify-center gap-4 lg:justify-start">
                             <Button class="bg-arya-gold h-14 px-8 font-bold text-black hover:bg-arya-gold/90">
                                Custom sekarang!
                            </Button>
                            <Button variant="outline" class="h-14 px-8 border-green-500 text-green-500 hover:bg-green-500 hover:text-white transition-all gap-2">
                                <Phone class="h-5 w-5" />
                                Konsultasi sekarang!
                            </Button>
                        </div>
                    </div>
                    
                    <div class="flex-1 relative order-first lg:order-last">
                        <img 
                            src="https://images.unsplash.com/photo-1549490349-8643362247b5?q=80&w=600&auto=format&fit=crop" 
                            alt="Guitar line" 
                            class="rounded-2xl shadow-lg grayscale hover:grayscale-0 transition-all duration-700"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
