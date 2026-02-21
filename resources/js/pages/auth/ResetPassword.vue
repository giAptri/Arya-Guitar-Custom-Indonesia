<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import AuthBase from '@/layouts/LandingAuthLayout.vue';
import { update } from '@/routes/password';
import { Form, Head } from '@inertiajs/vue3';
import { Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);
const showPassword = ref(false);
</script>

<template>
    <AuthBase
        title="Reset Password"
        description="BUAT PASSWORD BARU MU"
    >
        <Head title="Reset Password" />

        <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <!-- Email Input (Readonly) -->
            <div class="space-y-1">
                <label for="email" class="text-[11px] font-semibold text-[#1b1b18]/50">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    v-model="inputEmail"
                    readonly
                    class="w-full border-b-[1.5px] border-[#1b1b18]/10 bg-transparent py-2 text-sm font-medium text-[#1b1b18]/60 outline-none cursor-not-allowed"
                />
                <InputError :message="errors.email" />
            </div>

            <!-- Password Input -->
            <div class="space-y-1">
                <label for="password" class="text-[11px] font-semibold text-[#1b1b18]/50">New Password</label>
                <div class="relative">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        required
                        autofocus
                        placeholder="*******"
                        class="w-full border-b-[1.5px] border-[#1b1b18]/10 bg-transparent py-2 pr-10 text-sm font-medium text-[#1b1b18] outline-none transition-colors focus:border-arya-gold"
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

            <!-- Confirm Password Input -->
            <div class="space-y-1">
                <label for="password_confirmation" class="text-[11px] font-semibold text-[#1b1b18]/50">Confirm Password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    placeholder="*******"
                    class="w-full border-b-[1.5px] border-[#1b1b18]/10 bg-transparent py-2 text-sm font-medium text-[#1b1b18] outline-none transition-colors focus:border-arya-gold"
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
                    RESET PASSWORD
                </Button>
            </div>
        </Form>
    </AuthBase>
</template>
