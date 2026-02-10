<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/LandingAuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head, Link } from '@inertiajs/vue3';
import { Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

const showPassword = ref(false);
</script>

<template>
    <AuthBase
        title="Daftar Sekarang!"
        description="START YOUR JOURNEY"
    >
        <Head title="Register" />

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <!-- Name Input -->
            <div class="space-y-1">
                <label for="name" class="text-[11px] font-semibold text-[#1b1b18]/50">Full Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    required
                    autofocus
                    tabindex="1"
                    autocomplete="name"
                    placeholder="John Doe"
                    class="w-full border-b-[1.5px] border-[#1b1b18]/10 bg-transparent py-2 text-sm font-medium text-[#1b1b18] outline-none transition-colors focus:border-[#f8b803]"
                />
                <InputError :message="errors.name" />
            </div>

            <!-- Email Input -->
            <div class="space-y-1">
                <label for="email" class="text-[11px] font-semibold text-[#1b1b18]/50">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    required
                    tabindex="2"
                    autocomplete="email"
                    placeholder="john@example.com"
                    class="w-full border-b-[1.5px] border-[#1b1b18]/10 bg-transparent py-2 text-sm font-medium text-[#1b1b18] outline-none transition-colors focus:border-[#f8b803]"
                />
                <InputError :message="errors.email" />
            </div>

            <!-- Password Input -->
            <div class="space-y-1">
                <label for="password" class="text-[11px] font-semibold text-[#1b1b18]/50">Password</label>
                <div class="relative">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        required
                        tabindex="3"
                        autocomplete="new-password"
                        placeholder="*******"
                        class="w-full border-b-[1.5px] border-[#1b1b18]/10 bg-transparent py-2 pr-10 text-sm font-medium text-[#1b1b18] outline-none transition-colors focus:border-[#f8b803]"
                    />
                    <button 
                        type="button" 
                        class="absolute right-0 top-1/2 -translate-y-1/2 text-[#1b1b18]/30 hover:text-[#1b1b18]"
                        @click="showPassword = !showPassword"
                    >
                        <Eye v-if="!showPassword" class="h-4 w-4" />
                        <EyeOff v-else class="h-4 w-4" />
                    </button>
                </div>
                <InputError :message="errors.password" />
            </div>

            <!-- Password Confirmation -->
            <div class="space-y-1">
                <label for="password_confirmation" class="text-[11px] font-semibold text-[#1b1b18]/50">Confirm Password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    tabindex="4"
                    autocomplete="new-password"
                    placeholder="********"
                    class="w-full border-b-[1.5px] border-[#1b1b18]/10 bg-transparent py-2 text-sm font-medium text-[#1b1b18] outline-none transition-colors focus:border-[#f8b803]"
                />
                <InputError :message="errors.password_confirmation" />
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <Button
                    type="submit"
                    class="h-14 w-full rounded-3xl bg-[#efcc53] text-sm font-black tracking-widest text-[#1b1b18] shadow-lg shadow-[#efcc53]/20 hover:bg-[#efcc53]/90 active:scale-[0.98] transition-all"
                    :disabled="processing"
                >
                    REGISTER
                </Button>
            </div>

            <!-- Login Link -->
            <div class="mt-4 text-center text-xs font-semibold text-[#1b1b18]/40">
                Already have an account?
                <Link :href="login()" class="text-[#1b1b18] hover:underline underline-offset-4 ml-1">Log in</Link>
            </div>
        </Form>
    </AuthBase>
</template>
