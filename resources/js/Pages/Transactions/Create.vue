<template>
    <AuthenticatedLayout>
        <Head title="Nouvelle transaction" />

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link 
                        :href="route('transactions.index')"
                        class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                    >
                        ← Retour aux transactions
                    </Link>
                </div>

                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                            Nouvelle transaction
                        </h2>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Type *
                                </label>
                                <select 
                                    v-model="form.type"
                                    id="type"
                                    @change="filterCategories"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="">Sélectionnez un type</option>
                                    <option value="income">📈 Revenu</option>
                                    <option value="expense">📉 Dépense</option>
                                </select>
                                <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
                            </div>

                            <div>
                                <label for="account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Compte *
                                </label>
                                <select 
                                    v-model="form.account_id"
                                    id="account_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="">Sélectionnez un compte</option>
                                    <option v-for="account in accounts" :key="account.id" :value="account.id">
                                        {{ account.name }} ({{ formatCurrency(account.balance, account.currency) }})
                                    </option>
                                </select>
                                <p v-if="form.errors.account_id" class="mt-1 text-sm text-red-600">{{ form.errors.account_id }}</p>
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Catégorie *
                                </label>
                                <select 
                                    v-model="form.category_id"
                                    id="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option v-for="category in filteredCategories" :key="category.id" :value="category.id">
                                        {{ category.icon }} {{ category.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</p>
                            </div>

                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Montant *
                                </label>
                                <input 
                                    v-model="form.amount"
                                    id="amount"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                />
                                <p v-if="form.errors.amount" class="mt-1 text-sm text-red-600">{{ form.errors.amount }}</p>
                            </div>

                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Date *
                                </label>
                                <input 
                                    v-model="form.date"
                                    id="date"
                                    type="date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                />
                                <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">{{ form.errors.date }}</p>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Description
                                </label>
                                <textarea 
                                    v-model="form.description"
                                    id="description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>

                            <div class="flex items-center justify-end space-x-4">
                                <Link 
                                    :href="route('transactions.index')"
                                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                >
                                    Annuler
                                </Link>
                                <button 
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-50"
                                >
                                    Créer la transaction
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    accounts: Array,
    categories: Array,
});

const form = useForm({
    type: '',
    account_id: '',
    category_id: '',
    amount: '',
    date: new Date().toISOString().split('T')[0],
    description: '',
});

const filteredCategories = computed(() => {
    if (!form.type) return [];
    return props.categories.filter(cat => cat.type === form.type);
});

const filterCategories = () => {
    form.category_id = '';
};

const formatCurrency = (amount, currency = 'XAF') => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
    }).format(amount);
};

const submit = () => {
    form.post(route('transactions.store'));
};
</script>
