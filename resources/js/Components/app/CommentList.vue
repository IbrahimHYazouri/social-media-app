<script setup lang="ts">
import {usePage, Link} from "@inertiajs/vue3";
import {Post} from "@/types/post";
import {ref} from "vue";
import axios from "axios";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {HandThumbUpIcon, ChatBubbleLeftEllipsisIcon} from "@heroicons/vue/24/outline/index.js";
import {Disclosure, DisclosureButton, DisclosurePanel} from "@headlessui/vue";
import {Comment} from "@/types/comment";


const props = defineProps<{
    post: Post,
    data: {
      comments: Comment[]
    },
    parent ?: Comment
}>()

const authUser = usePage().props.auth.user;
const newComment = ref('');
const selectedComment = ref(null);

const create = () => {
    if (props.parent && props.parent.depth >= 1) {
        return;
    }

    axios.post(route('posts.comments.store', props.post.id), {
        comment: newComment.value,
        parent_id: props.parent?.id || null
    }).then((response) => {
        newComment.value = '';
        props.data.comments.unshift(response.data);
        props.post.num_of_comments++;

        if (props.parent) {
            props.parent.num_of_replies++;
        }
    })
}

const edit = (comment: Comment) => {
    selectedComment.value = comment;
}

const update = () => {
    axios.put(route('comments.update', selectedComment.value?.id), selectedComment.value)
        .then((response) => {
                selectedComment.value = null;
                props.data.comments = props.data.comments.map((c) => {
                    if (c.id === response.data.id) {
                        return response.data;
                    }
                    return c;
                })
            })
}

const destroy = (comment: Comment) => {
    axios.delete(route('comments.destroy', comment.id))
        .then((_) => {
            const index = props.data.comments.findIndex(c => c.id === comment.id);
            props.data.comments.splice(index, 1);
            props.post.num_of_comments--;
        })
}

const toggleReaction = (comment: Comment) => {
    axios.post(route('comments.reactions', comment.id), {
        type: 'like'
    }).then(({data}) => {
        comment.num_of_reactions = data.num_of_reactions;
        comment.user_has_reacted = data.user_has_reacted;
    }).catch(error => {
        console.error('Reaction failed', error)
    })
}
</script>

<template>
    <div v-if="authUser && (!parent || parent.depth < 1)" class="flex gap-2 mb-3">
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

    <div v-for="comment of data.comments" class="mb-4">
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
            <p>{{comment.comment}}</p>
            <Disclosure>
                <div class="mt-1 flex gap-2">
                    <button
                        @click="toggleReaction(comment)"
                        class="flex items-center text-xs text-indigo-500 py-0.5 px-1  rounded-lg"
                        :class="[
                        comment.user_has_reacted ?
                         'bg-indigo-50 hover:bg-indigo-100' :
                         'hover:bg-indigo-50'
                    ]">
                        <HandThumbUpIcon class="size-3 mr-1"/>
                        <span class="mr-2">{{comment.num_of_reactions}}</span>
                        Like
                    </button>
                    <DisclosureButton
                        v-if="comment.depth < 2"
                        class="flex items-center text-xs text-indigo-500 py-0.5 px-1 hover:bg-indigo-100 rounded-lg">
                        <ChatBubbleLeftEllipsisIcon class="size-3 mr-1"/>
                        <span class="mr-2">{{ comment.num_of_replies }}</span>
                        Replies
                    </DisclosureButton>
                </div>
                <DisclosurePanel class="mt-3">
                    <CommentList
                        :post="post"
                        :data="{comments: comment.replies}"
                        :parent="comment"
                    />
                </DisclosurePanel>
            </Disclosure>
        </div>
    </div>
</template>
