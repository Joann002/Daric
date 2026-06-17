<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    recurringTransaction: Object,
    accounts: Array,
    categories: Array,
});

const rt = props.recurringTransaction;

const form = useForm({
    account_id: rt.account_id,
    category_id: rt.category_id,
    type: rt.type,
    amount: rt.amount,
    frequency: rt.frequency,
    next_date: rt.next_date ? String(rt.next_date).split('T')[0] : '',
    description: rt.description ?? '',
    is_active: Boolean(rt.is_active),
});

const filteredCategories = computed(() =>
    form.type ? props.categories.filter((c) => c.type === form.type) : [],
);

const onTypeChange = () => {
    form.category_id = '';
};

const submit = () =>
    form.put(route('recurring-transactions.update', rt.id));
</script>

<template>
    <FormPage
        title="Modifier la récurrence"
        :back-href="route('recurring-transactions.index')"
        back-label="Retour aux récurrences"
    >
        <Head title="Modifier la récurrence" />

        <form @submit.prevent="submit" class="space-y-5">
            <FormField label="Type" required :error="form.errors.type">
                <select
                    v-model="form.type"
                    @change="onTypeChange"
                    class="input"
                    required
                >
                    <option value="income">Revenu</option>
                    <option value="expense">Dépense</option>
                </select>
            </FormField>

            <FormField label="Compte" required :error="form.errors.account_id">
                <select v-model="form.account_id" class="input" required>
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
            >
                <select v-model="form.category_id" class="input" required>
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

            <FormField label="Fréquence" required :error="form.errors.frequency">
                <select v-model="form.frequency" class="input" required>
                    <option value="daily">Quotidien</option>
                    <option value="weekly">Hebdomadaire</option>
                    <option value="monthly">Mensuel</option>
                    <option value="yearly">Annuel</option>
                </select>
            </FormField>

            <FormField
                label="Prochaine échéance"
                required
                :error="form.errors.next_date"
            >
                <input
                    v-model="form.next_date"
                    type="date"
                    class="input"
                    required
                />
            </FormField>

            <FormField label="Description" :error="form.errors.description">
                <textarea
                    v-model="form.description"
                    rows="3"
                    class="input"
                ></textarea>
            </FormField>

            <label class="flex items-center gap-2">
                <input
                    type="checkbox"
                    v-model="form.is_active"
                    class="rounded border-slate-300 text-brand-600 focus:ring-brand-500 dark:border-slate-600 dark:bg-slate-800"
                />
                <span class="text-sm text-slate-700 dark:text-slate-300">
                    Activer cette récurrence
                </span>
            </label>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link
                    :href="route('recurring-transactions.index')"
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
