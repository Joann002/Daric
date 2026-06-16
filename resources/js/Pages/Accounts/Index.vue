<template>
    <AuthenticatedLayout>
        <Head title="Mes comptes" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Mes comptes
                    </h2>
                    <Link 
                        :href="route('accounts.create')"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        + Nouveau compte
                    </Link>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div 
                        v-for="account in accounts" 
                        :key="account.id"
                        class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800"
                    >
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="text-3xl">{{ getAccountIcon(account.type) }}</span>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ account.name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ getAccountType(account.type) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-3xl font-bold" :class="account.balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ formatCurrency(account.balance, account.currency) }}
                                </p>
                            </div>

                            <div class="mt-6 flex space-x-2">
                                <Link 
                                    :href="route('accounts.show', account.id)"
                                    class="flex-1 rounded-md bg-gray-100 px-3 py-2 text-center text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                >
                                    Voir
                                </Link>
                                <Link 
                                    :href="route('accounts.edit', account.id)"
                                    class="flex-1 rounded-md bg-indigo-100 px-3 py-2 text-center text-sm font-medium text-indigo-700 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300"
                                >
                                    Modifier
                                </Link>
                                <button 
                                    @click="deleteAccount(account.id)"
                                    class="rounded-md bg-red-100 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-300"
                                >
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <p v-if="accounts.length === 0" class="mt-8 text-center text-gray-500 dark:text-gray-400">
                    Aucun compte. Créez votre premier compte pour commencer.
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    accounts: Array,
});

const getAccountIcon = (type) => {
    const icons = {
        cash: '💵',
        banque: '🏦',
        mobile_money: '📱',
    };
    return icons[type] || '💳';
};

const getAccountType = (type) => {
    const types = {
        cash: 'Espèces',
        banque: 'Banque',
        mobile_money: 'Mobile Money',
    };
    return types[type] || type;
};

const formatCurrency = (amount, currency = 'XAF') => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
    }).format(amount);
};

const deleteAccount = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce compte ? Toutes les transactions associées seront également supprimées.')) {
        router.delete(route('accounts.destroy', id));
    }
};
</script>
