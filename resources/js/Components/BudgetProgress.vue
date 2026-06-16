<template>
    <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
        <div class="mb-2 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-xl">{{ budget.category.icon }}</span>
                <h4 class="font-semibold text-gray-900 dark:text-white">
                    {{ budget.category.name }}
                </h4>
            </div>
            <span :class="statusClass" class="text-sm font-medium">
                {{ Math.round(budget.progress_percentage) }}%
            </span>
        </div>
        
        <div class="mb-2 h-3 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
            <div 
                :class="progressBarClass"
                class="h-full transition-all duration-300"
                :style="{ width: `${Math.min(budget.progress_percentage, 100)}%` }"
            ></div>
        </div>
        
        <div class="flex items-center justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">
                {{ formatCurrency(budget.spent_amount) }} / {{ formatCurrency(budget.limit_amount) }}
            </span>
            <span :class="remainingClass" class="font-medium">
                {{ budget.remaining >= 0 ? 'Reste' : 'Dépassé' }}: 
                {{ formatCurrency(Math.abs(budget.remaining)) }}
            </span>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    budget: Object,
});

const statusClass = computed(() => {
    if (props.budget.progress_percentage >= 100) {
        return 'text-red-600 dark:text-red-400';
    } else if (props.budget.progress_percentage >= 80) {
        return 'text-yellow-600 dark:text-yellow-400';
    }
    return 'text-green-600 dark:text-green-400';
});

const progressBarClass = computed(() => {
    if (props.budget.progress_percentage >= 100) {
        return 'bg-red-500';
    } else if (props.budget.progress_percentage >= 80) {
        return 'bg-yellow-500';
    }
    return 'bg-green-500';
});

const remainingClass = computed(() => {
    return props.budget.remaining >= 0 
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
