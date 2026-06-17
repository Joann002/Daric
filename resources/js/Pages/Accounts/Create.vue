<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';

const form = useForm({
    name: '',
    type: '',
    balance: 0,
    currency: 'MGA',
});

const submit = () => form.post(route('accounts.store'));
</script>

<template>
    <FormPage
        title="Nouveau compte"
        :back-href="route('accounts.index')"
        back-label="Retour aux comptes"
    >
        <Head title="Nouveau compte" />

        <form @submit.prevent="submit" class="space-y-5">
            <FormField label="Nom du compte" required :error="form.errors.name">
                <input v-model="form.name" type="text" class="input" required />
            </FormField>

            <FormField label="Type de compte" required :error="form.errors.type">
                <select v-model="form.type" class="input" required>
                    <option value="">Sélectionnez un type</option>
                    <option value="cash">Espèces</option>
                    <option value="banque">Banque</option>
                    <option value="mobile_money">Mobile Money</option>
                </select>
            </FormField>

            <FormField
                label="Solde initial"
                required
                :error="form.errors.balance"
            >
                <input
                    v-model="form.balance"
                    type="number"
                    step="0.01"
                    class="input"
                    required
                />
            </FormField>

            <FormField label="Devise" required :error="form.errors.currency">
                <select v-model="form.currency" class="input" required>
                    <option value="MGA">MGA — Ariary</option>
                    <option value="EUR">EUR — Euro</option>
                    <option value="USD">USD — Dollar US</option>
                </select>
            </FormField>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link :href="route('accounts.index')" class="btn-secondary">
                    Annuler
                </Link>
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="form.processing"
                >
                    Créer le compte
                </button>
            </div>
        </form>
    </FormPage>
</template>
