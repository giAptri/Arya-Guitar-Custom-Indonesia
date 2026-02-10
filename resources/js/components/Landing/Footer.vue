<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { Facebook, Youtube, Instagram, Mail, Phone, MapPin } from 'lucide-vue-next';
</script>

<template>
    <footer class="bg-[#050505] pt-24 pb-12 border-t border-white/5">
        <div class="container mx-auto px-4">
            <div class="grid gap-12 mb-16 lg:grid-cols-4">
                <!-- Branding -->
                <div class="space-y-6">
                    <AppLogo class="h-12 w-auto" />
                    <p class="text-sm leading-relaxed text-white/40">
                        Mewujudkan gitar impian premium dengan kualitas terbaik tangan Indonesia dan pengerjaan kustomisasi.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="mb-6 text-sm font-bold uppercase tracking-widest text-white">Layanan</h4>
                    <ul class="space-y-4 text-sm text-white/40">
                        <li><a href="#" class="hover:text-arya-gold transition-colors">Produk Gitar</a></li>
                        <li><a href="#" class="hover:text-arya-gold transition-colors">Gitar kustom</a></li>
                        <li><a href="#" class="hover:text-arya-gold transition-colors">Konsultasi</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="mb-6 text-sm font-bold uppercase tracking-widest text-white">Kontak</h4>
                    <ul class="space-y-4 text-sm text-white/40">
                        <li class="flex items-center gap-3">
                            <Phone class="h-4 w-4 text-arya-gold" />
                            +62 8XX XXXX XXXX
                        </li>
                        <li class="flex items-center gap-3">
                            <Mail class="h-4 w-4 text-arya-gold" />
                            info@aryaguitars.id
                        </li>
                        <li class="flex items-center gap-3">
                             <MapPin class="h-4 w-4 text-arya-gold" />
                             Cimahi, Indonesia
                        </li>
                    </ul>
                </div>

                <!-- Open Hours -->
                <div>
                     <h4 class="mb-6 text-sm font-bold uppercase tracking-widest text-white">Jam Operasional</h4>
                     <p class="text-sm text-white/40">Senin - Sabtu: 08:00 - 23:00</p>
                     <p class="text-sm text-white/40">Minggu: 09:00 - 20:00</p>
                      
                     <div class="mt-8">
                        <h4 class="mb-4 text-xs font-bold uppercase tracking-widest text-white">Sosial Media</h4>
                        <div class="flex gap-4">
                            <a href="#" class="h-10 w-10 rounded-full bg-white/90 flex items-center justify-center hover:bg-arya-gold hover:text-black transition-all">
                                <Instagram class="h-5 w-5" />
                            </a>
                            <a href="#" class="h-10 w-10 rounded-full bg-white/90 flex items-center justify-center hover:bg-arya-gold hover:text-black transition-all">
                                <Youtube class="h-5 w-5" />
                            </a>
                            <a href="#" class="h-10 w-10 rounded-full bg-white/90 flex items-center justify-center hover:bg-arya-gold hover:text-black transition-all">
                                <Facebook class="h-5 w-5" />
                            </a>
                        </div>
                     </div>
                </div>
            </div>

            <div class="bg-arya-gold/40 hover:bg-arya-gold/10 hover:border-arya-gold hover:text-white transition-all duration-500">
                <p class="text-center text-m text-white/90 ">
                    © 2024 Arya Guitar Custom Indonesia. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>
</template>
