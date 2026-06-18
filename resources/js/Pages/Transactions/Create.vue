<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    accounts: Array,
    categories: Array,
    defaultDate: { type: String, default: null },
});

const form = useForm({
    type: '',
    account_id: '',
    category_id: '',
    amount: '',
    date: props.defaultDate || new Date().toISOString().split('T')[0],
    description: '',
});

const filteredCategories = computed(() =>
    form.type ? props.categories.filter((c) => c.type === form.type) : [],
);

const onTypeChange = () => {
    form.category_id = '';
};

const submit = () => form.post(route('transactions.store'));
</script>

<template>
    <FormPage
        title="Nouvelle transaction"
        :back-href="route('transactions.index')"
        back-label="Retour aux transactions"
    >
        <Head title="Nouvelle transaction" />

        <form @submit.prevent="submit" class="space-y-5">
            <FormField label="Type" required :error="form.errors.type">
                <div class="grid grid-cols-2 gap-3">
                    <label
                        class="flex cursor-pointer items-center justify-center rounded-xl border px-4 py-3 text-sm font-medium transition"
                        :class="
                            form.type === 'income'
                                ? 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400'
                                : 'border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800'
                        "
                    >
                        <input
                            type="radio"
                            value="income"
                            v-model="form.type"
                            @change="onTypeChange"
                            class="sr-only"
                        />
                        Revenu
                    </label>
                    <label
                        class="flex cursor-pointer items-center justify-center rounded-xl border px-4 py-3 text-sm font-medium transition"
                        :class="
                            form.type === 'expense'
                                ? 'border-rose-500 bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400'
                                : 'border-slate-200 text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800'
                        "
                    >
                        <input
                            type="radio"
                            value="expense"
                            v-model="form.type"
                            @change="onTypeChange"
                            class="sr-only"
                        />
                        Dépense
                    </label>
                </div>
            </FormField>

            <FormField label="Compte" required :error="form.errors.account_id">
                <select v-model="form.account_id" class="input" required>
                    <option value="">Sélectionnez un compte</option>
                    <option
                        v-for="account in accounts"
                        :key="account.id"
                        :value="account.id"
                    >
                        {{ account.name }} ({{ formatCurrency(account.balance) }})
                    </option>
                </select>
            </FormField>

            <FormField
                label="Catégorie"
                required
                :error="form.errors.category_id"
                :hint="!form.type ? 'Choisissez d’abord un type' : null"
            >
                <select
                    v-model="form.category_id"
                    class="input"
                    :disabled="!form.type"
                    required
                >
                    <option value="">Sélectionnez une catégorie</option>
                    <option
                        v-for="category in filteredCategories"
                        :key="category.id"
                        :value="category.id"
                    >
                        {{ category.name }}
                    </option>
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

            <FormField label="Date" required :error="form.errors.date">
                <input v-model="form.date" type="date" class="input" required />
            </FormField>

            <FormField label="Description" :error="form.errors.description">
                <textarea
                    v-model="form.description"
                    rows="3"
                    class="input"
                ></textarea>
            </FormField>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link :href="route('transactions.index')" class="btn-secondary">
                    Annuler
                </Link>
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="form.processing"
                >
                    Créer la transaction
                </button>
            </div>
        </form>
    </FormPage>
</template>
