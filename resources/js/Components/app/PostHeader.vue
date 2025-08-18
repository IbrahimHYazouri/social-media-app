<script setup lang="ts">
import {Link} from "@inertiajs/vue3"
import {Post} from "@/types/post";
import {ChevronRightIcon} from "@heroicons/vue/24/solid/index.js";

defineProps<{
    post: Post
}>()

</script>

<template>
    <div class="flex items-center gap-3">
        <Link :href="route('profile.show', post.user?.username)" class="shrink-0">
            <img
                :src="post.user?.avatar_url || '/img/default_avatar.webp'"
                :alt="post.user?.username"
                class="w-10 h-10 rounded-full border border-gray-200 transition-all hover:border-blue-500"
            />
        </Link>

        <div class="flex flex-col">
            <div class="flex items-center gap-1 font-semibold text-gray-900">
                <Link
                    :href="route('profile.show', post.user?.username)"
                    class="hover:underline"
                >
                    {{ post.user?.name }}
                </Link>

                <template v-if="post.group">
                    <ChevronRightIcon class="w-4 h-4 text-gray-400" />
                    <Link
                        :href="route('groups.show', post.group.slug)"
                        class="flex items-center gap-1 hover:underline"
                    >
                        <img
                            :src="post.group.avatar_url || '/img/no_image.png'"
                            alt="group-avatar"
                            class="w-8 h-8 rounded-full border border-gray-200"
                        />
                        <span class="font-bold">{{ post.group.name }}</span>
                    </Link>
                </template>
            </div>

            <small class="text-sm text-gray-500">
                {{ post.updated_at }}
            </small>
        </div>
    </div>
</template>
