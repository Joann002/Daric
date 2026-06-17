<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Icon from '@/Components/Icon.vue';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    account: Object,
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

const meta = computed(() => typeMeta[props.account.type] || typeMeta.banque);

const balanceClass = computed(() =>
    props.account.balance >= 0
        ? 'text-slate-900 dark:text-white'
        : 'text-rose-600 dark:text-rose-400',
);
</script>

<template>
    <Link
        :href="route('accounts.show', account.id)"
        class="group block rounded-xl border border-slate-200/70 bg-white p-4 transition hover:border-brand-300 hover:shadow-card-hover dark:border-slate-800 dark:bg-slate-900 dark:hover:border-brand-500/40"
    >
        <div class="flex items-center gap-3">
            <span
                class="flex h-10 w-10 items-center justify-center rounded-xl"
                :class="meta.tone"
            >
                <Icon :name="meta.icon" />
            </span>
            <div>
                <h4 class="font-semibold text-slate-900 dark:text-white">
                    {{ account.name }}
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    {{ meta.label }}
                </p>
            </div>
        </div>
        <p class="tnum mt-3 text-xl font-bold" :class="balanceClass">
            {{ formatCurrency(account.balance) }}
        </p>
    </Link>
</template>
