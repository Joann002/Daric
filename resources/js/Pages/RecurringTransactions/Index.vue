<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Badge from '@/Components/ui/Badge.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/Icon.vue';
import { formatCurrency, formatDate } from '@/composables/useFormat';

defineProps({
    recurringTransactions: Array,
});

const frequencyLabels = {
    daily: 'Quotidien',
    weekly: 'Hebdomadaire',
    monthly: 'Mensuel',
    yearly: 'Annuel',
};

const deleteRecurring = (id) => {
    if (confirm('Supprimer cette transaction récurrente ?')) {
        router.delete(route('recurring-transactions.destroy', id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Transactions récurrentes" />

        <PageHeader
            title="Transactions récurrentes"
            subtitle="Automatisez vos opérations régulières"
        >
            <template #actions>
                <Link
                    :href="route('recurring-transactions.create')"
                    class="btn-primary"
                >
                    <Icon name="plus" class="h-4 w-4" />
                    Nouvelle récurrence
                </Link>
            </template>
        </PageHeader>

        <Card v-if="recurringTransactions.length" :padded="false" class="overflow-hidden">
            <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                <li
                    v-for="item in recurringTransactions"
                    :key="item.id"
                    class="flex items-center justify-between gap-3 px-5 py-4"
                >
                    <div class="flex min-w-0 items-center gap-3">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                            :class="
                                item.type === 'income'
                                    ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                                    : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400'
                            "
                        >
                            <Icon name="recurring" />
                        </span>
                        <div class="min-w-0">
                            <p
                                class="truncate font-medium text-slate-900 dark:text-white"
                            >
                                {{ item.category?.name || 'Sans catégorie' }}
                            </p>
                            <p
                                class="truncate text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ item.account?.name }} ·
                                {{ frequencyLabels[item.frequency] }} · prochaine
                                {{ formatDate(item.next_date) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <Badge :variant="item.is_active ? 'success' : 'neutral'">
                            {{ item.is_active ? 'Active' : 'En pause' }}
                        </Badge>
                        <p
                            class="tnum hidden text-sm font-bold sm:block"
                            :class="
                                item.type === 'income'
                                    ? 'text-emerald-600 dark:text-emerald-400'
                                    : 'text-rose-600 dark:text-rose-400'
                            "
                        >
                            {{ item.type === 'income' ? '+' : '−'
                            }}{{ formatCurrency(item.amount) }}
                        </p>
                        <div class="flex gap-1">
                            <Link
                                :href="route('recurring-transactions.edit', item.id)"
                                class="btn-ghost !p-2"
                                aria-label="Modifier"
                            >
                                <Icon name="edit" class="h-4 w-4" />
                            </Link>
                            <button
                                @click="deleteRecurring(item.id)"
                                class="btn-ghost !p-2 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                                aria-label="Supprimer"
                            >
                                <Icon name="trash" class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </Card>

        <Card v-else>
            <EmptyState
                icon="recurring"
                title="Aucune récurrence"
                description="Automatisez salaires, loyers et abonnements récurrents."
            >
                <template #action>
                    <Link
                        :href="route('recurring-transactions.create')"
                        class="btn-primary"
                    >
                        <Icon name="plus" class="h-4 w-4" />
                        Nouvelle récurrence
                    </Link>
                </template>
            </EmptyState>
        </Card>
    </AuthenticatedLayout>
</template>
