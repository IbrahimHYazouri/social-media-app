<script setup lang="ts">
import {ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import GroupItem from "@/Components/app/GroupItem.vue";
import GroupModal from "@/Components/app/GroupModal.vue";

const searchKeyword = ref('');
const showGroupModal = ref(false);
</script>

<template>
    <div class="flex gap-2 mt-4">
        <TextInput :model-value="searchKeyword" placeholder="Type to search" class="w-full"/>
        <button
            @click="showGroupModal = true"
                class="text-sm bg-indigo-500 hover:bg-indigo-600 text-white rounded py-1 px-2 w-[120px]">
            New group
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

    <GroupModal :show="showGroupModal" @close="showGroupModal = false"/>
</template>
