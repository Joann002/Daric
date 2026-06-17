<script setup>
import { computed } from 'vue';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    budget: Object,
});

const pct = computed(() => props.budget.progress_percentage);

const barClass = computed(() => {
    if (pct.value >= 100) return 'bg-rose-500';
    if (pct.value >= 80) return 'bg-amber-500';
    return 'bg-brand-500';
});

const statusClass = computed(() => {
    if (pct.value >= 100) return 'text-rose-600 dark:text-rose-400';
    if (pct.value >= 80) return 'text-amber-600 dark:text-amber-400';
    return 'text-brand-600 dark:text-brand-400';
});

const remainingClass = computed(() =>
    props.budget.remaining >= 0
        ? 'text-slate-500 dark:text-slate-400'
        : 'text-rose-600 dark:text-rose-400',
);
</script>

<template>
    <div>
        <div class="mb-2 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span
                    class="h-3 w-3 rounded-full"
                    :style="{ backgroundColor: budget.category.color }"
                />
                <h4 class="text-sm font-semibold text-slate-900 dark:text-white">
                    {{ budget.category.name }}
                </h4>
            </div>
            <span class="tnum text-sm font-semibold" :class="statusClass">
                {{ Math.round(pct) }}%
            </span>
        </div>

        <div
            class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"
        >
            <div
                class="h-full rounded-full transition-all duration-500"
                :class="barClass"
                :style="{ width: `${Math.min(pct, 100)}%` }"
            />
        </div>

        <div class="mt-2 flex items-center justify-between text-xs">
            <span class="tnum text-slate-500 dark:text-slate-400">
                {{ formatCurrency(budget.spent_amount) }} /
                {{ formatCurrency(budget.limit_amount) }}
            </span>
            <span class="tnum font-medium" :class="remainingClass">
                {{ budget.remaining >= 0 ? 'Reste' : 'Dépassé' }}
                {{ formatCurrency(Math.abs(budget.remaining)) }}
            </span>
        </div>
    </div>
</template>
