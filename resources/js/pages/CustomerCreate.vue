<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { getToken } from '@/utils/Token';

const token = getToken();

const form = useForm({
    first_name: '',
    last_name: '',
    age: null,
    dob: '',
    email: '',
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Customers', href: '/customers' },
    { title: 'Add Customer', href: '' },
];

const submit = async () => {
    form.processing = true;
    form.errors = [];

    try {
        await axios.post(
            route('api.customers.store'),
            form.data(),
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }
        );

        window.location.href = route('customers.index');
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

    <Head title="Add Customer" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="grid auto-rows-min m-5 gap-4 md:grid-cols-3">
            <div
                class="max-w-3xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold mb-8 text-gray-900 dark:text-gray-100">
                    Add New Customer
                </h3>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="flex gap-6">
                        <div class="flex-1">
                            <label for="first_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                First Name
                            </label>
                            <input id="first_name" v-model="form.first_name" type="text" placeholder="First name"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" />
                            <p v-if="form.errors.first_name" class="text-sm text-red-600 mt-1">
                                {{ form.errors.first_name[0] }}
                            </p>
                        </div>

                        <div class="flex-1">
                            <label for="last_name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Last Name
                            </label>
                            <input id="last_name" v-model="form.last_name" type="text" placeholder="Last name"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" />
                            <p v-if="form.errors.last_name" class="text-sm text-red-600 mt-1">
                                {{ form.errors.last_name[0] }}
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-6">
                        <div class="flex-1">
                            <label for="age" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Age
                            </label>
                            <input id="age" v-model.number="form.age" type="number" min="0" placeholder="Age"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" />
                            <p v-if="form.errors.age" class="text-sm text-red-600 mt-1">
                                {{ form.errors.age[0] }}
                            </p>
                        </div>

                        <div class="flex-1">
                            <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Date of Birth
                            </label>
                            <input id="dob" v-model="form.dob" type="date"
                                class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" />
                            <p v-if="form.errors.dob" class="text-sm text-red-600 mt-1">
                                {{ form.errors.dob[0] }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Email
                        </label>
                        <input id="email" v-model="form.email" type="email" placeholder="Email address"
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-3 py-2" />
                        <p v-if="form.errors.email" class="text-sm text-red-600 mt-1">
                            {{ form.errors.email[0] }}
                        </p>
                    </div>

                    <div>
                        <button type="submit" :disabled="form.processing"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-md disabled:opacity-50">
                            {{ form.processing ? 'Adding Customer...' : 'Add Customer' }}
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
