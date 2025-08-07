<script setup lang="ts">
import {Head} from "@inertiajs/vue3"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Notification} from "@/types/notification";
import {BellIcon} from '@heroicons/vue/24/outline';

defineProps<{
    notifications: {
        data: Notification[]
    }
}>()
</script>

<template>
    <Head title="Notifications"/>
    <AuthenticatedLayout>
        <div class="p-4">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-xl font-semibold">All Notifications</h1>
                <button
                    class="text-sm text-blue-600 hover:underline"
                >
                    Mark all as read
                </button>
            </div>

            <div class="space-y-4">
                <div
                    v-for="notification in notifications.data"
                    class="flex items-start p-4 rounded-md border shadow-sm"
                    :class="notification.read_at ? 'bg-white' : 'bg-blue-50 border-blue-300'"
                >
                    <BellIcon class="size-6 text-blue-500 mr-4 mt-1"/>

                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900 hover:underline">
                            {{notification.data.message}}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{notification.created_at}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
