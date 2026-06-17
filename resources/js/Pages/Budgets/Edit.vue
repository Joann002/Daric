<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';

const props = defineProps({
    budget: Object,
    expenseCategories: Array,
});

const form = useForm({
    category_id: props.budget.category_id,
    month: props.budget.month,
    limit_amount: props.budget.limit_amount,
});

const submit = () => form.put(route('budgets.update', props.budget.id));
</script>

<template>
    <FormPage
        title="Modifier le budget"
        :back-href="route('budgets.index')"
        back-label="Retour aux budgets"
    >
        <Head title="Modifier le budget" />

        <form @submit.prevent="submit" class="space-y-5">
            <FormField
                label="Catégorie de dépense"
                required
                :error="form.errors.category_id"
            >
                <select v-model="form.category_id" class="input" required>
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
                    Mettre à jour
                </button>
            </div>
        </form>
    </FormPage>
</template>
