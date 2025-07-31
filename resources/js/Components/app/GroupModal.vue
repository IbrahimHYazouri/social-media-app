<script setup lang="ts">
import BaseModal from "@/Components/app/BaseModal.vue";
import {XMarkIcon, BookmarkIcon} from '@heroicons/vue/24/solid'
import TextInput from "@/Components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import axios from "axios";
import InputError from "@/Components/InputError.vue";
import {ref} from "vue";

defineProps<{
    show: boolean
}>()

const emit = defineEmits<{
    (e: 'close'): void
}>()

const form = useForm({
    name: '',
    auto_approval: true,
    about: '',
})

const loading = ref(false);

const submit = () => {
    loading.value = true;
    axios.post(route('groups.store'), form)
        .then((_) => {
            emit('close')
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                form.errors = error.response.data.errors;
            }
        })
        .finally(() => {
            loading.value = false;
        })
}
</script>

<template>
    <BaseModal title="Create new Group" :show="show">
        <div class="p-4 space-y-3 dark:text-gray-100">
            <div>
                <label>Group Name</label>
                <TextInput
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    autofocus
                />

                <InputError class="mt-1" :message="form.errors.name ? form.errors.name[0] : ''"/>
            </div>

            <div>
                <label>
                    <Checkbox name="remember" v-model:checked="form.auto_approval"/>
                    Enable Auto Approval
                </label>
            </div>

            <div>
                <label>About Group</label>
                <textarea
                    v-model="form.about"
                    class="dark:bg-slate-950 dark:text-white py-2 px-3 border-2 border-gray-200 dark:border-slate-900 text-gray-500 rounded-md mb-3 w-full resize-none"></textarea>
            </div>
        </div>

        <div class="flex justify-end gap-2 py-3 px-4">
            <button
                @click="$emit('close')"
                class="text-gray-800 flex gap-1 items-center justify-center bg-gray-100 rounded-md hover:bg-gray-200 py-2 px-4"
            >
                <XMarkIcon class="size-5"/>
                Cancel
            </button>
            <button
                @click="submit"
                :disabled="loading"
                type="button"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-25 disabled:cursor-not-allowed"
            >
                <BookmarkIcon class="size-4 mr-2"/>
                Submit
            </button>
        </div>
    </BaseModal>
</template>

