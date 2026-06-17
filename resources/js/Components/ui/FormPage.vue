<script setup>
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/Icon.vue';

defineProps({
    title: { type: String, required: true },
    backHref: { type: String, required: true },
    backLabel: { type: String, default: 'Retour' },
    wide: { type: Boolean, default: false },
});
</script>

<template>
    <AuthenticatedLayout>
        <div :class="wide ? 'max-w-3xl' : 'max-w-2xl'" class="mx-auto">
            <Link
                :href="backHref"
                class="mb-4 inline-flex items-center gap-1 text-sm font-medium text-slate-500 transition hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"
            >
                <Icon name="chevronDown" class="h-4 w-4 rotate-90" />
                {{ backLabel }}
            </Link>
            <h1
                class="mb-6 text-2xl font-bold tracking-tight text-slate-900 dark:text-white"
            >
                {{ title }}
            </h1>
            <Card>
                <slot />
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
