<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Mot de passe oublié" />

        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">
                Mot de passe oublié ?
            </h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Indiquez votre e-mail et nous vous enverrons un lien de
                réinitialisation.
            </p>
        </div>

        <div
            v-if="status"
            class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <InputLabel for="email" value="Adresse e-mail" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <PrimaryButton
                class="w-full"
                :class="{ 'opacity-50': form.processing }"
                :disabled="form.processing"
            >
                Envoyer le lien
            </PrimaryButton>

            <p class="text-center text-sm text-slate-500 dark:text-slate-400">
                <Link
                    :href="route('login')"
                    class="font-medium text-brand-600 hover:text-brand-700 dark:text-brand-400"
                >
                    Retour à la connexion
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
