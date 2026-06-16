<template>
    <AuthenticatedLayout>
        <Head title="Tableau de bord" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- En-tête avec sélecteur de mois -->
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Tableau de bord
                    </h2>
                    <input 
                        type="month" 
                        v-model="selectedMonth"
                        @change="loadData"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                    />
                </div>

                <!-- Cartes statistiques -->
                <div class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <StatCard 
                        title="Solde total"
                        :value="formatCurrency(totalBalance)"
                        icon="💰"
                        color="blue"
                    />
                    <StatCard 
                        title="Revenus du mois"
                        :value="formatCurrency(monthIncome)"
                        icon="📈"
                        color="green"
                    />
                    <StatCard 
                        title="Dépenses du mois"
                        :value="formatCurrency(monthExpense)"
                        icon="📉"
                        color="red"
                    />
                    <StatCard 
                        title="Balance"
                        :value="formatCurrency(monthIncome - monthExpense)"
                        icon="⚖️"
                        :color="monthIncome - monthExpense >= 0 ? 'green' : 'red'"
                    />
                </div>

                <!-- Comptes -->
                <div class="mb-8 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">
                            Mes comptes
                        </h3>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <AccountCard 
                                v-for="account in accounts" 
                                :key="account.id"
                                :account="account"
                            />
                        </div>
                        <Link 
                            :href="route('accounts.create')"
                            class="mt-4 inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500"
                        >
                            + Nouveau compte
                        </Link>
                    </div>
                </div>

                <!-- Graphiques -->
                <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Dépenses par catégorie -->
                    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Dépenses par catégorie
                            </h3>
                            <PieChart 
                                v-if="expensesByCategory.length > 0"
                                :data="expensesByCategory"
                            />
                            <p v-else class="text-gray-500 dark:text-gray-400">
                                Aucune dépense ce mois-ci
                            </p>
                        </div>
                    </div>

                    <!-- Évolution mensuelle -->
                    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">
                                Évolution des 6 derniers mois
                            </h3>
                            <LineChart 
                                :data="monthlyEvolution"
                            />
                        </div>
                    </div>
                </div>

                <!-- Budgets -->
                <div v-if="budgets.length > 0" class="mb-8 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">
                            Budgets du mois
                        </h3>
                        <div class="space-y-4">
                            <BudgetProgress 
                                v-for="budget in budgets" 
                                :key="budget.id"
                                :budget="budget"
                            />
                        </div>
                    </div>
                </div>

                <!-- Dernières transactions -->
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Dernières transactions
                            </h3>
                            <Link 
                                :href="route('transactions.index')"
                                class="text-sm text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
                            >
                                Voir toutes
                            </Link>
                        </div>
                        <TransactionList :transactions="recentTransactions" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import AccountCard from '@/Components/AccountCard.vue';
import PieChart from '@/Components/PieChart.vue';
import LineChart from '@/Components/LineChart.vue';
import BudgetProgress from '@/Components/BudgetProgress.vue';
import TransactionList from '@/Components/TransactionList.vue';

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
    currentMonth: String,
});

const selectedMonth = ref(props.currentMonth);

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XAF',
        minimumFractionDigits: 0,
    }).format(amount);
};

const loadData = () => {
    router.get(route('dashboard'), { month: selectedMonth.value }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>
