<script setup lang="ts">
import {useForm} from "@inertiajs/vue3";
import BaseModal from "@/Components/app/BaseModal.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import {XMarkIcon, BookmarkIcon} from '@heroicons/vue/24/solid'
import {Group} from "@/types/group";

const props = defineProps<{
    show: boolean,
    group: Group
}>()

const emit = defineEmits<{
    (e: 'close'): void
}>()

const form = useForm({
    email: ''
})

const reset = () => {
    form.email = ''
    emit('close')
}

const submit = () => {
    form.post(route('groups.invite', props.group.slug), {
        onSuccess: () => {
            reset()
        }
    })
}
</script>

<template>
    <BaseModal title="Invite Users" :show="show" @close="reset">
        <form @submit.prevent="submit" class="p-4 dark:text-gray-100">
            <div class="mb-3">
                <label>Username or email</label>
                <TextInput
                    v-model="form.email"
                    type="text"
                    class="my-1 block w-full"
                    autofocus
                />

                <InputError :message="form.errors.email"/>
            </div>

            <div class="flex justify-end gap-2 py-3 px-4">
                <button
                    @click="reset"
                    type="button"
                    class="text-gray-800 flex items-center  justify-center bg-gray-100 rounded-md hover:bg-gray-200 py-2 px-4"
                >
                    <XMarkIcon class="size-5"/>
                    Cancel
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-25 disabled:cursor-not-allowed"
                >
                    <BookmarkIcon class="size-4 mr-2"/>
                    Submit
                </button>
            </div>
        </form>
    </BaseModal>
</template>
