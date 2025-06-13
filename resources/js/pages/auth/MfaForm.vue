<script setup lang="ts">
import axios from 'axios';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { setToken } from '@/utils/Token';

const props = defineProps<{
    email: string;
}>();


const form = useForm({
    email: props.email,
    code: '',
});

const submit = () => {
    form.processing = true;

    axios.post(route('mfa.verify'), form.data())
        .then(response => {
            form.processing = false;

            const token = response.data.token;
            if (token) {
                setToken(token);
                window.location.href = route('dashboard');
            }
        })
        .catch(error => {
            form.processing = false;

            if (error.response && error.response.status === 422) {
                console.log(error.response.data.message);
                
                form.errors.code = error.response.data.message || {};
            } else {
                console.error('Error posting MFA verify:', error);
            }
        });
};
</script>

<template>
    <AuthBase title="Multi Factor Authentication is Enabled"
        :description="`Enter your code sent on your <b>${props.email}</b>`">

        <Head title="Multi Factor Authentication" />

        <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <input type="hidden" v-model="form.email" />
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="code">Code</Label>
                    <Input id="code" type="text" required autofocus :tabindex="1" v-model="form.code"
                        placeholder="Enter the code recieved on mail" />
                    <InputError :message="form.errors.code" />
                </div>


                <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Submit
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Back to
                <TextLink :href="route('login')" :tabindex="5">Log In</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
