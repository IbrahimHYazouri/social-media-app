<script setup lang="ts">
import {usePage, Link} from "@inertiajs/vue3";
import {Post} from "@/types/post";
import {ref} from "vue";
import axios from "axios";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps<{
    post: Post
}>()

const authUser = usePage().props.auth.user;
const newComment = ref('');
const selectedComment = ref(null);

const create = () => {
    axios.post(route('posts.comments.store', props.post.id), {
        comment: newComment.value
    }).then(({comment}) => {
        newComment.value = '';
        props.post.comments.unshift(comment);
        props.post.num_of_comments++;
    })
}

const edit = (comment: Comment) => {
    selectedComment.value = comment;
}

const update = () => {
    axios.put(route('comments.update', selectedComment.value?.id), selectedComment.value)
        .then(({comment}) => {
            selectedComment.value = null;
            props.post.comments = props.post.comments.map((c) => {
                if (c.id === comment.id) {
                    return comment;
                }
                return c;
            })
        })
}

const destroy = (comment: Comment) => {
    axios.delete(route('comments.destroy', comment.id))
        .then((_) => {
            const index = props.post.comments.findIndex(c => c.id === comment.id);
            props.post.comments.splice(index, 1);
            props.post.num_of_comments--;
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
                v-model="newComment"
                placeholder="Enter your comment here"
                rows="1"
            ></textarea>
            <button
                @click="create"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 relative rounded-l-none w-[100px]">Submit</button>
        </div>
    </div>

    <div v-for="comment of post.comments" class="mb-4">
        <div class="flex justify-between gap-2">
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
            <EditDeleteDropdown
                :comment="comment"
                @update="edit(comment)"
                @delete="destroy(comment)"
            />
        </div>
        <div class="pl-12">
            <div v-if="selectedComment && selectedComment.id === comment.id">
                <textarea
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full max-h-[160px] resize-none rounded-r-none"
                    v-model="selectedComment.comment"
                    placeholder="Enter your comment here"
                    rows="1"
                ></textarea>
                <div class="flex gap-2 justify-end">
                    <SecondaryButton @click="selectedComment = null">Cancel</SecondaryButton>
                    <button
                        @click="update"
                        class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 relative w-[100px]">Update</button>
                </div>
            </div>
            {{comment.comment}}
        </div>
    </div>
</template>
