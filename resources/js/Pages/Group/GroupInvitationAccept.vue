<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {ref} from "vue";
import axios from "axios";
import { router, Link } from '@inertiajs/vue3'

const props = defineProps<{
    group: {
        id: string;
        name: string;
    },
    invitee: {
        id: string;
        name: string;
        username: string;
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
        <div class="flex items-center justify-center p-4 bg-gray-50 dark:bg-gray-900">
            <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 space-y-6 text-center">
                <ApplicationLogo class="mx-auto w-16 h-16 text-gray-500 dark:text-gray-400" />

                <div class="space-y-1">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                        You're invited to join
                    </h2>
                    <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">
                        “{{ group.name }}”
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        by <Link :href="route('profile.show', invitee.username)" class="font-semibold">{{ invitee.name }}</Link> (@{{ invitee.username }})
                    </p>
                </div>

                <div class="space-y-2">
                    <SecondaryButton
                        @click="accept"
                        :disabled="loading"
                        class="disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ loading ? 'Processing...' : 'Accept Invitation' }}
                    </SecondaryButton>

                    <p v-if="error" class="text-sm text-red-600 dark:text-red-400">{{ error }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
