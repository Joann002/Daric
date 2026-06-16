<template>
    <Link 
        :href="route('accounts.show', account.id)"
        class="block rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-800"
    >
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="text-2xl">
                    {{ accountIcon }}
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">
                        {{ account.name }}
                    </h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ accountType }}
                    </p>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <p class="text-2xl font-bold" :class="balanceClass">
                {{ formatCurrency(account.balance) }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ account.currency }}
            </p>
        </div>
    </Link>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    account: Object,
});

const accountIcon = computed(() => {
    const icons = {
        cash: '💵',
        banque: '🏦',
        mobile_money: '📱',
    };
    return icons[props.account.type] || '💳';
});

const accountType = computed(() => {
    const types = {
        cash: 'Espèces',
        banque: 'Banque',
        mobile_money: 'Mobile Money',
    };
    return types[props.account.type] || props.account.type;
});

const balanceClass = computed(() => {
    return props.account.balance >= 0 
        ? 'text-green-600 dark:text-green-400' 
        : 'text-red-600 dark:text-red-400';
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
        style: 'decimal',
        minimumFractionDigits: 0,
    }).format(amount);
};
</script>
