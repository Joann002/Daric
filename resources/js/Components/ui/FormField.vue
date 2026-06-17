<script setup>
defineProps({
    label: { type: String, default: null },
    error: { type: String, default: null },
    required: { type: Boolean, default: false },
    hint: { type: String, default: null },
});
</script>

<template>
    <div>
        <label v-if="label" class="label">
            {{ label }}
            <span v-if="required" class="text-rose-500">*</span>
        </label>
        <slot />
        <p
            v-if="hint && !error"
            class="mt-1 text-xs text-slate-400 dark:text-slate-500"
        >
            {{ hint }}
        </p>
        <p v-if="error" class="mt-1 text-sm text-rose-600 dark:text-rose-400">
            {{ error }}
        </p>
    </div>
</template>
