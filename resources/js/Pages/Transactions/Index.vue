<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/Icon.vue';
import TransactionList from '@/Components/TransactionList.vue';

const props = defineProps({
    transactions: Object,
    accounts: Array,
    categories: Array,
    filters: Object,
});

const filters = ref({
    account_id: props.filters.account_id || '',
    category_id: props.filters.category_id || '',
    type: props.filters.type || '',
    search: props.filters.search || '',
});

const applyFilters = () => {
    router.get(route('transactions.index'), filters.value, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Transactions" />

        <PageHeader
            title="Transactions"
            subtitle="Tous vos revenus et dépenses"
        >
            <template #actions>
                <a
                    :href="route('export.transactions.csv')"
                    class="btn-secondary"
                >
                    <Icon name="download" class="h-4 w-4" />
                    Exporter
                </a>
                <Link :href="route('transactions.create')" class="btn-primary">
                    <Icon name="plus" class="h-4 w-4" />
                    Nouvelle transaction
                </Link>
            </template>
        </PageHeader>

        <!-- Filters -->
        <Card class="mb-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="label">Compte</label>
                    <select
                        v-model="filters.account_id"
                        @change="applyFilters"
                        class="input"
                    >
                        <option value="">Tous les comptes</option>
                        <option
                            v-for="account in accounts"
                            :key="account.id"
                            :value="account.id"
                        >
                            {{ account.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="label">Catégorie</label>
                    <select
                        v-model="filters.category_id"
                        @change="applyFilters"
                        class="input"
                    >
                        <option value="">Toutes les catégories</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                <div>
                    <label class="label">Type</label>
                    <select
                        v-model="filters.type"
                        @change="applyFilters"
                        class="input"
                    >
                        <option value="">Tous</option>
                        <option value="income">Revenus</option>
                        <option value="expense">Dépenses</option>
                    </select>
                </div>
                <div>
                    <label class="label">Recherche</label>
                    <input
                        v-model="filters.search"
                        @input="applyFilters"
                        type="text"
                        placeholder="Description…"
                        class="input"
                    />
                </div>
            </div>
        </Card>

        <!-- List -->
        <Card :padded="false" class="overflow-hidden">
            <div class="p-2 sm:p-3">
                <TransactionList
                    v-if="transactions.data.length"
                    :transactions="transactions.data"
                />
                <EmptyState
                    v-else
                    icon="transactions"
                    title="Aucune transaction"
                    description="Aucune transaction ne correspond à ces filtres."
                />
            </div>

            <div
                v-if="transactions.links.length > 3"
                class="flex flex-wrap items-center justify-center gap-1.5 border-t border-slate-200/70 px-4 py-4 dark:border-slate-800"
            >
                <Link
                    v-for="link in transactions.links"
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
