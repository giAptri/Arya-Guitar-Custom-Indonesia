<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { Facebook, Youtube, Instagram, Mail, Phone, MapPin } from 'lucide-vue-next';
</script>

<template>
    <footer id="contact" class="bg-[#050505] pt-24 pb-8 border-t border-arya-gold">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 mb-20 lg:grid-cols-4">
                <!-- Branding -->
                <div class="space-y-6">
                    <AppLogo class="h-16 w-auto" />
                    <p class="text-sm leading-relaxed text-white/60">
                        Menciptakan gitar custom premium dengan keahlian tangan Indonesia dan standar internasional.
                    </p>
                </div>

                <!-- Layanan -->
                <div>
                    <h4 class="mb-8 text-sm font-black uppercase tracking-widest text-arya-gold">LAYANAN</h4>
                    <ul class="space-y-4 text-sm text-white/60">
                        <li><a href="#" class="hover:text-arya-gold transition-colors">Custom Guitar</a></li>
                        <li><a href="#" class="hover:text-arya-gold transition-colors">Repair & Setup</a></li>
                        <li><a href="#" class="hover:text-arya-gold transition-colors">Konsultasi</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h4 class="mb-8 text-sm font-black uppercase tracking-widest text-arya-gold">KONTAK</h4>
                    <ul class="space-y-4 text-sm text-white/60">
                        <li class="flex items-center gap-3">
                            <Phone class="h-4 w-4 text-white/40" />
                            +62 812-3456-7890
                        </li>
                        <li class="flex items-center gap-3">
                            <Mail class="h-4 w-4 text-white/40" />
                            info@aryaguitar.id
                        </li>
                        <li class="flex items-center gap-3">
                            <MapPin class="h-4 w-4 text-white/40" />
                            Bogor, Indonesia
                        </li>
                    </ul>
                </div>

                <!-- Jam Operasional & Social Media -->
                <div>
                     <h4 class="mb-8 text-sm font-black uppercase tracking-widest text-arya-gold">JAM OPERASIONAL</h4>
                     <p class="text-sm text-white/60 mb-12">Setiap Hari : 10.00 - 20.00</p>
                      
                     <h4 class="mb-6 text-sm font-black uppercase tracking-widest text-arya-gold">SOSIAL MEDIA</h4>
                     <div class="flex gap-4">
                        <a href="#" class="h-10 w-10 flex items-center justify-center text-white/60 hover:text-white transition-all">
                            <Instagram class="h-6 w-6" />
                        </a>
                        <a href="#" class="h-10 w-10 flex items-center justify-center text-white/60 hover:text-white transition-all">
                            <Youtube class="h-6 w-6" />
                        </a>
                        <a href="#" class="h-10 w-10 flex items-center justify-center text-white/60 hover:text-white transition-all">
                            <Facebook class="h-6 w-6" />
                        </a>
                     </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="text-center">
                <p class="text-sm font-bold text-white/40">
                    © 2024 Arya Guitar Custom Indonesia. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</template>
