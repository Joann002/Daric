<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/Icon.vue';
import { formatCurrency, formatDate } from '@/composables/useFormat';

defineProps({
    transfers: Object,
});

const deleteTransfer = (id) => {
    if (
        confirm(
            'Supprimer ce transfert ? Les soldes des comptes seront restaurés.',
        )
    ) {
        router.delete(route('transfers.destroy', id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Transferts" />

        <PageHeader title="Transferts" subtitle="Mouvements entre vos comptes">
            <template #actions>
                <Link :href="route('transfers.create')" class="btn-primary">
                    <Icon name="plus" class="h-4 w-4" />
                    Nouveau transfert
                </Link>
            </template>
        </PageHeader>

        <Card :padded="false" class="overflow-hidden">
            <ul
                v-if="transfers.data.length"
                class="divide-y divide-slate-100 dark:divide-slate-800"
            >
                <li
                    v-for="transfer in transfers.data"
                    :key="transfer.id"
                    class="flex items-center justify-between gap-3 px-5 py-4"
                >
                    <div class="flex min-w-0 items-center gap-3">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400"
                        >
                            <Icon name="transfers" />
                        </span>
                        <div class="min-w-0">
                            <p
                                class="flex items-center gap-1.5 truncate text-sm font-medium text-slate-900 dark:text-white"
                            >
                                {{ transfer.from_account.name }}
                                <Icon
                                    name="incoming"
                                    class="h-4 w-4 -rotate-45 text-slate-400"
                                />
                                {{ transfer.to_account.name }}
                            </p>
                            <p
                                class="truncate text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ formatDate(transfer.date) }}
                                <template v-if="transfer.description">
                                    · {{ transfer.description }}
                                </template>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <p
                            class="tnum text-sm font-bold text-sky-600 dark:text-sky-400"
                        >
                            {{ formatCurrency(transfer.amount) }}
                        </p>
                        <button
                            @click="deleteTransfer(transfer.id)"
                            class="btn-ghost !p-2 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                            aria-label="Supprimer"
                        >
                            <Icon name="trash" class="h-4 w-4" />
                        </button>
                    </div>
                </li>
            </ul>
            <EmptyState
                v-else
                icon="transfers"
                title="Aucun transfert"
                description="Déplacez de l'argent entre vos comptes en un transfert."
            >
                <template #action>
                    <Link :href="route('transfers.create')" class="btn-primary">
                        <Icon name="plus" class="h-4 w-4" />
                        Nouveau transfert
                    </Link>
                </template>
            </EmptyState>

            <div
                v-if="transfers.links.length > 3"
                class="flex flex-wrap items-center justify-center gap-1.5 border-t border-slate-200/70 px-4 py-4 dark:border-slate-800"
            >
                <Link
                    v-for="link in transfers.links"
                    :key="link.label"
                    :href="link.url"
                    :class="[
                        'min-w-9 rounded-lg px-3 py-1.5 text-center text-sm transition',
                        link.active
                            ? 'bg-brand-600 text-white'
                            : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800',
                        !link.url && 'cursor-not-allowed opacity-40',
                    ]"
                    v-html="link.label"
                />
            </div>
        </Card>
    </AuthenticatedLayout>
</template>
