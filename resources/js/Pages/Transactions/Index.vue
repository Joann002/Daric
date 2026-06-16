<template>
    <AuthenticatedLayout>
        <Head title="Transactions" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Transactions
                    </h2>
                    <Link 
                        :href="route('transactions.create')"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        + Nouvelle transaction
                    </Link>
                </div>

                <!-- Filtres -->
                <div class="mb-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Filtres</h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Compte</label>
                                <select 
                                    v-model="filters.account_id"
                                    @change="applyFilters"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                >
                                    <option value="">Tous les comptes</option>
                                    <option v-for="account in accounts" :key="account.id" :value="account.id">
                                        {{ account.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catégorie</label>
                                <select 
                                    v-model="filters.category_id"
                                    @change="applyFilters"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                >
                                    <option value="">Toutes les catégories</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.icon }} {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                                <select 
                                    v-model="filters.type"
                                    @change="applyFilters"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                >
                                    <option value="">Tous</option>
                                    <option value="income">Revenus</option>
                                    <option value="expense">Dépenses</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Recherche</label>
                                <input 
                                    v-model="filters.search"
                                    @input="applyFilters"
                                    type="text"
                                    placeholder="Description..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des transactions -->
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <TransactionList :transactions="transactions.data" />
                        
                        <!-- Pagination -->
                        <div v-if="transactions.links.length > 3" class="mt-6 flex justify-center space-x-2">
                            <Link 
                                v-for="link in transactions.links" 
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
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TransactionList from '@/Components/TransactionList.vue';

const props = defineProps({
    transactions: Object,
    accounts: Array,
    categories: Array,
    filters: Object,
});

const filters = ref({
    account_id: props.filters.account_id || '',
    category_id: props.filters.category_id || '',
    type: props.filters.type || '',
    search: props.filters.search || '',
});

const applyFilters = () => {
    router.get(route('transactions.index'), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>
