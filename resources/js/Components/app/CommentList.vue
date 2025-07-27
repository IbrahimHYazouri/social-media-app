<script setup lang="ts">
import {usePage, Link} from "@inertiajs/vue3";
import {Post} from "@/types/post";
import {ref} from "vue";
import axios from "axios";

const props = defineProps<{
    post: Post
}>()

const authUser = usePage().props.auth.user;
const comment = ref('');

const createComment = () => {
    axios.post(route('posts.comments.store', props.post.id), {
        comment: comment.value
    }).then(({data}) => {
        comment.value = '';
        props.post.comments.unshift(data);
        props.post.num_of_comments++;
    })
}
</script>

<template>
    <div v-if="authUser" class="flex gap-2 mb-3">
        <Link :href="route('profile.show', authUser.username)">
            <img
                :src="authUser?.avatar_url || '/img/default_avatar.webp'"
                alt="user-avatar"
                class="w-[40px] rounded-full border-2 transition-all hover:border-blue-500"
            />
        </Link>
        <div class="flex flex-1">
            <textarea
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full max-h-[160px] resize-none rounded-r-none"
                v-model="comment"
                placeholder="Enter your comment here"
                rows="1"
            ></textarea>
            <button
                @click="createComment"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 relative rounded-l-none w-[100px]">Submit</button>
        </div>
    </div>

    <div v-for="comment of post.comments" class="mb-4">
        <div class="flex gap-2">
            <Link :href="route('profile.show', comment.user.username)">
                <img
                    :src="comment.user?.avatar_url || '/img/default_avatar.webp'"
                    alt="user-avatar"
                    class="w-[40px] rounded-full border-2 transition-all hover:border-blue-500"
                />
            </Link>
            <div>
                <h4 class="font-bold">
                    <Link :href="route('profile.show', comment.user.username)" class="hover:underline">
                        {{comment.user.name}}
                    </Link>
                </h4>
                <small class="text-xs text-gray-400">{{ comment.updated_at }}</small>
            </div>
        </div>
        <div class="pl-12">
            {{comment.comment}}
        </div>
    </div>
</template>
