<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import Badge from '@/Components/ui/Badge.vue';
import Icon from '@/Components/Icon.vue';
import { formatCurrency, formatDate } from '@/composables/useFormat';

const props = defineProps({
    transaction: Object,
});

const isIncome = props.transaction.type === 'income';

const remove = () => {
    if (confirm('Supprimer cette transaction ? Le solde sera ajusté.')) {
        router.delete(route('transactions.destroy', props.transaction.id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Transaction" />

        <div class="mx-auto max-w-2xl">
            <Link
                :href="route('transactions.index')"
                class="mb-4 inline-flex items-center gap-1 text-sm font-medium text-slate-500 transition hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"
            >
                <Icon name="chevronDown" class="h-4 w-4 rotate-90" />
                Retour aux transactions
            </Link>

            <Card>
                <div class="flex flex-col items-center text-center">
                    <span
                        class="flex h-14 w-14 items-center justify-center rounded-2xl"
                        :class="
                            isIncome
                                ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                                : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400'
                        "
                    >
                        <Icon :name="isIncome ? 'incoming' : 'outgoing'" class="h-7 w-7" />
                    </span>
                    <p
                        class="tnum mt-4 text-3xl font-extrabold"
                        :class="
                            isIncome
                                ? 'text-emerald-600 dark:text-emerald-400'
                                : 'text-rose-600 dark:text-rose-400'
                        "
                    >
                        {{ isIncome ? '+' : '−'
                        }}{{ formatCurrency(transaction.amount) }}
                    </p>
                    <Badge :variant="isIncome ? 'success' : 'danger'" class="mt-2">
                        {{ isIncome ? 'Revenu' : 'Dépense' }}
                    </Badge>
                </div>

                <dl class="mt-6 divide-y divide-slate-100 dark:divide-slate-800">
                    <div class="flex justify-between py-3 text-sm">
                        <dt class="text-slate-500 dark:text-slate-400">
                            Catégorie
                        </dt>
                        <dd class="font-medium text-slate-900 dark:text-white">
                            {{ transaction.category?.name }}
                        </dd>
                    </div>
                    <div class="flex justify-between py-3 text-sm">
                        <dt class="text-slate-500 dark:text-slate-400">Compte</dt>
                        <dd class="font-medium text-slate-900 dark:text-white">
                            {{ transaction.account?.name }}
                        </dd>
                    </div>
                    <div class="flex justify-between py-3 text-sm">
                        <dt class="text-slate-500 dark:text-slate-400">Date</dt>
                        <dd class="font-medium text-slate-900 dark:text-white">
                            {{ formatDate(transaction.date) }}
                        </dd>
                    </div>
                    <div
                        v-if="transaction.description"
                        class="flex justify-between gap-6 py-3 text-sm"
                    >
                        <dt class="text-slate-500 dark:text-slate-400">
                            Description
                        </dt>
                        <dd
                            class="text-right font-medium text-slate-900 dark:text-white"
                        >
                            {{ transaction.description }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <Link
                        :href="route('transactions.edit', transaction.id)"
                        class="btn-secondary"
                    >
                        <Icon name="edit" class="h-4 w-4" />
                        Modifier
                    </Link>
                    <button @click="remove" class="btn-danger">
                        <Icon name="trash" class="h-4 w-4" />
                        Supprimer
                    </button>
                </div>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
