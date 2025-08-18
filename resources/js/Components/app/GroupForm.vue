<script setup lang="ts">
import {Group} from "@/types/group";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps<{
    group: Group
}>()

const form = useForm<{
    id: number;
    name: string;
    auto_approval: boolean;
    about?: string;
}>({
    id: props.group.id,
    name: props.group.name,
    auto_approval: props.group.auto_approval,
    about: props.group.about,
});

const submit = () => {
    form.put(route('groups.update', props.group.slug))
}
</script>

<template>
    <form class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800 space-y-4" @submit.prevent="submit">
        <div class="">
            <label>Group Name</label>
            <TextInput
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
            />
        </div>

        <div class="mb-3 dark:text-gray-100">
            <label>
                <Checkbox v-model:checked="form.auto_approval" />
            </label>
            Enable Auto Approval
        </div>

        <div class="mb-3 dark:text-gray-100">
            <label>About Group</label>
            <textarea
                v-model="form.about"
                class="dark:bg-slate-950 dark:text-white py-2 px-3 border-2 border-gray-200 dark:border-slate-900 text-gray-500 rounded-md mb-3 w-full resize-none"></textarea>
        </div>

        <PrimaryButton>Submit</PrimaryButton>
    </form>
</template>
