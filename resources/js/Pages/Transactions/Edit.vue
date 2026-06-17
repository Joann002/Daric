<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    transaction: Object,
    accounts: Array,
    categories: Array,
});

const form = useForm({
    type: props.transaction.type,
    account_id: props.transaction.account_id,
    category_id: props.transaction.category_id,
    amount: props.transaction.amount,
    date: props.transaction.date?.split('T')[0] ?? props.transaction.date,
    description: props.transaction.description ?? '',
});

const filteredCategories = computed(() =>
    form.type ? props.categories.filter((c) => c.type === form.type) : [],
);

const onTypeChange = () => {
    form.category_id = '';
};

const submit = () => form.put(route('transactions.update', props.transaction.id));
</script>

<template>
    <FormPage
        title="Modifier la transaction"
        :back-href="route('transactions.index')"
        back-label="Retour aux transactions"
    >
        <Head title="Modifier la transaction" />

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
                    Mettre à jour
                </button>
            </div>
        </form>
    </FormPage>
</template>
