<script setup lang="ts">
import { defineProps } from 'vue';
import axios from 'axios';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { getToken } from '@/utils/Token';

const token = getToken();

const props = defineProps<{
    data: {
        id: number;
        first_name: string;
        last_name: string;
        age: number;
        dob: string;
        email: string;
        creation_date: string;
    };
}>();

const form = useForm({
    first_name: props.data.first_name,
    last_name: props.data.last_name,
    age: props.data.age,
    dob: props.data.dob,
    email: props.data.email,
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Customers', href: '/customers' },
    { title: `${props.data.first_name} ${props.data.last_name}`, href: `/customer/${props.data.id}` },
    { title: 'Edit', href: '' },
];

const submit = async () => {
    form.processing = true;
    form.errors = [];
    
    try {
        await axios.put(
            route('api.customers.update', props.data.id),
            form.data(), // assuming form.data() returns your form payload
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        );
        form.processing = false;
    } catch (error) {
        form.processing = false;
        if (error.response) {
            if (error.response.status === 422) {
                form.errors = error.response.data.errors || {};
            } else {
                console.error('Error response:', error.response);
            }
        }
    }
};
</script>

<template>

    <Head :title="`Edit ${props.data.first_name} ${props.data.last_name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid auto-rows-min m-5 gap-4 md:grid-cols-3">
            <div
                class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                <h3 class=" font-semibold mb-8 text-gray-900 dark:text-gray-100">
                    Edit Customer Profile
                </h3>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <label for="first_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                First Name
                            </label>
                            <input id="first_name" v-model="form.first_name" type="text"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                                placeholder="First name" />
                            <p v-if="form.errors.first_name" class="text-sm text-red-600 mt-1">{{ form.errors.first_name[0]}}</p>
                        </div>

                        <div class="flex-1">
                            <label for="last_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Last Name
                            </label>
                            <input id="last_name" v-model="form.last_name" type="text"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                                placeholder="Last name" />
                            <p v-if="form.errors.last_name" class="text-sm text-red-600 mt-1">{{ form.errors.last_name[0]}}</p>
                        </div>
                    </div>

                    <div class="flex gap-6">
                        <div class="flex-1">
                            <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Age
                            </label>
                            <input id="age" v-model.number="form.age" type="number" min="0"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                                placeholder="Age" />
                            <p v-if="form.errors.age" class="text-sm text-red-600 mt-1">{{ form.errors.age[0] }}</p>
                        </div>

                        <div class="flex-1">
                            <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Date of Birth
                            </label>
                            <input id="dob" v-model="form.dob" type="date"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" />
                            <p v-if="form.errors.dob" class="text-sm text-red-600 mt-1">{{ form.errors.dob[0] }}</p>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Email
                        </label>
                        <input id="email" v-model="form.email" type="email"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2"
                            placeholder="Email address" />
                        <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">{{ form.errors.email[0] }}</p>
                    </div>

                    <div>
                        <button type="submit" :disabled="form.processing"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-md disabled:opacity-50">
                            {{form.processing ? 'Saving Changes...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
