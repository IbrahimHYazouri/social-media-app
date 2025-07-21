<script setup lang="ts">
import {ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import GroupItem from "@/Components/app/GroupItem.vue";

const searchKeyword = ref('')
</script>

<template>
    <div class="flex gap-2 mt-4">
        <TextInput :model-value="searchKeyword" placeholder="Type to search" class="w-full"/>
        <button
                class="text-sm bg-indigo-500 hover:bg-indigo-600 text-white rounded py-1 px-2 w-[120px]">
            new group
        </button>
    </div>
    <div class="mt-3 h-[200px] lg:flex-1 overflow-auto">
        <div v-if="false" class="text-gray-400 text-center p-3">
            You are not joined to any groups
        </div>
        <div v-else>
            <GroupItem />
            <GroupItem />
            <GroupItem />
            <GroupItem />
        </div>
    </div>
</template>
