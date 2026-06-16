<template>
    <AuthenticatedLayout>
        <Head title="Créer un budget" />

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="mb-6">
                    <Link 
                        :href="route('budgets.index')"
                        class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                    >
                        ← Retour aux budgets
                    </Link>
                </div>

                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                            Créer un nouveau budget
                        </h2>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Catégorie de dépense *
                                </label>
                                <select 
                                    v-model="form.category_id"
                                    id="category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                >
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option v-for="category in expenseCategories" :key="category.id" :value="category.id">
                                        {{ category.icon }} {{ category.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</p>
                            </div>

                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Mois *
                                </label>
                                <input 
                                    v-model="form.month"
                                    id="month"
                                    type="month"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                />
                                <p v-if="form.errors.month" class="mt-1 text-sm text-red-600">{{ form.errors.month }}</p>
                            </div>

                            <div>
                                <label for="limit_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Montant limite *
                                </label>
                                <input 
                                    v-model="form.limit_amount"
                                    id="limit_amount"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                    required
                                />
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Montant maximum que vous souhaitez dépenser dans cette catégorie ce mois-ci
                                </p>
                                <p v-if="form.errors.limit_amount" class="mt-1 text-sm text-red-600">{{ form.errors.limit_amount }}</p>
                            </div>

                            <div class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
                                <p class="text-sm text-blue-800 dark:text-blue-200">
                                    💡 <strong>Astuce :</strong> Définissez des budgets réalistes basés sur vos dépenses habituelles. Vous recevrez des alertes visuelles lorsque vous dépassez 80% du budget.
                                </p>
                            </div>

                            <div class="flex items-center justify-end space-x-4">
                                <Link 
                                    :href="route('budgets.index')"
                                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                >
                                    Annuler
                                </Link>
                                <button 
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 disabled:opacity-50"
                                >
                                    Créer le budget
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
import { ref } from 'vue';

const props = defineProps({
    expenseCategories: Array,
});

const currentMonth = new Date().toISOString().slice(0, 7);

const form = useForm({
    category_id: '',
    month: currentMonth,
    limit_amount: '',
});

const submit = () => {
    form.post(route('budgets.store'));
};
</script>
