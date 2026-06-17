<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Icon from '@/Components/Icon.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { useDarkMode } from '@/composables/useDarkMode';

const { isDark, toggle } = useDarkMode();
const sidebarOpen = ref(false);
const page = usePage();

const navigation = [
    { name: 'Tableau de bord', route: 'dashboard', icon: 'dashboard', pattern: 'dashboard' },
    { name: 'Comptes', route: 'accounts.index', icon: 'wallet', pattern: 'accounts.*' },
    { name: 'Transactions', route: 'transactions.index', icon: 'transactions', pattern: 'transactions.*' },
    { name: 'Transferts', route: 'transfers.index', icon: 'transfers', pattern: 'transfers.*' },
    { name: 'Budgets', route: 'budgets.index', icon: 'budgets', pattern: 'budgets.*' },
    { name: 'Récurrences', route: 'recurring-transactions.index', icon: 'recurring', pattern: 'recurring-transactions.*' },
    { name: 'Dettes', route: 'debts.index', icon: 'debts', pattern: 'debts.*' },
    { name: 'Catégories', route: 'categories.index', icon: 'categories', pattern: 'categories.*' },
];

function isActive(pattern) {
    return route().current(pattern);
}

function initials(name) {
    return (name || '?')
        .split(' ')
        .map((p) => p[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();
}
</script>

<template>
    <div class="min-h-dvh">
        <!-- Desktop sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-40 hidden w-64 flex-col bg-slate-900 lg:flex"
        >
            <div class="flex h-16 items-center gap-2.5 px-6">
                <ApplicationLogo class="h-8 w-8 text-brand-400" />
                <span class="text-lg font-bold text-white">Daric</span>
            </div>
            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <Link
                    v-for="item in navigation"
                    :key="item.route"
                    :href="route(item.route)"
                    class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
                    :class="
                        isActive(item.pattern)
                            ? 'bg-brand-600 text-white shadow-sm'
                            : 'text-slate-400 hover:bg-slate-800 hover:text-white'
                    "
                >
                    <Icon :name="item.icon" class="h-5 w-5" />
                    {{ item.name }}
                </Link>
            </nav>
            <div class="border-t border-slate-800 p-3">
                <div class="flex items-center gap-3 rounded-xl px-3 py-2">
                    <span
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-500/20 text-sm font-semibold text-brand-300"
                    >
                        {{ initials(page.props.auth.user.name) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">
                            {{ page.props.auth.user.name }}
                        </p>
                        <p class="truncate text-xs text-slate-400">
                            {{ page.props.auth.user.email }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile drawer -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="sidebarOpen" class="fixed inset-0 z-50 lg:hidden">
                <div
                    class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
                    @click="sidebarOpen = false"
                />
                <aside
                    class="absolute inset-y-0 left-0 flex w-72 flex-col bg-slate-900"
                >
                    <div class="flex h-16 items-center justify-between px-6">
                        <span class="flex items-center gap-2.5">
                            <ApplicationLogo class="h-8 w-8 text-brand-400" />
                            <span class="text-lg font-bold text-white">Daric</span>
                        </span>
                        <button
                            class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-800 hover:text-white"
                            @click="sidebarOpen = false"
                            aria-label="Fermer le menu"
                        >
                            <Icon name="close" />
                        </button>
                    </div>
                    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                        <Link
                            v-for="item in navigation"
                            :key="item.route"
                            :href="route(item.route)"
                            @click="sidebarOpen = false"
                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition"
                            :class="
                                isActive(item.pattern)
                                    ? 'bg-brand-600 text-white'
                                    : 'text-slate-400 hover:bg-slate-800 hover:text-white'
                            "
                        >
                            <Icon :name="item.icon" class="h-5 w-5" />
                            {{ item.name }}
                        </Link>
                    </nav>
                </aside>
            </div>
        </transition>

        <!-- Main column -->
        <div class="lg:pl-64">
            <header
                class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-slate-200/70 bg-white/80 px-4 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80 sm:px-6 lg:px-8"
            >
                <button
                    class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden dark:text-slate-400 dark:hover:bg-slate-800"
                    @click="sidebarOpen = true"
                    aria-label="Ouvrir le menu"
                >
                    <Icon name="menu" />
                </button>

                <div class="min-w-0 flex-1">
                    <slot name="header" />
                </div>

                <button
                    class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800"
                    @click="toggle"
                    :aria-label="
                        isDark ? 'Activer le mode clair' : 'Activer le mode sombre'
                    "
                >
                    <Icon :name="isDark ? 'sun' : 'moon'" />
                </button>

                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button
                            class="flex items-center gap-2 rounded-full p-0.5 pr-2 transition hover:bg-slate-100 dark:hover:bg-slate-800"
                        >
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700 dark:bg-brand-500/20 dark:text-brand-300"
                            >
                                {{ initials(page.props.auth.user.name) }}
                            </span>
                            <Icon
                                name="chevronDown"
                                class="h-4 w-4 text-slate-400"
                            />
                        </button>
                    </template>
                    <template #content>
                        <div
                            class="border-b border-slate-100 px-4 py-2 dark:border-slate-700"
                        >
                            <p
                                class="truncate text-sm font-medium text-slate-900 dark:text-white"
                            >
                                {{ page.props.auth.user.name }}
                            </p>
                            <p
                                class="truncate text-xs text-slate-500 dark:text-slate-400"
                            >
                                {{ page.props.auth.user.email }}
                            </p>
                        </div>
                        <DropdownLink :href="route('profile.edit')">
                            Profil
                        </DropdownLink>
                        <DropdownLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Déconnexion
                        </DropdownLink>
                    </template>
                </Dropdown>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
</template>
