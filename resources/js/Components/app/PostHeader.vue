<script setup lang="ts">
import {Link} from "@inertiajs/vue3"
import {Post} from "@/types/post";

defineProps<{
    post: Post
}>()

</script>

<template>
    <div class="flex items-center gap-2 ">
        <Link :href="route('profile.show', post.user?.username)">
            <img :src="post.user?.avatar_url || '/img/default_avatar.webp'"
                 :alt="post.user?.username"
                 class="w-[40px] rounded-full border-2 transition-all hover:border-blue-500"/>
        </Link>
        <div>
            <h4 class="flex items-center font-bold">
                <Link :href="route('profile.show', post.user?.username)" class="hover:underline">
                    {{ post.user?.name }}
                </Link>
            </h4>
            <small class="text-gray-400">{{ post.updated_at }}</small>
        </div>
    </div>
</template>
