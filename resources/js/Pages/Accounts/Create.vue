<template>
    <AuthenticatedLayout>
        <Head title="Nouveau compte" />

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link 
                        :href="route('accounts.index')"
                        class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                    >
                        ← Retour aux comptes
                    </Link>
                </div>

                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                            Créer un nouveau compte
                        </h2>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nom du compte *
                                </label>
                                <input 
                                    v-model="form.name"
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                />
                                <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Type de compte *
                                </label>
                                <select 
                                    v-model="form.type"
                                    id="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="">Sélectionnez un type</option>
                                    <option value="cash">💵 Espèces</option>
                                    <option value="banque">🏦 Banque</option>
                                    <option value="mobile_money">📱 Mobile Money</option>
                                </select>
                                <p v-if="form.errors.type" class="mt-1 text-sm text-red-600">{{ form.errors.type }}</p>
                            </div>

                            <div>
                                <label for="balance" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Solde initial *
                                </label>
                                <input 
                                    v-model="form.balance"
                                    id="balance"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                />
                                <p v-if="form.errors.balance" class="mt-1 text-sm text-red-600">{{ form.errors.balance }}</p>
                            </div>

                            <div>
                                <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Devise *
                                </label>
                                <select 
                                    v-model="form.currency"
                                    id="currency"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="XAF">XAF - Franc CFA</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="USD">USD - Dollar US</option>
                                    <option value="GBP">GBP - Livre Sterling</option>
                                </select>
                                <p v-if="form.errors.currency" class="mt-1 text-sm text-red-600">{{ form.errors.currency }}</p>
                            </div>

                            <div class="flex items-center justify-end space-x-4">
                                <Link 
                                    :href="route('accounts.index')"
                                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                >
                                    Annuler
                                </Link>
                                <button 
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-50"
                                >
                                    Créer le compte
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
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm({
    name: '',
    type: '',
    balance: 0,
    currency: 'XAF',
});

const submit = () => {
    form.post(route('accounts.store'));
};
</script>
