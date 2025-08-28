<script setup lang="ts">
import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import {ChatBubbleLeftRightIcon, HandThumbUpIcon, MapPinIcon} from '@heroicons/vue/24/outline'
import {Post} from "@/types/post";
import PostHeader from "@/Components/app/PostHeader.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/app/PostAttachments.vue";
import {ref} from "vue";
import axios from "axios";
import CommentList from "@/Components/app/CommentList.vue";
import {useForm} from "@inertiajs/vue3";

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
    }).then(({data}) => {
        props.post.num_of_reactions = data.num_of_reactions;
        props.post.user_has_reacted = data.user_has_reacted;
    }).catch(error => {
        console.error('Reaction failed', error)
    }).finally(() => {
        loading.value = false;
    })
}

const pinUnpinPost = () => {
    const form = useForm({
        'scope': props.post.group ? 'group' : 'user'
    });

    form.post(route('posts.pin', props.post.id))
}
</script>

<template>
    <div class="bg-white border dark:bg-slate-950 dark:border-slate-900 dark:text-gray-100 rounded p-4 mb-3">
        <div class="flex items-center justify-between mb-3">
            <PostHeader :post="post"/>
            <div class="flex items-center">
                <div v-if="post.isPinned" class="flex items-center text-xs gap-1">
                    <MapPinIcon
                        class="size-3"
                    />
                    Pinned
                </div>
                <EditDeleteDropdown
                    :post="post"
                    @update="$emit('update', post)"
                    @delete="$emit('destroy', post)"
                    @pin="pinUnpinPost(post)"
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
                    post.user_has_reacted ?
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
                    <span class="mr-2">{{post.num_of_comments}}</span>
                    Comment
                </DisclosureButton>
            </div>

            <DisclosurePanel class="mt-5 max-h-[400px] overflow-auto">
                <CommentList
                    :post="post"
                    :data="{comments: post.comments}"
                />
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>
