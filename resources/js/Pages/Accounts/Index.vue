<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/Icon.vue';
import { formatCurrency } from '@/composables/useFormat';

defineProps({
    accounts: Array,
});

const typeMeta = {
    cash: {
        label: 'Espèces',
        icon: 'banknotes',
        tone: 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
    },
    banque: {
        label: 'Banque',
        icon: 'wallet',
        tone: 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400',
    },
    mobile_money: {
        label: 'Mobile Money',
        icon: 'transfers',
        tone: 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
    },
};

const meta = (type) => typeMeta[type] || typeMeta.banque;

const deleteAccount = (id) => {
    if (
        confirm(
            'Supprimer ce compte ? Toutes les transactions associées seront aussi supprimées.',
        )
    ) {
        router.delete(route('accounts.destroy', id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Mes comptes" />

        <PageHeader title="Mes comptes" subtitle="Gérez vos comptes et soldes">
            <template #actions>
                <Link :href="route('accounts.create')" class="btn-primary">
                    <Icon name="plus" class="h-4 w-4" />
                    Nouveau compte
                </Link>
            </template>
        </PageHeader>

        <div
            v-if="accounts.length"
            class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3"
        >
            <Card v-for="account in accounts" :key="account.id">
                <div class="flex items-center gap-3">
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        :class="meta(account.type).tone"
                    >
                        <Icon :name="meta(account.type).icon" class="h-6 w-6" />
                    </span>
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white">
                            {{ account.name }}
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ meta(account.type).label }}
                        </p>
                    </div>
                </div>

                <p
                    class="tnum mt-4 text-2xl font-bold"
                    :class="
                        account.balance >= 0
                            ? 'text-slate-900 dark:text-white'
                            : 'text-rose-600 dark:text-rose-400'
                    "
                >
                    {{ formatCurrency(account.balance) }}
                </p>

                <div class="mt-5 flex items-center gap-2">
                    <Link
                        :href="route('accounts.show', account.id)"
                        class="btn-secondary flex-1 !py-2 text-xs"
                    >
                        Voir
                    </Link>
                    <Link
                        :href="route('accounts.edit', account.id)"
                        class="btn-secondary !py-2 text-xs"
                        aria-label="Modifier"
                    >
                        <Icon name="edit" class="h-4 w-4" />
                    </Link>
                    <button
                        @click="deleteAccount(account.id)"
                        class="btn-ghost !py-2 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                        aria-label="Supprimer"
                    >
                        <Icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </Card>
        </div>

        <Card v-else>
            <EmptyState
                icon="wallet"
                title="Aucun compte"
                description="Créez votre premier compte pour commencer à suivre vos finances."
            >
                <template #action>
                    <Link :href="route('accounts.create')" class="btn-primary">
                        <Icon name="plus" class="h-4 w-4" />
                        Nouveau compte
                    </Link>
                </template>
            </EmptyState>
        </Card>
    </AuthenticatedLayout>
</template>
