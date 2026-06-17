<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Vérification de l'e-mail" />

        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">
                Vérifiez votre e-mail
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Merci de votre inscription ! Cliquez sur le lien que nous venons
                de vous envoyer pour confirmer votre adresse.
            </p>
        </div>

        <div
            v-if="verificationLinkSent"
            class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400"
        >
            Un nouveau lien de vérification vient d'être envoyé.
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <PrimaryButton
                class="w-full"
                :class="{ 'opacity-50': form.processing }"
                :disabled="form.processing"
            >
                Renvoyer l'e-mail de vérification
            </PrimaryButton>

            <p class="text-center">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm font-medium text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"
                >
                    Déconnexion
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
