<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Icon from '@/Components/Icon.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

const features = [
    {
        icon: 'wallet',
        title: 'Comptes multiples',
        text: 'Espèces, banque et mobile money réunis avec un solde toujours à jour.',
    },
    {
        icon: 'transactions',
        title: 'Suivi des transactions',
        text: 'Enregistrez revenus et dépenses, filtrez et exportez en un clic.',
    },
    {
        icon: 'budgets',
        title: 'Budgets intelligents',
        text: 'Fixez des limites par catégorie et suivez votre progression en temps réel.',
    },
    {
        icon: 'recurring',
        title: 'Transactions récurrentes',
        text: 'Automatisez vos opérations régulières et ne ratez plus une échéance.',
    },
    {
        icon: 'debts',
        title: 'Dettes & prêts',
        text: 'Gardez le compte de ce que vous devez et de ce qu’on vous doit.',
    },
    {
        icon: 'scale',
        title: 'Tableau de bord clair',
        text: 'Graphiques et statistiques pour comprendre vos finances d’un coup d’œil.',
    },
];
</script>

<template>
    <Head title="Daric — Gestionnaire de finances personnelles" />

    <div class="relative min-h-dvh bg-slate-50 dark:bg-slate-950">
        <!-- Background glow -->
        <div
            class="pointer-events-none absolute inset-x-0 top-0 -z-10 h-[480px] overflow-hidden"
            aria-hidden="true"
        >
            <div
                class="absolute left-1/2 top-[-180px] h-[420px] w-[700px] -translate-x-1/2 rounded-full bg-brand-400/20 blur-3xl dark:bg-brand-500/10"
            />
        </div>

        <!-- Nav -->
        <header
            class="mx-auto flex max-w-6xl items-center justify-between px-6 py-5"
        >
            <div class="flex items-center gap-2.5">
                <span
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand-600 text-white"
                >
                    <ApplicationLogo class="h-5 w-5" />
                </span>
                <span class="text-xl font-bold text-slate-900 dark:text-white">
                    Daric
                </span>
            </div>
            <nav v-if="canLogin" class="flex items-center gap-2">
                <Link :href="route('login')" class="btn-ghost">Connexion</Link>
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="btn-primary"
                >
                    Commencer
                </Link>
            </nav>
        </header>

        <!-- Hero -->
        <section class="mx-auto max-w-4xl px-6 pb-16 pt-12 text-center sm:pt-20">
            <span
                class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700 ring-1 ring-inset ring-brand-600/20 dark:bg-brand-500/10 dark:text-brand-400"
            >
                <Icon name="sparkles" class="h-4 w-4" />
                Pensé pour Madagascar · Ariary & Mobile Money
            </span>
            <h1
                class="mt-6 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white sm:text-5xl"
            >
                Reprenez le contrôle de
                <span class="text-brand-600 dark:text-brand-400">
                    votre argent
                </span>
            </h1>
            <p
                class="mx-auto mt-5 max-w-2xl text-lg text-slate-600 dark:text-slate-400"
            >
                Daric réunit vos comptes, transactions, budgets et dettes dans
                une interface simple et élégante. Suivez chaque franc, sans
                effort.
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="btn-primary px-6 py-3 text-base"
                >
                    Créer un compte gratuit
                </Link>
                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="btn-secondary px-6 py-3 text-base"
                >
                    J'ai déjà un compte
                </Link>
            </div>
        </section>

        <!-- Features -->
        <section class="mx-auto max-w-6xl px-6 pb-24">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="card p-6 transition hover:shadow-card-hover"
                >
                    <span
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400"
                    >
                        <Icon :name="feature.icon" class="h-6 w-6" />
                    </span>
                    <h3
                        class="mt-4 text-base font-semibold text-slate-900 dark:text-white"
                    >
                        {{ feature.title }}
                    </h3>
                    <p class="mt-1.5 text-sm text-slate-600 dark:text-slate-400">
                        {{ feature.text }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer
            class="border-t border-slate-200/70 py-8 text-center text-sm text-slate-400 dark:border-slate-800"
        >
            © {{ new Date().getFullYear() }} Daric · Votre argent, en toute
            clarté.
        </footer>
    </div>
</template>
