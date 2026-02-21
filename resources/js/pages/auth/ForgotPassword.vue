<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/LandingAuthLayout.vue';
import { login } from '@/routes';
import { email } from '@/routes/password';
import { Form, Head, Link } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <AuthBase
        title="Lupa Password?"
        description="JANGAN PANIK, KAMI BANTU"
    >
        <Head title="Forgot Password" />

        <div
            v-if="status"
            class="mb-4 text-center text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <Form
            v-bind="email.form()"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-8"
        >
            <!-- Email Input -->
            <div class="space-y-1">
                <label for="email" class="text-[11px] font-semibold text-[#1b1b18]/50">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    placeholder="email@example.com"
                    class="w-full border-b-[1.5px] border-[#1b1b18]/10 bg-transparent py-2 text-sm font-medium text-[#1b1b18] outline-none transition-colors focus:border-arya-gold"
                />
                <InputError :message="errors.email" />
            </div>

            <!-- Submit Button -->
            <div class="mt-4">
                <Button
                    type="submit"
                    class="h-14 w-full rounded-3xl bg-[#efcc53] text-sm font-black tracking-widest text-[#1b1b18] shadow-lg shadow-[#efcc53]/20 hover:bg-[#efcc53]/90 active:scale-[0.98] transition-all"
                    :disabled="processing"
                >
                    KIRIM LINK RESET
                </Button>
            </div>

            <!-- Back to Login -->
            <div class="mt-4 text-center text-xs font-semibold text-[#1b1b18]/40">
                Instantly remembered?
                <Link :href="login()" class="text-[#1b1b18] hover:underline underline-offset-4 ml-1">Log in</Link>
            </div>
        </Form>
    </AuthBase>
</template>
