<template>
    <div :class="cardClasses" class="overflow-hidden rounded-lg shadow">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="text-3xl">{{ icon }}</span>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ title }}
                        </dt>
                        <dd class="mt-1 text-2xl font-semibold" :class="valueClass">
                            {{ value }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: String,
    value: String,
    icon: String,
    color: {
        type: String,
        default: 'blue',
    },
});

const cardClasses = computed(() => {
    return 'bg-white dark:bg-gray-800';
});

const valueClass = computed(() => {
    const colors = {
        blue: 'text-blue-600 dark:text-blue-400',
        green: 'text-green-600 dark:text-green-400',
        red: 'text-red-600 dark:text-red-400',
        yellow: 'text-yellow-600 dark:text-yellow-400',
    };
    return colors[props.color] || colors.blue;
});
</script>
