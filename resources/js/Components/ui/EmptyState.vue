<script setup>
import Icon from '@/Components/Icon.vue';

defineProps({
    icon: { type: String, default: 'sparkles' },
    title: { type: String, required: true },
    description: { type: String, default: null },
});
</script>

<template>
    <div class="flex flex-col items-center justify-center px-6 py-12 text-center">
        <span
            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500"
        >
            <Icon :name="icon" class="h-7 w-7" />
        </span>
        <h3 class="mt-4 text-base font-semibold text-slate-900 dark:text-white">
            {{ title }}
        </h3>
        <p
            v-if="description"
            class="mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400"
        >
            {{ description }}
        </p>
        <div v-if="$slots.action" class="mt-5">
            <slot name="action" />
        </div>
    </div>
</template>
