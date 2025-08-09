<script setup lang="ts">
import SecondaryButton from "@/Components/SecondaryButton.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {ref} from "vue";
import axios from "axios";
import {router, Link} from '@inertiajs/vue3'

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

const getInitials = (name: string) => {
    return name
        .split(' ')
        .map(word => word.charAt(0).toUpperCase())
        .join('')
        .substring(0, 2)
}

const accept = async () => {
    loading.value = true;

    try {
        const response = await axios.post(route('groups.invitations.accept', props.token));

        if (response.data.redirect) {
            router.visit(response.data.redirect)
        }
    } catch (err: any) {
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
    <div
        class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-900 dark:to-gray-800 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-8 pt-8 pb-6 text-center">
                    <div class="flex justify-center mb-6">
                        <Link
                            :href="route('dashboard')"
                            class="p-3 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl">
                            <ApplicationLogo class="size-12 text-blue-600 dark:text-blue-400"/>
                        </Link>
                    </div>

                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        You're Invited!
                    </h1>

                    <div class="mb-6">
                        <div class="flex items-center justify-center mb-1">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-green-400 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                {{ getInitials(invitee.name) }}
                            </div>
                        </div>
                        <Link
                            :href="route('profile.show', invitee.username)"
                            class="text-gray-600 dark:text-gray-300 text-sm mb-1">
                            <span class="font-semibold text-gray-900 dark:text-white">{{ invitee.name }}</span>
                        </Link>
                        <p class="text-gray-600 dark:text-gray-300 text-sm">
                            invited you to join
                        </p>
                    </div>

                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-4 mb-6">
                            <h2 class="text-xl font-semibold text-blue-900 dark:text-blue-100">
                                {{ group.name }}
                            </h2>
                        </div>

                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                            Accept this invitation to become a member and start collaborating with your team.
                        </p>
                </div>

                <div class="px-8 pb-3">
                    <div v-if="error"
                         class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400 text-center">
                            {{ error }}
                        </p>
                    </div>

                    <SecondaryButton
                        @click="accept"
                        :disabled="loading"
                        class="w-full flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span>
                            {{ loading ? 'Processing...' : 'Accept Invitation' }}
                        </span>
                    </SecondaryButton>
                </div>
            </div>

            <div class="text-center mt-6">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    This invitation link is secure and can only be used once.
                </p>
            </div>
        </div>
    </div>
</template>
