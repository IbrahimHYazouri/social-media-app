<script setup lang="ts">
import {ref} from "vue";
import TextInput from "@/Components/TextInput.vue";
import UserListItem from "@/Components/app/UserListItem.vue";

const searchKeyword = ref('')
</script>

<template>
    <TextInput :model-value="searchKeyword" placeholder="Type to search" class="w-full mt-3"/>
    <div class="mt-3 h-[200px] lg:flex-1 overflow-auto">
        <div v-if="false" class="text-gray-400 text-center p-3">
            You don't have friends yet.
        </div>
        <div v-else>
            <UserListItem />
            <UserListItem />
            <UserListItem />
            <UserListItem />
        </div>
    </div>
</template>

