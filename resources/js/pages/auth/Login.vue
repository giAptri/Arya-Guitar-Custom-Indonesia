<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import AuthBase from '@/layouts/LandingAuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';
import { Form, Head, Link } from '@inertiajs/vue3';
import { Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

const showPassword = ref(false);
</script>

<template>
    <AuthBase
        title="Login Sekarang!"
        description="LET'S GET YOU STARTED"
    >
        <Head title="Log in" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-8"
        >
            <!-- Email Input -->
            <div class="space-y-1">
                <label for="email" class="text-[11px] font-semibold text-[#1b1b18]/50">Email or Username</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    tabindex="1"
                    autocomplete="email"
                    placeholder="johndadev"
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
                        tabindex="2"
                        autocomplete="current-password"
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

            <!-- Remember me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember" class="flex items-center space-x-3 cursor-pointer">
                    <Checkbox id="remember" name="remember" class="h-5 w-5 rounded-md border-[#1b1b18]/20 data-[state=checked]:bg-[#1b1b18] data-[state=checked]:text-[#f9f5eb]" />
                    <span class="text-[11px] font-semibold text-[#1b1b18]/40">Remember me</span>
                </label>
                
                <Link
                    v-if="canResetPassword"
                    :href="request()"
                    class="text-[10px] font-bold text-[#1b1b18]/40 underline underline-offset-4 hover:text-[#1b1b18]"
                >
                    Lupa Password?
                </Link>
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <Button
                    type="submit"
                    class="h-14 w-full rounded-3xl bg-[#efcc53] text-sm font-black tracking-widest text-[#1b1b18] shadow-lg shadow-[#efcc53]/20 hover:bg-[#efcc53]/90 active:scale-[0.98] transition-all"
                    :disabled="processing"
                >
                    LOG IN
                </Button>
            </div>

            <!-- Register Link -->
            <div
                class="mt-4 text-center text-xs font-semibold text-[#1b1b18]/40"
                v-if="canRegister"
            >
                Don't have an account?
                <Link :href="register()" class="text-[#1b1b18] hover:underline underline-offset-4 ml-1">Sign up</Link>
            </div>
        </Form>
    </AuthBase>
</template>
