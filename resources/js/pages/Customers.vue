<script setup lang="ts">
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import axios from 'axios';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { getToken } from '@/utils/Token';
import { Eye, Pen, Trash } from 'lucide-vue-next';
import { Dialog, DialogTrigger, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter, DialogClose } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

const token = getToken();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Customers',
        href: '/sustomers',
    },
];

const customers = ref<any[]>([]);
const pagination = ref<any>({});
const loading = ref(true);
const isDialogOpen = ref(false);
const deletingCustomerId = ref<number | null>(null);

const fetchCustomers = async (page = 1) => {
    loading.value = true;
    try {
        const url = route('api.customers.index', { page });
        const response = await axios.get(url, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        customers.value = response.data.data;
        pagination.value = {
            current_page: response.data.current_page,
            last_page: response.data.last_page,
            next_page_url: response.data.next_page_url,
            prev_page_url: response.data.prev_page_url,
        };
    } catch (error) {
        console.error('Failed to load customers:', error);
    } finally {
        loading.value = false;
    }
};

const openDeleteModal = (id: number) => {
    deletingCustomerId.value = id;
    isDialogOpen.value = true;
};

const closeModal = () => {
    isDialogOpen.value = false;
    deletingCustomerId.value = null;
    form.reset();
    form.clearErrors();
};

const confirmDelete = async () => {
    if (!deletingCustomerId.value) return;

    try {
        await axios.delete(route('api.customers.destroy', deletingCustomerId.value), {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        customers.value = customers.value.filter(c => c.id !== deletingCustomerId.value);
        closeModal();
    } catch (error) {
        console.error('Delete failed', error);
    }
};


onMounted(() => fetchCustomers());
</script>

<template>

    <Head title="Customers List" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-2xl font-semibold text-white"></h1>
                <Link :href="route('customer.create')"
                    class="inline-block bg-white border border-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-50">
                    Add Customer
                </Link>
            </div>
            <div v-if="!loading"
                class="relative min-h-[100vh] pb-4 flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Created At
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y bg-dark-50">
                        <tr v-for="customer in customers" :key="customer.id" class="transition">
                            <td class="px-6 py-4 text-sm">{{ `${customer.first_name}
                                ${customer.last_name}` }}</td>
                            <td class="px-6 py-4 text-sm">{{ customer.email }}</td>
                            <td class="px-6 py-4 text-sm">{{ customer.created_at }}</td>
                            <td class="px-6 py-4 text-right flex justify-end">
                                <Link :href="route('customer.show', { id: customer.id })"
                                    class="flex items-center mx-1">
                                <Eye class="ml-auto size-4" />
                                </Link>
                                <Link :href="route('customer.edit', { id: customer.id })"
                                    class="flex items-center mx-1">
                                <Pen class="ml-auto size-4" />
                                </Link>
                                <span @click="openDeleteModal(customer.id)" class="flex items-center mx-1">
                                    <Trash class="ml-auto size-4" />
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="pagination.last_page > 1" class="mt-4 flex justify-center gap-2">
                    <button :disabled="pagination.current_page === 1"
                        @click="fetchCustomers(pagination.current_page - 1)"
                        class="px-3 py-1 rounded bg-gray-700 text-white disabled:opacity-50">
                        Previous
                    </button>

                    <span class="px-3 py-1 text-white">Page {{ pagination.current_page }} of {{ pagination.last_page
                        }}</span>

                    <button :disabled="pagination.current_page === pagination.last_page"
                        @click="fetchCustomers(pagination.current_page + 1)"
                        class="px-3 py-1 rounded bg-gray-700 text-white disabled:opacity-50">
                        Next
                    </button>
                </div>
            </div>
            <div v-else class="text-center">
                Loading...
            </div>
        </div>
    </AppLayout>

    <Dialog v-model:open="isDialogOpen">
        <DialogTrigger as-child>
            <!-- You can keep this empty or place a button to open dialog manually -->
        </DialogTrigger>
        <DialogContent>
            <form @submit.prevent="confirmDelete" class="space-y-6">
                <DialogHeader>
                    <DialogTitle>Confirm Delete Customer</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete this customer? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal">Cancel</Button>
                    </DialogClose>
                    <Button type="submit" variant="destructive">Delete</Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
