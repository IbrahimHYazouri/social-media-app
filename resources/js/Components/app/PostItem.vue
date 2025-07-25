<script setup lang="ts">
import {Disclosure, DisclosureButton} from '@headlessui/vue'
import {ChatBubbleLeftRightIcon, HandThumbUpIcon} from '@heroicons/vue/24/outline'
import {Post} from "@/types/post";
import PostHeader from "@/Components/app/PostHeader.vue";
import EditDeleteDropdown from "@/Components/app/EditDeleteDropdown.vue";
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import PostModal from "@/Components/app/PostModal.vue";
import PostAttachments from "@/Components/app/PostAttachments.vue";

const props = defineProps<{
    post: Post
}>()

const showUpdatePostModal = ref(false);
const confirmPostDeletion = ref(false);
const form = useForm({});

const destroy = () => {
    form.delete(route('posts.destroy', props.post.id), {
        onSuccess: () => {
            confirmPostDeletion.value = false
        },
        preserveScroll: true
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
                    @update="showUpdatePostModal = true"
                    @delete="confirmPostDeletion = true"
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
        <Disclosure v-slot="{ open }">
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

    <PostModal :post="post" :show="showUpdatePostModal" @close="showUpdatePostModal = false"/>
    <Modal :show="confirmPostDeletion" @close="confirmPostDeletion = false">
        <div class="p-6">
            <h2
                class="text-lg font-medium text-gray-900 dark:text-gray-100"
            >
                Are you sure you want to delete this post?
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Once your post is deleted, all of its resources and data
                will be permanently deleted.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="confirmPostDeletion = false">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="destroy"
                >
                    Delete post
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
