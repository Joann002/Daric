<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import AccountCard from '@/Components/AccountCard.vue';
// Lazy-loaded: ApexCharts & FullCalendar are heavy, split into their own chunks
const PieChart = defineAsyncComponent(() => import('@/Components/PieChart.vue'));
const LineChart = defineAsyncComponent(() => import('@/Components/LineChart.vue'));
const CalendarView = defineAsyncComponent(
    () => import('@/Components/CalendarView.vue'),
);
import BudgetProgress from '@/Components/BudgetProgress.vue';
import TransactionList from '@/Components/TransactionList.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/Icon.vue';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    totalBalance: Number,
    monthIncome: Number,
    monthExpense: Number,
    accounts: Array,
    recentTransactions: Array,
    expensesByCategory: Array,
    incomesByCategory: Array,
    monthlyEvolution: Array,
    budgets: Array,
    calendarEvents: Array,
    currentMonth: String,
});

const selectedMonth = ref(props.currentMonth);

const incomeSeries = computed(() => props.monthlyEvolution.map((m) => m.income));
const expenseSeries = computed(() =>
    props.monthlyEvolution.map((m) => m.expense),
);
const balanceSeries = computed(() =>
    props.monthlyEvolution.map((m) => m.balance),
);
const totalBalanceSeries = computed(() => {
    const series = props.accounts
        .map((a) => a.sparkline)
        .filter((s) => Array.isArray(s) && s.length);
    if (!series.length) return [];
    const length = Math.max(...series.map((s) => s.length));
    return Array.from({ length }, (_, i) =>
        series.reduce((sum, s) => sum + (Number(s[i]) || 0), 0),
    );
});

const loadData = () => {
    router.get(
        route('dashboard'),
        { month: selectedMonth.value },
        { preserveState: true, preserveScroll: true },
    );
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tableau de bord" />

        <PageHeader
            title="Tableau de bord"
            subtitle="Vue d'ensemble de vos finances"
        >
            <template #actions>
                <label class="relative">
                    <span class="sr-only">Mois</span>
                    <input
                        type="month"
                        v-model="selectedMonth"
                        @change="loadData"
                        class="input w-auto"
                    />
                </label>
            </template>
        </PageHeader>

        <!-- Stats -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <StatCard
                title="Solde total"
                :value="formatCurrency(totalBalance)"
                icon="wallet"
                tone="brand"
                :sparkline="totalBalanceSeries"
            />
            <StatCard
                title="Revenus du mois"
                :value="formatCurrency(monthIncome)"
                icon="trendingUp"
                tone="emerald"
                :sparkline="incomeSeries"
            />
            <StatCard
                title="Dépenses du mois"
                :value="formatCurrency(monthExpense)"
                icon="trendingDown"
                tone="rose"
                :sparkline="expenseSeries"
            />
            <StatCard
                title="Balance du mois"
                :value="formatCurrency(monthIncome - monthExpense)"
                icon="scale"
                :tone="monthIncome - monthExpense >= 0 ? 'emerald' : 'rose'"
                :sparkline="balanceSeries"
            />
        </div>

        <!-- Accounts -->
        <Card :padded="false" class="mt-6 overflow-hidden">
            <div
                class="flex items-center justify-between border-b border-slate-200/70 px-5 py-4 dark:border-slate-800 sm:px-6"
            >
                <h3 class="text-base font-semibold">Mes comptes</h3>
                <Link
                    :href="route('accounts.create')"
                    class="btn-primary !px-3 !py-1.5 text-xs"
                >
                    <Icon name="plus" class="h-4 w-4" />
                    Nouveau compte
                </Link>
            </div>
            <div class="p-5 sm:p-6">
                <div
                    v-if="accounts.length"
                    class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <AccountCard
                        v-for="account in accounts"
                        :key="account.id"
                        :account="account"
                    />
                </div>
                <EmptyState
                    v-else
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
            </div>
        </Card>

        <!-- Charts -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <Card>
                <h3 class="mb-4 text-base font-semibold">
                    Dépenses par catégorie
                </h3>
                <PieChart
                    v-if="expensesByCategory.length"
                    :data="expensesByCategory"
                />
                <EmptyState
                    v-else
                    icon="budgets"
                    title="Aucune dépense"
                    description="Aucune dépense enregistrée pour ce mois-ci."
                />
            </Card>

            <Card>
                <h3 class="mb-4 text-base font-semibold">
                    Évolution sur 6 mois
                </h3>
                <LineChart :data="monthlyEvolution" />
            </Card>
        </div>

        <!-- Calendar -->
        <Card class="mt-6">
            <h3 class="mb-4 text-base font-semibold">
                Calendrier des transactions
            </h3>
            <CalendarView
                :events="calendarEvents"
                :initial-date="currentMonth"
            />
        </Card>

        <!-- Budgets -->
        <Card v-if="budgets.length" class="mt-6">
            <h3 class="mb-4 text-base font-semibold">Budgets du mois</h3>
            <div class="space-y-5">
                <BudgetProgress
                    v-for="budget in budgets"
                    :key="budget.id"
                    :budget="budget"
                />
            </div>
        </Card>

        <!-- Recent transactions -->
        <Card :padded="false" class="mt-6 overflow-hidden">
            <div
                class="flex items-center justify-between border-b border-slate-200/70 px-5 py-4 dark:border-slate-800 sm:px-6"
            >
                <h3 class="text-base font-semibold">Dernières transactions</h3>
                <Link
                    :href="route('transactions.index')"
                    class="text-sm font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400"
                >
                    Voir toutes
                </Link>
            </div>
            <div class="p-2 sm:p-3">
                <TransactionList
                    v-if="recentTransactions.length"
                    :transactions="recentTransactions"
                />
                <EmptyState
                    v-else
                    icon="transactions"
                    title="Aucune transaction"
                    description="Vos transactions récentes apparaîtront ici."
                />
            </div>
        </Card>
    </AuthenticatedLayout>
</template>
