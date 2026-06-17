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
    debts: Array,
});

const statusMeta = {
    pending: { label: 'En attente', variant: 'warning' },
    partially_paid: { label: 'Partiel', variant: 'info' },
    paid: { label: 'Réglé', variant: 'success' },
};

const deleteDebt = (id) => {
    if (confirm('Supprimer cet enregistrement ?')) {
        router.delete(route('debts.destroy', id));
    }
};

const progress = (debt) =>
    debt.amount > 0 ? Math.min((debt.paid_amount / debt.amount) * 100, 100) : 0;
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dettes & prêts" />

        <PageHeader
            title="Dettes & prêts"
            subtitle="Ce que vous devez et ce qu'on vous doit"
        >
            <template #actions>
                <Link :href="route('debts.create')" class="btn-primary">
                    <Icon name="plus" class="h-4 w-4" />
                    Nouvel enregistrement
                </Link>
            </template>
        </PageHeader>

        <div v-if="debts.length" class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <Card v-for="debt in debts" :key="debt.id">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span
                            class="flex h-10 w-10 items-center justify-center rounded-xl"
                            :class="
                                debt.direction === 'je_dois'
                                    ? 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400'
                                    : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                            "
                        >
                            <Icon
                                :name="
                                    debt.direction === 'je_dois'
                                        ? 'outgoing'
                                        : 'incoming'
                                "
                            />
                        </span>
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-white">
                                {{ debt.person_name }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                {{
                                    debt.direction === 'je_dois'
                                        ? 'Je dois'
                                        : 'On me doit'
                                }}
                            </p>
                        </div>
                    </div>
                    <Badge :variant="statusMeta[debt.status]?.variant || 'neutral'">
                        {{ statusMeta[debt.status]?.label || debt.status }}
                    </Badge>
                </div>

                <div class="mt-4">
                    <div class="flex items-baseline justify-between">
                        <span class="text-sm text-slate-500 dark:text-slate-400">
                            Restant
                        </span>
                        <span
                            class="tnum text-lg font-bold text-slate-900 dark:text-white"
                        >
                            {{ formatCurrency(debt.remaining_amount) }}
                        </span>
                    </div>
                    <div
                        class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"
                    >
                        <div
                            class="h-full rounded-full bg-brand-500 transition-all duration-500"
                            :style="{ width: `${progress(debt)}%` }"
                        />
                    </div>
                    <div
                        class="mt-2 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400"
                    >
                        <span class="tnum">
                            {{ formatCurrency(debt.paid_amount) }} /
                            {{ formatCurrency(debt.amount) }}
                        </span>
                        <span v-if="debt.due_date">
                            Échéance {{ formatDate(debt.due_date) }}
                        </span>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-1">
                    <Link
                        :href="route('debts.edit', debt.id)"
                        class="btn-ghost !p-2"
                        aria-label="Modifier"
                    >
                        <Icon name="edit" class="h-4 w-4" />
                    </Link>
                    <button
                        @click="deleteDebt(debt.id)"
                        class="btn-ghost !p-2 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                        aria-label="Supprimer"
                    >
                        <Icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </Card>
        </div>

        <Card v-else>
            <EmptyState
                icon="debts"
                title="Aucune dette ni prêt"
                description="Suivez l'argent que vous devez ou qu'on vous doit."
            >
                <template #action>
                    <Link :href="route('debts.create')" class="btn-primary">
                        <Icon name="plus" class="h-4 w-4" />
                        Nouvel enregistrement
                    </Link>
                </template>
            </EmptyState>
        </Card>
    </AuthenticatedLayout>
</template>
