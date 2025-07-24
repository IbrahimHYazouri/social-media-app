<script setup lang="ts">
import PostItem from "@/Components/app/PostItem.vue";
import {Post} from "@/types/post";

defineProps<{
    posts: Post[]
}>()
</script>

<template>
    <div class="overflow-auto">
        <PostItem v-for="post in posts" :post="post" />
    </div>
</template>
