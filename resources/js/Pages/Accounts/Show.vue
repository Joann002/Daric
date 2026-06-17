<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/Icon.vue';
import TransactionList from '@/Components/TransactionList.vue';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    account: Object,
});

const typeLabels = {
    cash: 'Espèces',
    banque: 'Banque',
    mobile_money: 'Mobile Money',
};

const deleteAccount = () => {
    if (
        confirm(
            'Supprimer ce compte ? Toutes les transactions associées seront aussi supprimées.',
        )
    ) {
        router.delete(route('accounts.destroy', props.account.id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="account.name" />

        <Link
            :href="route('accounts.index')"
            class="mb-4 inline-flex items-center gap-1 text-sm font-medium text-slate-500 transition hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"
        >
            <Icon name="chevronDown" class="h-4 w-4 rotate-90" />
            Retour aux comptes
        </Link>

        <div
            class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <h1
                    class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white"
                >
                    {{ account.name }}
                </h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ typeLabels[account.type] || account.type }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <Link
                    :href="route('accounts.edit', account.id)"
                    class="btn-secondary"
                >
                    <Icon name="edit" class="h-4 w-4" />
                    Modifier
                </Link>
                <button @click="deleteAccount" class="btn-danger">
                    <Icon name="trash" class="h-4 w-4" />
                    Supprimer
                </button>
            </div>
        </div>

        <!-- Balance hero -->
        <Card class="mb-6 text-center">
            <p class="text-sm text-slate-500 dark:text-slate-400">
                Solde actuel
            </p>
            <p
                class="tnum mt-2 text-4xl font-extrabold sm:text-5xl"
                :class="
                    account.balance >= 0
                        ? 'text-slate-900 dark:text-white'
                        : 'text-rose-600 dark:text-rose-400'
                "
            >
                {{ formatCurrency(account.balance) }}
            </p>
        </Card>

        <!-- Recent transactions -->
        <Card :padded="false" class="overflow-hidden">
            <div
                class="flex items-center justify-between border-b border-slate-200/70 px-5 py-4 dark:border-slate-800 sm:px-6"
            >
                <h3 class="text-base font-semibold">Dernières transactions</h3>
                <Link
                    :href="route('transactions.create')"
                    class="btn-primary !px-3 !py-1.5 text-xs"
                >
                    <Icon name="plus" class="h-4 w-4" />
                    Nouvelle
                </Link>
            </div>
            <div class="p-2 sm:p-3">
                <TransactionList
                    v-if="account.transactions.length"
                    :transactions="account.transactions"
                />
                <EmptyState
                    v-else
                    icon="transactions"
                    title="Aucune transaction"
                    description="Ce compte n'a pas encore de transaction."
                />
            </div>
            <div
                v-if="account.transactions.length"
                class="border-t border-slate-200/70 px-5 py-3 text-center dark:border-slate-800"
            >
                <Link
                    :href="`/transactions?account_id=${account.id}`"
                    class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400"
                >
                    Voir toutes les transactions de ce compte
                </Link>
            </div>
        </Card>
    </AuthenticatedLayout>
</template>
