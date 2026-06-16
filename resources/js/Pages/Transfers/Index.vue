<template>
    <AuthenticatedLayout>
        <Head title="Transferts" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Transferts entre comptes
                    </h2>
                    <Link 
                        :href="route('transfers.create')"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        + Nouveau transfert
                    </Link>
                </div>

                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <div v-if="transfers.data.length > 0" class="space-y-3">
                            <div 
                                v-for="transfer in transfers.data" 
                                :key="transfer.id"
                                class="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                            >
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2 text-sm text-gray-900 dark:text-white">
                                            <span class="font-semibold">{{ transfer.from_account.name }}</span>
                                            <span class="text-gray-400">→</span>
                                            <span class="font-semibold">{{ transfer.to_account.name }}</span>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ formatDate(transfer.date) }}
                                        </p>
                                        <p v-if="transfer.description" class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ transfer.description }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                            {{ formatCurrency(transfer.amount) }}
                                        </p>
                                    </div>
                                    <button 
                                        @click="deleteTransfer(transfer.id)"
                                        class="rounded-md bg-red-100 p-2 text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-300"
                                        title="Supprimer"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <p v-else class="py-8 text-center text-gray-500 dark:text-gray-400">
                            Aucun transfert effectué
                        </p>

                        <!-- Pagination -->
                        <div v-if="transfers.links.length > 3" class="mt-6 flex justify-center space-x-2">
                            <Link 
                                v-for="link in transfers.links" 
                                :key="link.label"
                                :href="link.url"
                                :class="[
                                    'rounded-md px-3 py-2 text-sm',
                                    link.active 
                                        ? 'bg-indigo-600 text-white' 
                                        : 'bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600',
                                    !link.url && 'opacity-50 cursor-not-allowed'
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    transfers: Object,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'decimal',
        minimumFractionDigits: 0,
    }).format(amount) + ' FCFA';
};

const deleteTransfer = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce transfert ? Les soldes des comptes seront restaurés.')) {
        router.delete(route('transfers.destroy', id));
    }
};
</script>
