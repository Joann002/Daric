<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Badge from '@/Components/ui/Badge.vue';
import EmptyState from '@/Components/ui/EmptyState.vue';
import Icon from '@/Components/Icon.vue';

defineProps({
    categories: Array,
});

const deleteCategory = (id) => {
    if (confirm('Supprimer cette catégorie ?')) {
        router.delete(route('categories.destroy', id));
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Catégories" />

        <PageHeader
            title="Catégories"
            subtitle="Organisez vos revenus et dépenses"
        >
            <template #actions>
                <Link :href="route('categories.create')" class="btn-primary">
                    <Icon name="plus" class="h-4 w-4" />
                    Nouvelle catégorie
                </Link>
            </template>
        </PageHeader>

        <div
            v-if="categories.length"
            class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3"
        >
            <Card
                v-for="category in categories"
                :key="category.id"
                class="flex items-center justify-between"
            >
                <div class="flex min-w-0 items-center gap-3">
                    <span
                        class="h-9 w-9 shrink-0 rounded-xl"
                        :style="{ backgroundColor: category.color }"
                    />
                    <div class="min-w-0">
                        <p
                            class="truncate font-semibold text-slate-900 dark:text-white"
                        >
                            {{ category.name }}
                        </p>
                        <div class="mt-1 flex items-center gap-1.5">
                            <Badge
                                :variant="
                                    category.type === 'income'
                                        ? 'success'
                                        : 'danger'
                                "
                            >
                                {{
                                    category.type === 'income'
                                        ? 'Revenu'
                                        : 'Dépense'
                                }}
                            </Badge>
                            <Badge v-if="!category.user_id" variant="neutral">
                                Par défaut
                            </Badge>
                            <span
                                class="text-xs text-slate-400 dark:text-slate-500"
                            >
                                {{ category.transactions_count }} op.
                            </span>
                        </div>
                    </div>
                </div>
                <div v-if="category.user_id" class="flex shrink-0 gap-1">
                    <Link
                        :href="route('categories.edit', category.id)"
                        class="btn-ghost !p-2"
                        aria-label="Modifier"
                    >
                        <Icon name="edit" class="h-4 w-4" />
                    </Link>
                    <button
                        @click="deleteCategory(category.id)"
                        class="btn-ghost !p-2 text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-500/10"
                        aria-label="Supprimer"
                    >
                        <Icon name="trash" class="h-4 w-4" />
                    </button>
                </div>
            </Card>
        </div>

        <Card v-else>
            <EmptyState
                icon="categories"
                title="Aucune catégorie"
                description="Créez des catégories pour classer vos transactions."
            >
                <template #action>
                    <Link :href="route('categories.create')" class="btn-primary">
                        <Icon name="plus" class="h-4 w-4" />
                        Nouvelle catégorie
                    </Link>
                </template>
            </EmptyState>
        </Card>
    </AuthenticatedLayout>
</template>
