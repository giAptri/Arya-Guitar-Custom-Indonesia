<script setup lang="ts">
</script>

<template>
    <section class="bg-arya-dark py-32 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-16 lg:flex-row">
                <!-- Owner Image -->
                <div class="relative flex-1">
                    <div class="relative h-96 w-96 mx-auto">
                         <div class="absolute -inset-4 rounded-full bg-arya-gold animate-pulse opacity-20 blur-2xl"></div>
                         <div class="relative h-full w-full overflow-hidden rounded-[3rem] border-4 border-arya-gold shadow-2xl transform lg:-rotate-6">
                            <img 
                                src="https://images.unsplash.com/photo-1593642532400-2682810df593?q=80&w=800&auto=format&fit=crop" 
                                alt="Owner" 
                                class="h-full w-full object-cover grayscale"
                            />
                         </div>
                    </div>
                </div>

                <!-- Testimonial Content -->
                <div class="flex-1">
                    <h2 class="mb-10 text-4xl font-bold text-white lg:text-5xl">
                        Apa Kata <span class="text-arya-gold italic">Owner?</span>
                    </h2>
                    <div class="space-y-6 text-lg leading-relaxed text-white/70 italic">
                        <p>
                            "Sejak dulu memiliki perjalanan ke berbagai tempat di Dunia. Dari situlah saya belajar banyak tentang gitar.
                            Saya ingin membuat wadah dimana gitar bukan hanya sekedar alat musik saja bagi kami,
                            tapi juga merupakan media ekspresi bagi semua gitaris diluar sana melalui instrumen yang kami kerjakan."
                        </p>
                        <p>
                             "Gitar kustom adalah kunci bagi pemain untuk mengeksplorasi jati diri mereka lebih jauh melalui suara yang dihasilkan."
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
