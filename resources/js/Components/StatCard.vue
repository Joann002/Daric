<script setup>
import { computed } from 'vue';
import Icon from '@/Components/Icon.vue';
import Sparkline from '@/Components/Sparkline.vue';

const props = defineProps({
    title: String,
    value: String,
    subtitle: { type: String, default: null },
    icon: { type: String, default: 'banknotes' },
    tone: { type: String, default: 'brand' }, // brand | emerald | rose | sky | amber | slate
    sparkline: { type: Array, default: null },
});

const tones = {
    brand: 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400',
    emerald:
        'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
    rose: 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-400',
    sky: 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-400',
    amber: 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
    slate: 'bg-slate-100 text-slate-600 dark:bg-slate-700/40 dark:text-slate-300',
};

const sparkColors = {
    brand: '#10b981',
    emerald: '#10b981',
    rose: '#f43f5e',
    sky: '#0ea5e9',
    amber: '#f59e0b',
    slate: '#64748b',
};

const iconTone = computed(() => tones[props.tone] || tones.brand);
const sparkColor = computed(() => sparkColors[props.tone] || sparkColors.brand);
const hasSpark = computed(
    () => Array.isArray(props.sparkline) && props.sparkline.length > 1,
);
</script>

<template>
    <div class="card p-5 transition hover:shadow-card-hover">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
                <p
                    class="truncate text-sm font-medium text-slate-500 dark:text-slate-400"
                >
                    {{ title }}
                </p>
                <p
                    class="tnum mt-2 text-2xl font-bold tracking-tight text-slate-900 dark:text-white"
                >
                    {{ value }}
                </p>
                <p
                    v-if="subtitle"
                    class="mt-1 truncate text-xs text-slate-400 dark:text-slate-500"
                >
                    {{ subtitle }}
                </p>
            </div>
            <span
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl"
                :class="iconTone"
            >
                <Icon :name="icon" class="h-6 w-6" />
            </span>
        </div>
        <Sparkline
            v-if="hasSpark"
            :data="sparkline"
            :color="sparkColor"
            :width="200"
            :height="36"
            class="mt-3 w-full"
        />
    </div>
</template>
