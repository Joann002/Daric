<template>
    <AuthenticatedLayout>
        <Head title="Nouveau transfert" />

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link 
                        :href="route('transfers.index')"
                        class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                    >
                        ← Retour aux transferts
                    </Link>
                </div>

                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                            Effectuer un transfert
                        </h2>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="from_account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Compte source *
                                </label>
                                <select 
                                    v-model="form.from_account_id"
                                    id="from_account_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="">Depuis quel compte ?</option>
                                    <option v-for="account in accounts" :key="account.id" :value="account.id">
                                        {{ account.name }} ({{ formatCurrency(account.balance, account.currency) }})
                                    </option>
                                </select>
                                <p v-if="form.errors.from_account_id" class="mt-1 text-sm text-red-600">{{ form.errors.from_account_id }}</p>
                            </div>

                            <div class="flex justify-center">
                                <div class="rounded-full bg-indigo-100 p-3 dark:bg-indigo-900">
                                    <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                </div>
                            </div>

                            <div>
                                <label for="to_account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Compte destination *
                                </label>
                                <select 
                                    v-model="form.to_account_id"
                                    id="to_account_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="">Vers quel compte ?</option>
                                    <option 
                                        v-for="account in accounts" 
                                        :key="account.id" 
                                        :value="account.id"
                                        :disabled="account.id == form.from_account_id"
                                    >
                                        {{ account.name }} ({{ formatCurrency(account.balance, account.currency) }})
                                    </option>
                                </select>
                                <p v-if="form.errors.to_account_id" class="mt-1 text-sm text-red-600">{{ form.errors.to_account_id }}</p>
                            </div>

                            <div>
                                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Montant à transférer *
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
                                    Date du transfert *
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
                                    Description (optionnel)
                                </label>
                                <textarea 
                                    v-model="form.description"
                                    id="description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    placeholder="Ex: Rechargement du compte mobile money"
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>

                            <div class="rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/20">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                    ⚠️ <strong>Attention :</strong> Le solde des deux comptes sera automatiquement mis à jour lors du transfert.
                                </p>
                            </div>

                            <div class="flex items-center justify-end space-x-4">
                                <Link 
                                    :href="route('transfers.index')"
                                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                >
                                    Annuler
                                </Link>
                                <button 
                                    type="submit"
                                    :disabled="form.processing || !isValid"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-50"
                                >
                                    Effectuer le transfert
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
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    accounts: Array,
});

const form = useForm({
    from_account_id: '',
    to_account_id: '',
    amount: '',
    date: new Date().toISOString().split('T')[0],
    description: '',
});

const isValid = computed(() => {
    return form.from_account_id && 
           form.to_account_id && 
           form.from_account_id !== form.to_account_id &&
           form.amount > 0;
});

const formatCurrency = (amount, currency = 'XAF') => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: currency,
        minimumFractionDigits: 0,
    }).format(amount);
};

const submit = () => {
    form.post(route('transfers.store'));
};
</script>
