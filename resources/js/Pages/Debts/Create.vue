<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';

const form = useForm({
    person_name: '',
    amount: '',
    direction: 'je_dois',
    status: 'pending',
    paid_amount: 0,
    due_date: '',
    description: '',
});

const submit = () => form.post(route('debts.store'));
</script>

<template>
    <FormPage
        title="Nouvel enregistrement"
        :back-href="route('debts.index')"
        back-label="Retour aux dettes & prêts"
    >
        <Head title="Nouvelle dette / prêt" />

        <form @submit.prevent="submit" class="space-y-5">
            <FormField
                label="Personne"
                required
                :error="form.errors.person_name"
            >
                <input
                    v-model="form.person_name"
                    type="text"
                    class="input"
                    required
                />
            </FormField>

            <FormField label="Sens" required :error="form.errors.direction">
                <select v-model="form.direction" class="input" required>
                    <option value="je_dois">Je dois</option>
                    <option value="me_doit">On me doit</option>
                </select>
            </FormField>

            <FormField label="Montant" required :error="form.errors.amount">
                <input
                    v-model="form.amount"
                    type="number"
                    step="0.01"
                    class="input"
                    required
                />
            </FormField>

            <FormField
                label="Déjà remboursé"
                :error="form.errors.paid_amount"
            >
                <input
                    v-model="form.paid_amount"
                    type="number"
                    step="0.01"
                    class="input"
                />
            </FormField>

            <FormField label="Statut" :error="form.errors.status">
                <select v-model="form.status" class="input">
                    <option value="pending">En attente</option>
                    <option value="partially_paid">Partiellement réglé</option>
                    <option value="paid">Réglé</option>
                </select>
            </FormField>

            <FormField label="Échéance" :error="form.errors.due_date">
                <input v-model="form.due_date" type="date" class="input" />
            </FormField>

            <FormField label="Description" :error="form.errors.description">
                <textarea
                    v-model="form.description"
                    rows="3"
                    class="input"
                ></textarea>
            </FormField>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link :href="route('debts.index')" class="btn-secondary">
                    Annuler
                </Link>
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="form.processing"
                >
                    Enregistrer
                </button>
            </div>
        </form>
    </FormPage>
</template>
