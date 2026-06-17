<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-dvh flex-col items-center justify-center bg-slate-50 px-4 py-10 dark:bg-slate-950"
    >
        <!-- Subtle brand backdrop -->
        <div
            class="pointer-events-none fixed inset-0 -z-10 overflow-hidden"
            aria-hidden="true"
        >
            <div
                class="absolute -top-24 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-brand-400/20 blur-3xl"
            />
        </div>

        <Link href="/" class="flex items-center gap-2.5">
            <span
                class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm"
            >
                <ApplicationLogo class="h-6 w-6" />
            </span>
            <span class="text-2xl font-bold text-slate-900 dark:text-white">
                Daric
            </span>
        </Link>

        <div class="mt-8 w-full sm:max-w-md">
            <div class="card p-6 sm:p-8">
                <slot />
            </div>
        </div>

        <p class="mt-6 text-xs text-slate-400">
            Votre argent, en toute clarté.
        </p>
    </div>
</template>
