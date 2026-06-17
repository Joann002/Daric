<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';

const form = useForm({
    name: '',
    type: 'expense',
    color: '#10b981',
    icon: '',
});

const submit = () => form.post(route('categories.store'));
</script>

<template>
    <FormPage
        title="Nouvelle catégorie"
        :back-href="route('categories.index')"
        back-label="Retour aux catégories"
    >
        <Head title="Nouvelle catégorie" />

        <form @submit.prevent="submit" class="space-y-5">
            <FormField label="Nom" required :error="form.errors.name">
                <input v-model="form.name" type="text" class="input" required />
            </FormField>

            <FormField label="Type" required :error="form.errors.type">
                <select v-model="form.type" class="input" required>
                    <option value="expense">Dépense</option>
                    <option value="income">Revenu</option>
                </select>
            </FormField>

            <FormField label="Couleur" required :error="form.errors.color">
                <div class="flex items-center gap-3">
                    <input
                        v-model="form.color"
                        type="color"
                        class="h-10 w-14 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 dark:border-slate-700 dark:bg-slate-800"
                    />
                    <input
                        v-model="form.color"
                        type="text"
                        class="input tnum w-32"
                    />
                </div>
            </FormField>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link :href="route('categories.index')" class="btn-secondary">
                    Annuler
                </Link>
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="form.processing"
                >
                    Créer la catégorie
                </button>
            </div>
        </form>
    </FormPage>
</template>
