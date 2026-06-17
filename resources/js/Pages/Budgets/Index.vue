<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/Icon.vue';
import BudgetProgress from '@/Components/BudgetProgress.vue';

defineProps({
    budgets: Array,
    expenseCategories: Array,
    currentMonth: String,
});

const formatMonth = (month) => {
    const [year, monthNum] = month.split('-');
    const date = new Date(year, monthNum - 1);
    return date.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
};

const deleteBudget = (id) => {
    if (confirm('Supprimer ce budget ?')) {
        router.delete(route('budgets.destroy', id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Budgets" />

        <PageHeader
            title="Budgets mensuels"
            :subtitle="formatMonth(currentMonth)"
        >
            <template #actions>
                <Link :href="route('budgets.create')" class="btn-primary">
                    <Icon name="plus" class="h-4 w-4" />
                    Nouveau budget
                </Link>
            </template>
        </PageHeader>

        <div v-if="budgets.length" class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <Card v-for="budget in budgets" :key="budget.id">
                <div class="flex items-start gap-4">
                    <div class="min-w-0 flex-1">
                        <BudgetProgress :budget="budget" />
                    </div>
                    <div class="flex shrink-0 gap-1">
                        <Link
                            :href="route('budgets.edit', budget.id)"
                            class="btn-ghost !p-2"
                            aria-label="Modifier"
                        >
                            <Icon name="edit" class="h-4 w-4" />
                        </Link>
                        <button
                            @click="deleteBudget(budget.id)"
                            class="btn-ghost !p-2 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                            aria-label="Supprimer"
                        >
                            <Icon name="trash" class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </Card>
        </div>

        <Card v-else>
            <EmptyState
                icon="budgets"
                title="Aucun budget"
                description="Définissez un budget par catégorie pour suivre vos dépenses du mois."
            >
                <template #action>
                    <Link :href="route('budgets.create')" class="btn-primary">
                        <Icon name="plus" class="h-4 w-4" />
                        Créer un budget
                    </Link>
                </template>
            </EmptyState>
        </Card>
    </AuthenticatedLayout>
</template>
