<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';

defineProps({
    expenseCategories: Array,
});

const form = useForm({
    category_id: '',
    month: new Date().toISOString().slice(0, 7),
    limit_amount: '',
});

const submit = () => form.post(route('budgets.store'));
</script>

<template>
    <FormPage
        title="Nouveau budget"
        :back-href="route('budgets.index')"
        back-label="Retour aux budgets"
    >
        <Head title="Nouveau budget" />

        <form @submit.prevent="submit" class="space-y-5">
            <FormField
                label="Catégorie de dépense"
                required
                :error="form.errors.category_id"
            >
                <select v-model="form.category_id" class="input" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <option
                        v-for="category in expenseCategories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
                </select>
            </FormField>

            <FormField label="Mois" required :error="form.errors.month">
                <input v-model="form.month" type="month" class="input" required />
            </FormField>

            <FormField
                label="Montant limite"
                required
                :error="form.errors.limit_amount"
                hint="Plafond de dépenses pour cette catégorie ce mois-ci. Alerte au-delà de 80 %."
            >
                <input
                    v-model="form.limit_amount"
                    type="number"
                    step="0.01"
                    class="input"
                    required
                />
            </FormField>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link :href="route('budgets.index')" class="btn-secondary">
                    Annuler
                </Link>
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="form.processing"
                >
                    Créer le budget
                </button>
            </div>
        </form>
    </FormPage>
</template>
