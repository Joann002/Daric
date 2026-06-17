<script setup>
import Icon from '@/Components/Icon.vue';
import { formatCurrency, formatDate } from '@/composables/useFormat';

defineProps({
    transactions: Array,
});
</script>

<template>
    <ul class="divide-y divide-slate-100 dark:divide-slate-800">
        <li
            v-for="transaction in transactions"
            :key="transaction.id"
            class="flex items-center justify-between gap-3 rounded-xl px-3 py-3 transition hover:bg-slate-50 dark:hover:bg-slate-800/50"
        >
            <div class="flex min-w-0 items-center gap-3">
                <span
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full"
                    :class="
                        transaction.type === 'income'
                            ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400'
                            : 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400'
                    "
                >
                    <Icon
                        :name="
                            transaction.type === 'income'
                                ? 'incoming'
                                : 'outgoing'
                        "
                    />
                </span>
                <div class="min-w-0">
                    <p
                        class="truncate font-medium text-slate-900 dark:text-white"
                    >
                        {{ transaction.category?.name || 'Sans catégorie' }}
                    </p>
                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                        {{ transaction.account?.name }} ·
                        {{ formatDate(transaction.date) }}
                        <template v-if="transaction.description">
                            · {{ transaction.description }}
                        </template>
                    </p>
                </div>
            </div>
            <p
                class="tnum shrink-0 text-sm font-bold"
                :class="
                    transaction.type === 'income'
                        ? 'text-emerald-600 dark:text-emerald-400'
                        : 'text-rose-600 dark:text-rose-400'
                "
            >
                {{ transaction.type === 'income' ? '+' : '−'
                }}{{ formatCurrency(transaction.amount) }}
            </p>
        </li>
    </ul>
</template>
