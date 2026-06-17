<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';
import Icon from '@/Components/Icon.vue';

const props = defineProps({
    account: Object,
});

const form = useForm({
    name: props.account.name,
    type: props.account.type,
    currency: props.account.currency,
});

const submit = () => form.put(route('accounts.update', props.account.id));
</script>

<template>
    <FormPage
        title="Modifier le compte"
        :back-href="route('accounts.show', account.id)"
        back-label="Retour au compte"
    >
        <Head title="Modifier le compte" />

        <form @submit.prevent="submit" class="space-y-5">
            <FormField label="Nom du compte" required :error="form.errors.name">
                <input v-model="form.name" type="text" class="input" required />
            </FormField>

            <FormField label="Type de compte" required :error="form.errors.type">
                <select v-model="form.type" class="input" required>
                    <option value="cash">Espèces</option>
                    <option value="banque">Banque</option>
                    <option value="mobile_money">Mobile Money</option>
                </select>
            </FormField>

            <FormField label="Devise" required :error="form.errors.currency">
                <select v-model="form.currency" class="input" required>
                    <option value="XAF">XAF — Franc CFA</option>
                    <option value="EUR">EUR — Euro</option>
                    <option value="USD">USD — Dollar US</option>
                    <option value="GBP">GBP — Livre Sterling</option>
                </select>
            </FormField>

            <div
                class="flex items-start gap-2.5 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-800 dark:bg-amber-500/10 dark:text-amber-300"
            >
                <Icon name="scale" class="mt-0.5 h-5 w-5 shrink-0" />
                <p>
                    Le solde ne se modifie pas ici : utilisez les transactions
                    pour l'ajuster.
                </p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link
                    :href="route('accounts.show', account.id)"
                    class="btn-secondary"
                >
                    Annuler
                </Link>
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="form.processing"
                >
                    Mettre à jour
                </button>
            </div>
        </form>
    </FormPage>
</template>
