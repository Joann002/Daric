<template>
    <div class="space-y-3">
        <div 
            v-for="transaction in transactions" 
            :key="transaction.id"
            class="flex items-center justify-between rounded-lg border border-gray-200 p-4 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50"
        >
            <div class="flex items-center space-x-4">
                <div 
                    class="flex h-10 w-10 items-center justify-center rounded-full"
                    :style="{ backgroundColor: transaction.category.color + '20' }"
                >
                    <span class="text-xl">{{ transaction.category.icon }}</span>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">
                        {{ transaction.category.name }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ transaction.account.name }} • {{ formatDate(transaction.date) }}
                    </p>
                    <p v-if="transaction.description" class="text-xs text-gray-400 dark:text-gray-500">
                        {{ transaction.description }}
                    </p>
                </div>
            </div>
            <div class="text-right">
                <p 
                    class="text-lg font-bold"
                    :class="transaction.type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'"
                >
                    {{ transaction.type === 'income' ? '+' : '-' }}{{ formatCurrency(transaction.amount) }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ transactionType(transaction.type) }}
                </p>
            </div>
        </div>
        <p v-if="transactions.length === 0" class="py-8 text-center text-gray-500 dark:text-gray-400">
            Aucune transaction
        </p>
    </div>
</template>

<script setup>
const props = defineProps({
    transactions: Array,
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'decimal',
        minimumFractionDigits: 0,
    }).format(amount);
};

const transactionType = (type) => {
    return type === 'income' ? 'Revenu' : 'Dépense';
};
</script>
