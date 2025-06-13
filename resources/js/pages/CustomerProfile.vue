<script setup lang="ts">
import { defineProps } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Pen } from 'lucide-vue-next';

const props = defineProps<{
    data: {
        id: number;
        first_name: string;
        last_name: string;
        email: string;
        age: number;
        dob: string;
        creation_date: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Customers', href: '/customers' },
    { title: `${props.data.first_name} ${props.data.last_name}`, href: '' },
];
</script>

<template>

    <Head :title="`${props.data.first_name} ${props.data.last_name} - Profile`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="col-span-1 rounded-xl border bg-white dark:bg-gray-800 p-6 shadow">
                    <div class="flex justify-between">
                        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-2">
                            {{ props.data.first_name }} {{ props.data.last_name }}
                        </h2>
                        <Link :href="route('customer.edit', { id: props.data.id })"
                            class="flex items-center mx-1">
                            <Pen class="ml-auto size-4" />
                        </Link>
                    </div>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Email: {{ props.data.email }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Age: {{ props.data.age }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Date of Birth: {{ props.data.dob }}
                    </p>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Created At: {{ props.data.creation_date }}
                    </p>
                </div>

                <div class="col-span-1 rounded-xl border bg-white dark:bg-gray-800 p-6 shadow">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300">
                        Notes
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Additional customer info can go here.
                    </p>
                </div>

                <div class="col-span-1 rounded-xl border bg-white dark:bg-gray-800 p-6 shadow">
                    <h3 class="font-semibold text-gray-700 dark:text-gray-300">
                        Activity
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        No recent activity.
                    </p>
                </div>
            </div>

            <div class="rounded-xl border bg-white dark:bg-gray-800 p-6 shadow">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">
                    Customer Overview
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                    industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type
                    and scrambled it to make a type specimen book. It has survived not only five centuries, but also the
                    leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s
                    with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                    publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
