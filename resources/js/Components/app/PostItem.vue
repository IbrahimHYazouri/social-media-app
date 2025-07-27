<script setup lang="ts">
import {Disclosure, DisclosureButton} from '@headlessui/vue'
import {ChatBubbleLeftRightIcon, HandThumbUpIcon} from '@heroicons/vue/24/outline'
import {Post} from "@/types/post";
import PostHeader from "@/Components/app/PostHeader.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/app/PostAttachments.vue";
import {ref} from "vue";
import axios from "axios";

const props = defineProps<{
    post: Post
}>()

const emit = defineEmits<{
    (e: 'update', post: Post): void
    (e: 'destroy', post: Post): void
    (e: 'previewAttachments', post: Post, index: number): void
}>()

const loading = ref(false)

const openAttachmentPreview = (index: number) => {
    emit('previewAttachments', props.post, index)
}

const toggleReaction = () => {
    loading.value = true;

    axios.post(route('posts.reactions', props.post.id), {
        type: 'like'
    }).then((response) => {
        const { num_of_reactions, current_user_has_reaction } = response.data;

        props.post.num_of_reactions = num_of_reactions;
        props.post.user_has_reaction = current_user_has_reaction;
    }).catch(error => {
        console.error('Reaction failed', error)
    }).finally(() => {
        loading.value = false;
    })
}
</script>

<template>
    <div class="bg-white border dark:bg-slate-950 dark:border-slate-900 dark:text-gray-100 rounded p-4 mb-3">
        <div class="flex items-center justify-between mb-3">
            <PostHeader :post="post"/>
            <div class="flex items-center">
                <EditDeleteDropdown
                    :post="post"
                    @update="$emit('update', post)"
                    @delete="$emit('destroy', post)"
                />
            </div>
        </div>
        <div class="mb-3">
            {{ post.body }}
        </div>
        <div class="grid gap-3 mb-3"
             :class="[
                post.attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'
            ]"
        >
            <PostAttachments :attachments="post.attachments" @preview="openAttachmentPreview"/>
        </div>
        <Disclosure>
            <div class="flex gap-2">
                <button
                    @click="toggleReaction"
                    class="text-gray-800 dark:text-gray-100 flex gap-1 items-center justify-center  rounded-lg py-2 px-4 flex-1"
                    :class="[
                    post.user_has_reaction ?
                     'bg-sky-100 dark:bg-sky-900 hover:bg-sky-200 dark:hover:bg-sky-950' :
                     'bg-gray-100 dark:bg-slate-900 dark:hover:bg-slate-800']
                     "
                >
                    <HandThumbUpIcon class="size-5"/>
                    <span class="mr-2">{{ post.num_of_reactions }}</span>
                    Like
                </button>
                <DisclosureButton
                    class="text-gray-800 dark:text-gray-100 flex gap-1 items-center justify-center bg-gray-100 dark:bg-slate-900 dark:hover:bg-slate-800 rounded-lg hover:bg-gray-200  py-2 px-4 flex-1"
                >
                    <ChatBubbleLeftRightIcon class="w-5 h-5"/>
                    <span class="mr-2">17</span>
                    Comment
                </DisclosureButton>
            </div>
        </Disclosure>
    </div>
</template>
