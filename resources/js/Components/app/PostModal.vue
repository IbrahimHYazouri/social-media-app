<script setup lang="ts">
import {useForm, usePage} from "@inertiajs/vue3";
import {BookmarkIcon} from '@heroicons/vue/24/solid'
import BaseModal from "@/Components/app/BaseModal.vue";
import {Post} from "@/types/post";
import {watch} from "vue";

const props = defineProps<{
    post?: Post,
    show: boolean
}>()

const emit = defineEmits<{
    (e: 'close'): void
}>()

const authUser = usePage().props.auth.user;

const form = useForm({
    body: props.post?.body || '',
    user_id: props.post?.user_id || authUser.id
})

watch(() => props.post, (newPost) => {
    form.body = newPost?.body || ''
    form.user_id = newPost?.user_id || authUser.id
})

const submit = () => {
    if (props.post && props.post.id) {
        form.put(route('posts.update', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                emit('close')
            },
        })
    } else {
        form.post(route('posts.store'), {
            preserveScroll: true,
            onSuccess: () => {
                emit('close')
            }
        })
    }
}
</script>

<template>
    <BaseModal
        :title="post ? 'Update Post' : 'Create Post'"
        :show="show" @close="$emit('close')"
    >
        <div class="p-4">
            <div>
                <textarea
                    v-model="form.body"
                    class="dark:bg-slate-950 dark:text-white py-2 px-3 border-2 border-gray-200 dark:border-slate-900 text-gray-500 rounded-md mb-3 w-full resize-none"></textarea>
            </div>
        </div>

        <div class="flex gap-2 py-3 px-4">
            <button
                type="button"
                @click="submit"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full"
            >
                <BookmarkIcon class="size-4 mr-2"/>
                Submit
            </button>
        </div>
    </BaseModal>
</template>
