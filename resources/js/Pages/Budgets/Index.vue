<template>
    <AuthenticatedLayout>
        <Head title="Budgets" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                            Budgets Mensuels
                        </h2>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">
                            {{ formatMonth(currentMonth) }}
                        </p>
                    </div>
                    <Link 
                        :href="route('budgets.create')"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        + Nouveau budget
                    </Link>
                </div>

                <div v-if="budgets.length > 0" class="space-y-4">
                    <div 
                        v-for="budget in budgets" 
                        :key="budget.id"
                        class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800"
                    >
                        <div class="p-6">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="text-3xl">{{ budget.category.icon }}</span>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ budget.category.name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Budget: {{ formatCurrency(budget.limit_amount) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <Link 
                                        :href="route('budgets.edit', budget.id)"
                                        class="rounded-md bg-indigo-100 px-3 py-1.5 text-sm font-medium text-indigo-700 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300"
                                    >
                                        Modifier
                                    </Link>
                                    <button 
                                        @click="deleteBudget(budget.id)"
                                        class="rounded-md bg-red-100 px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-200 dark:bg-red-900 dark:text-red-300"
                                    >
                                        Supprimer
                                    </button>
                                </div>
                            </div>

                            <!-- Barre de progression -->
                            <div class="mb-3 h-4 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                <div 
                                    :class="getProgressBarClass(budget.progress_percentage)"
                                    class="h-full transition-all duration-300"
                                    :style="{ width: `${Math.min(budget.progress_percentage, 100)}%` }"
                                ></div>
                            </div>

                            <!-- Statistiques -->
                            <div class="grid grid-cols-3 gap-4 text-center">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Dépensé</p>
                                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                                        {{ formatCurrency(budget.spent_amount) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Restant</p>
                                    <p 
                                        class="text-lg font-bold"
                                        :class="budget.remaining >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                                    >
                                        {{ formatCurrency(Math.abs(budget.remaining)) }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Progression</p>
                                    <p 
                                        class="text-lg font-bold"
                                        :class="getProgressClass(budget.progress_percentage)"
                                    >
                                        {{ Math.round(budget.progress_percentage) }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="rounded-lg bg-white p-12 text-center shadow dark:bg-gray-800">
                    <p class="text-gray-500 dark:text-gray-400">
                        Aucun budget défini pour ce mois. Créez votre premier budget pour suivre vos dépenses.
                    </p>
                    <Link 
                        :href="route('budgets.create')"
                        class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                    >
                        Créer un budget
                    </Link>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    budgets: Array,
    expenseCategories: Array,
    currentMonth: String,
});

const formatMonth = (month) => {
    const [year, monthNum] = month.split('-');
    const date = new Date(year, monthNum - 1);
    return date.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'decimal',
        minimumFractionDigits: 0,
    }).format(amount) + ' FCFA';
};

const getProgressBarClass = (percentage) => {
    if (percentage >= 100) return 'bg-red-500';
    if (percentage >= 80) return 'bg-yellow-500';
    return 'bg-green-500';
};

const getProgressClass = (percentage) => {
    if (percentage >= 100) return 'text-red-600 dark:text-red-400';
    if (percentage >= 80) return 'text-yellow-600 dark:text-yellow-400';
    return 'text-green-600 dark:text-green-400';
};

const deleteBudget = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce budget ?')) {
        router.delete(route('budgets.destroy', id));
    }
};
</script>
