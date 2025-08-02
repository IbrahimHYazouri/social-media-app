<script setup lang="ts">
import {Link} from "@inertiajs/vue3"
import {User} from "@/types";

defineProps<{
    user: User,
    showApprovalActions: boolean
}>()

defineEmits<{
    (e: 'approve', user: User): void,
    (e: 'reject', user: User): void
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
            </div>
        </div>
    </div>
</template>
