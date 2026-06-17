<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormPage from '@/Components/ui/FormPage.vue';
import FormField from '@/Components/ui/FormField.vue';
import Icon from '@/Components/Icon.vue';
import { formatCurrency } from '@/composables/useFormat';

const props = defineProps({
    accounts: Array,
});

const form = useForm({
    from_account_id: '',
    to_account_id: '',
    amount: '',
    date: new Date().toISOString().split('T')[0],
    description: '',
});

const isValid = computed(
    () =>
        form.from_account_id &&
        form.to_account_id &&
        form.from_account_id !== form.to_account_id &&
        form.amount > 0,
);

const submit = () => form.post(route('transfers.store'));
</script>

<template>
    <FormPage
        title="Nouveau transfert"
        :back-href="route('transfers.index')"
        back-label="Retour aux transferts"
    >
        <Head title="Nouveau transfert" />

        <form @submit.prevent="submit" class="space-y-5">
            <FormField
                label="Compte source"
                required
                :error="form.errors.from_account_id"
            >
                <select v-model="form.from_account_id" class="input" required>
                    <option value="">Depuis quel compte ?</option>
                    <option
                        v-for="account in accounts"
                        :key="account.id"
                        :value="account.id"
                    >
                        {{ account.name }} ({{ formatCurrency(account.balance) }})
                    </option>
                </select>
            </FormField>

            <div class="flex justify-center">
                <span
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400"
                >
                    <Icon name="incoming" class="rotate-180" />
                </span>
            </div>

            <FormField
                label="Compte destination"
                required
                :error="form.errors.to_account_id"
            >
                <select v-model="form.to_account_id" class="input" required>
                    <option value="">Vers quel compte ?</option>
                    <option
                        v-for="account in accounts"
                        :key="account.id"
                        :value="account.id"
                        :disabled="account.id == form.from_account_id"
                    >
                        {{ account.name }} ({{ formatCurrency(account.balance) }})
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
                    placeholder="Ex : Rechargement mobile money"
                ></textarea>
            </FormField>

            <div class="flex items-center justify-end gap-3 pt-2">
                <Link :href="route('transfers.index')" class="btn-secondary">
                    Annuler
                </Link>
                <button
                    type="submit"
                    class="btn-primary"
                    :disabled="form.processing || !isValid"
                >
                    Effectuer le transfert
                </button>
            </div>
        </form>
    </FormPage>
</template>
