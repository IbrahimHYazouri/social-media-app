<script setup lang="ts">
import { Head, Link, useForm, router } from "@inertiajs/vue3"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Notification } from "@/types/notification";
import { BellIcon, CheckIcon } from '@heroicons/vue/24/outline';

defineProps<{
    notifications: {
        data: Notification[]
    }
}>()

const handleNotificationClick = (notification: Notification) => {
    markAsRead(notification.id)
    router.visit(getNotificationHref(notification))
}

const markAsRead = (id: string) => {
    const form = useForm({});

    form.post(route('notifications.read', id))
}

const markAllAsRead = () => {
    const form = useForm({});
    form.post(route('notifications.mark-all-read'))
}

const getNotificationHref = (notification: Notification) => {
    if (notification.data.target_route && notification.data.target_params) {
        return route(notification.data.target_route, notification.data.target_params);
    }

    return '#';
}
</script>

<template>
    <Head title="Notifications"/>
    <AuthenticatedLayout>
        <div class="bg-gray-50">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <BellIcon class="w-6 h-6 text-blue-600"/>
                                </div>
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ notifications.data.length }} total notifications
                                    </p>
                                </div>
                            </div>
                            <button
                                @click="markAllAsRead"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200"
                            >
                                <CheckIcon class="w-4 h-4"/>
                                Mark all as read
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    <Link
                        :href="getNotificationHref(notification)"
                        @click.prevent="handleNotificationClick(notification)"
                        v-for="notification in notifications.data"
                        :key="notification.id"
                        class="bg-white rounded-lg shadow-sm border transition-all duration-200 hover:shadow-md"
                        :class="notification.read_at
                            ? 'border-gray-200'
                            : 'border-l-4 border-l-blue-500 border-t-gray-200 border-r-gray-200 border-b-gray-200 bg-blue-50/30'"
                    >
                        <div class="p-6">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center"
                                        :class="notification.read_at
                                            ? 'bg-gray-100'
                                            : 'bg-blue-100'"
                                    >
                                        <BellIcon
                                            class="w-5 h-5"
                                            :class="notification.read_at
                                                ? 'text-gray-500'
                                                : 'text-blue-600'"
                                        />
                                    </div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <p
                                                class="text-sm leading-relaxed"
                                                :class="notification.read_at
                                                    ? 'text-gray-700'
                                                    : 'text-gray-900 font-medium'"
                                            >
                                                {{ notification.data.message }}
                                            </p>
                                            <div class="flex items-center gap-3 mt-3">
                                                <time class="text-xs text-gray-500">
                                                    {{ notification.created_at }}
                                                </time>
                                                <span
                                                    v-if="!notification.read_at"
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                                >
                                                    New
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            v-if="!notification.read_at"
                                            class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Link>

                    <div
                        v-if="notifications.data.length === 0"
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center"
                    >
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <BellIcon class="w-8 h-8 text-gray-400"/>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
                        <p class="text-gray-500">You're all caught up! Check back later for new notifications.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
