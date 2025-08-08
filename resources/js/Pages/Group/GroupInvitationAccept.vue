<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {ref} from "vue";
import axios from "axios";
import { router } from '@inertiajs/vue3'

const props = defineProps<{
    group: {
        id: string;
        name: string;
    },
    token: string
}>()

const loading = ref(false);
const error = ref('');

const accept = async () => {
    loading.value = true;

    try {
        const response = await axios.post(route('groups.invitations.accept', props.token));

        if (response.data.redirect) {
            router.visit(response.data.redirect)
        }
    } catch(err: any) {
        if (err.response && err.response.data && err.response.data.message) {
            error.value = err.response.data.message;
        } else {
            error.value = 'An unexpected error occurred. Please try again.';
        }
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-4 flex items-center justify-center">
            <div class="flex items-center justify-center size-full">
                <div
                    class="flex flex-col items-center gap-5 w-full sm:max-w-md m-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg space-y-4"
                >
                    <ApplicationLogo class="w-16 h-16 fill-current text-gray-500"/>
                    <div class="flex flex-col justify-center items-center gap-2 text-center">
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">
                            You're invited to join
                        </h2>
                        <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                            " {{ group.name }} "
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Click the button below to accept the invitation and become a member of this group.
                        </p>
                    </div>
                    <SecondaryButton
                        @click="accept"
                        :disabled="loading"
                        class="disabled:opacity-25 disabled:cursor-not-allowed"
                    >
                        Accept invitation
                    </SecondaryButton>

                    <p v-if="error" class="mb-4 text-red-600">{{ error }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
