<script setup lang="ts">
import {ref} from "vue";
import PostModal from "@/Components/app/PostModal.vue";
import {Group} from "@/types/group";

const showModal = ref(false);

defineProps<{
    group?: Group
}>()
</script>

<template>
    <div class="p-4 bg-white dark:bg-slate-950 rounded-lg border dark:border-slate-900 mb-3">
        <div
            @click="showModal = true"
            class="py-2 px-3 border-2 border-gray-200 dark:border-slate-900 text-gray-500 rounded-md mb-3 w-full">
            Click here to create new post
        </div>

        <PostModal :show="showModal" :group="group" @close="showModal = false" />
    </div>
</template>

<style scoped>

</style>
