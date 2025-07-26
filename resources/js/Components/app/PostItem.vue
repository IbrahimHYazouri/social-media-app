<script setup lang="ts">
import {Disclosure, DisclosureButton} from '@headlessui/vue'
import {ChatBubbleLeftRightIcon, HandThumbUpIcon} from '@heroicons/vue/24/outline'
import {Post} from "@/types/post";
import PostHeader from "@/Components/app/PostHeader.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import PostAttachments from "@/Components/app/PostAttachments.vue";

defineProps<{
    post: Post
}>()

defineEmits<{
    (e: 'update', post: Post): void
    (e: 'destroy', post: Post): void
}>()
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
            <PostAttachments :attachments="post.attachments" />
        </div>
        <Disclosure>
            <div class="flex gap-2">
                <button
                    class="text-gray-800 dark:text-gray-100 flex gap-1 items-center justify-center  rounded-lg py-2 px-4 flex-1"
                >
                    <HandThumbUpIcon class="w-5 h-5"/>
                    <span class="mr-2">55</span>
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
