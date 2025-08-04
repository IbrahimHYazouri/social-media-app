<script setup lang="ts">
import {Link} from "@inertiajs/vue3"
import {User} from "@/types";

defineProps<{
    user: User,
    showApprovalActions: boolean,
    showRoleChangeActions: boolean
}>()

defineEmits<{
    (e: 'approve', user: User): void,
    (e: 'reject', user: User): void,
    (e: 'changeRole', user: User, role: 'admin' | 'user'): void,
    (e: 'removeUser', user: User): void
}>()
</script>

<template>
    <div class="bg-white dark:bg-slate-900 dark:text-gray-100 transition-all border-2 border-transparent ">
        <div class="flex items-center gap-2 py-2 px-2">
            <Link :href="route('profile.show', user.username)">
                <img
                    :src="user.avatar_url || '/img/default_avatar.webp'"
                    alt="avatar"
                    class="size-[32px] rounded-full object-cover"
                />
            </Link>
            <div class="flex justify-between flex-1">
                <Link :href="route('profile.show', user.username)">
                    <h3 class="font-black hover:underline">{{ user.name }}</h3>
                </Link>
                <div v-if="showApprovalActions" class="flex gap-1">
                    <button class="text-xs py-1 px-2 rounded bg-emerald-500 hover:bg-emerald-600 text-white"
                            @click="$emit('approve', user)">
                        Approve
                    </button>
                    <button class="text-xs py-1 px-2 rounded bg-red-500 hover:bg-red-600 text-white"
                            @click="$emit('reject', user)">
                        Reject
                    </button>
                </div>
                <div v-if="showRoleChangeActions">
                    <select
                        @change="$emit('changeRole', user, $event.target.value)"
                        class="rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 max-w-xs text-sm leading-6">
                        <option :selected="user.role === 'admin'" value="admin">Admin</option>
                        <option :selected="user.role === 'user'" value="user">User</option>
                    </select>
                    <button
                        @click="$emit('removeUser', user)"
                        class="text-xs py-1.5 px-2 rounded bg-gray-700 hover:bg-gray-800 text-white ml-3 disabled:bg-gray-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
