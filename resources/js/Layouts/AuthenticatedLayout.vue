<script setup lang="ts">
import {ref} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import TextInput from "@/Components/TextInput.vue";
import { Link, usePage, useForm, router } from '@inertiajs/vue3';
import {MoonIcon} from '@heroicons/vue/24/solid';
import {BellIcon} from '@heroicons/vue/24/outline';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import {Notification} from "@/types/notification";
import { useEcho } from "@laravel/echo-vue";
import axios from "axios";
import {User} from "@/types";
import {Post} from "@/types/post";
import {Group} from "@/types/group";

interface LiveNotification {
    id: string;
    type: string;
    message: string;
    target_route: string;
    target_params: Record<string, string>
}

interface SearchResult {
    search: string;
    users: User[],
    posts: Post[],
    groups: Group[]
}

const showingNavigationDropdown = ref(false);
const authUser = usePage().props.auth.user;
const notifications = usePage().props.auth.notifications
const keywords = ref("");
const searchResult = ref<SearchResult | null>(null);
const isSearching = ref(false)
const showSearchResults = ref(false)

function debounce<T extends (...args: any[]) => void>(
    func: T,
    delay: number
): (...args: Parameters<T>) => void {
    let timeout: ReturnType<typeof setTimeout>;

    return function (this: ThisParameterType<T>, ...args: Parameters<T>) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            func.apply(context, args);
        }, delay);
    };
}


const search = async () => {
    if (!keywords.value.trim()) {
        searchResult.value = null;
        showSearchResults.value = false;
        return;
    }

    isSearching.value = true;

    try {

        const { data } = await axios.get('/search', {
            params: {
                search: keywords.value
            }
        })

        searchResult.value = data;
        showSearchResults.value = true;
    } catch (error) {
        console.error('Search error:', error);
        searchResult.value = null;
    } finally {
        isSearching.value = false;
    }
}

const debounceSearch = debounce(search, 500)

useEcho(
    `App.Models.User.${authUser.id}`,
    '.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated',
    (notification: LiveNotification) => {
        console.log('New notification received:', notification);

        notifications.count += 1;
        notifications.unread.unshift({
            id: notification.id,
            type: notification.type,
            data: {
                message: notification.message,
                target_route: notification.target_route,
                target_params: notification.target_params
            },
        })
    },
    [authUser.id], // dependencies
    'private'
)

const toggleDarkMode = () => {
    const html = window.document.documentElement
    if (html.classList.contains('dark')) {
        html.classList.remove('dark')
        localStorage.setItem('darkMode', '0')
    } else {
        html.classList.add('dark')
        localStorage.setItem('darkMode', '1')
    }
}

const markAsRead = async (id: string) => {
    const form = useForm({});

    return new Promise((resolve, reject) => {
        form.post(route('notifications.read', id), {
            onSuccess: () => {
                notifications.count = Math.max(0, notifications.count - 1);

                const index = notifications.unread.findIndex(n => n.id === id);

                if (index !== -1) {
                    notifications.unread.splice(index, 1);
                }

                resolve(true)
            },
            onError: () => {
                reject(false);
            }
        })
    })
}

const handleNotificationClick = async (notification: Notification) => {
    await markAsRead(notification.id)
    router.visit(getNotificationHref(notification))
}

const getNotificationHref = (notification: Notification) => {
    if (notification.data.target_route && notification.data.target_params) {
        return route(notification.data.target_route, notification.data.target_params);
    }

    return '#';
}
</script>

