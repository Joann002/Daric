<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profil" />

    <AuthenticatedLayout>
        <PageHeader
            title="Profil"
            subtitle="Gérez votre compte et votre sécurité"
        />

        <div class="mx-auto max-w-2xl space-y-6">
            <Card>
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </Card>

            <Card>
                <UpdatePasswordForm />
            </Card>

            <Card>
                <DeleteUserForm />
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
