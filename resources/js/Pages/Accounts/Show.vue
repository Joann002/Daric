<template>
    <AuthenticatedLayout>
        <Head :title="`Compte: ${account.name}`" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <Link 
                            :href="route('accounts.index')"
                            class="mb-2 block text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                        >
                            ← Retour aux comptes
                        </Link>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ account.name }}
                        </h2>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">
                            {{ getAccountType(account.type) }}
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <Link 
                            :href="route('accounts.edit', account.id)"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                        >
                            ✏️ Modifier
                        </Link>
                        <button 
                            @click="deleteAccount"
                            class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500"
                        >
                            🗑️ Supprimer
                        </button>
                    </div>
                </div>

                <!-- Solde du compte -->
                <div class="mb-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-8 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Solde actuel</p>
                        <p 
                            class="mt-2 text-5xl font-bold"
                            :class="account.balance >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                        >
                            {{ formatCurrency(account.balance, account.currency) }}
                        </p>
                    </div>
                </div>

                <!-- Transactions récentes -->
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Dernières transactions
                            </h3>
                            <Link 
                                :href="route('transactions.create')"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-indigo-500"
                            >
                                + Nouvelle transaction
                            </Link>
                        </div>
                        
                        <TransactionList :transactions="account.transactions" />
                        
                        <div class="mt-4 text-center">
                            <Link 
                                :href="`/transactions?account_id=${account.id}`"
                                class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
                            >
                                Voir toutes les transactions de ce compte →
                            </Link>
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
import TransactionList from '@/Components/TransactionList.vue';

const props = defineProps({
    account: Object,
});

const getAccountType = (type) => {
    const types = {
        cash: '💵 Espèces',
        banque: '🏦 Banque',
        mobile_money: '📱 Mobile Money',
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

const deleteAccount = () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce compte ? Toutes les transactions associées seront également supprimées.')) {
        router.delete(route('accounts.destroy', props.account.id), {
            onSuccess: () => {
                router.visit(route('accounts.index'));
            },
        });
    }
};
</script>