<template>
    <div class="min-h-screen overflow-hidden flex flex-col bg-gray-100 dark:bg-gray-800">
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-2 h-16">
                    <div class="flex mr-2">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                                />
                            </Link>
                        </div>

                    </div>

                    <div class="flex items-center gap-3 flex-1 relative">
                        <div class="relative w-full">
                            <TextInput v-model="keywords" @keydown="debounceSearch" placeholder="Search on the website" class="w-full"/>

                            <div
                                v-if="isSearching"
                                class="absolute right-3 top-2.5 h-5 w-5 animate-spin rounded-full border-2 border-gray-400 border-t-transparent"
                            ></div>

                            <div v-if="showSearchResults && searchResult"
                                 class="absolute mt-2 w-full rounded-md border border-gray-200 bg-white shadow-lg"
                            >
                                <!-- Users -->
                                <div v-if="searchResult.users.length" class="p-3 border-b border-gray-200">
                                    <h3 class="mb-2 text-sm font-semibold text-gray-600">Users</h3>
                                    <ul>
                                        <li
                                            v-for="user in searchResult.users"
                                            :key="user.id"
                                            class="cursor-pointer px-2 py-1 hover:bg-gray-100"
                                        >
                                            {{ user.name }} <span class="text-gray-500">@{{ user.username }}</span>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Users /-->

                                <!-- Groups -->
                                <div v-if="searchResult.groups.length" class="p-3 border-b border-gray-200">
                                    <h3 class="mb-2 text-sm font-semibold text-gray-600">Groups</h3>
                                    <ul>
                                        <li
                                            v-for="group in searchResult.groups"
                                            :key="group.id"
                                            class="cursor-pointer px-2 py-1 hover:bg-gray-100"
                                        >
                                            {{ group.name }}
                                            <p class="text-xs text-gray-500">{{ group.about }}</p>
                                        </li>
                                    </ul>
                                </div>
                                <!-- Groups /-->

                                <!-- Posts -->
                                <div v-if="searchResult.posts.length" class="p-3">
                                    <h3 class="mb-2 text-sm font-semibold text-gray-600">Posts</h3>
                                    <ul>
                                        <li
                                            v-for="post in searchResult.posts"
                                            :key="post.id"
                                            class="cursor-pointer px-2 py-1 hover:bg-gray-100"
                                        >
                                            {{ post.body?.slice(0, 80) }}...
                                        </li>
                                    </ul>
                                </div>
                                <!-- Posts /-->

                                <!-- Empty state -->
                                <div
                                    v-if="
                                      !searchResult.users.length &&
                                      !searchResult.groups.length &&
                                      !searchResult.posts.length
                                    "
                                    class="p-3 text-center text-gray-500"
                                >
                                    No results found
                                </div>
                            </div>
                        </div>

                        <Popover class="relative">
                            <PopoverButton class="relative p-1 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-full">
                                <BellIcon class="size-6 text-gray-600 dark:text-gray-300"/>
                                <span
                                    v-if="notifications.count"
                                    class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
                                >
                                    {{ notifications.count }}
                                </span>
                            </PopoverButton>

                            <transition
                                enter-active-class="transition duration-200 ease-out"
                                enter-from-class="translate-y-1 opacity-0"
                                enter-to-class="translate-y-0 opacity-100"
                                leave-active-class="transition duration-150 ease-in"
                                leave-from-class="translate-y-0 opacity-100"
                                leave-to-class="translate-y-1 opacity-0"
                            >
                                <PopoverPanel class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50">
                                    <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                                        <span class="font-semibold text-gray-700 dark:text-gray-200">
                                          Notifications
                                        </span>
                                    </div>

                                    <ul class="max-h-64 overflow-auto">
                                        <li v-for="notification in notifications.unread"
                                            class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 flex items-start gap-3"
                                        >
                                            <Link :href="getNotificationHref(notification)"
                                                @click.prevent="handleNotificationClick(notification)"
                                                class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <BellIcon class="size-5 text-indigo-500 flex-shrink-0"/>
                                                <div class="flex-1">
                                                    <p class="text-sm text-gray-800 dark:text-gray-200">
                                                        {{notification.data.message || notification.type}}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        {{ notification.created_at }}
                                                    </p>
                                                </div>
                                            </Link>
                                        </li>
                                        <li v-if="!notifications.unread.length" class="p-3 text-center text-gray-500 dark:text-gray-400">
                                            No new notifications
                                        </li>
                                    </ul>

                                    <div class="p-3 border-t border-gray-100 dark:border-gray-700 text-center">
                                        <Link
                                            :href="route('notifications.index')"
                                            class="text-indigo-600 dark:text-indigo-400 text-sm hover:underline"
                                        >
                                            See all
                                        </Link>
                                    </div>
                                </PopoverPanel>
                            </transition>
                        </Popover>

                        <button @click="toggleDarkMode" class="dark:text-white">
                            <MoonIcon class="w-5 h-5"/>
                        </button>
                    </div>

                    <div class="hidden sm:flex sm:items-center">
                        <!-- Settings Dropdown -->
                        <div class="ms-3 relative">
                            <Dropdown v-if="authUser" align="right" width="48">
                                <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ authUser.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.show', {username: authUser.username })">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                            <div v-else>
                                <Link :href="route('login')" class="dark:text-gray-100">
                                    Login
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            @click="showingNavigationDropdown = !showingNavigationDropdown"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                        >
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path
                                    :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div
                :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                class="sm:hidden"
            >

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    <template v-if="authUser">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ authUser.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ authUser.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show', {username: authUser.username })"> Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </template>
                    <template>
                        Login button
                    </template>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header"/>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 h-full overflow-hidden">
            <slot/>
        </main>
    </div>
</template>
