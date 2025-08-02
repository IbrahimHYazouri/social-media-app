<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import GroupList from "@/Components/app/GroupList.vue";
import CreatePost from "@/Components/app/CreatePost.vue";
import PostList from "@/Components/app/PostList.vue";
import FollowingList from "@/Components/app/FollowingList.vue";
import {Post} from "@/types/post";
import {Group} from "@/types/group";

const props = defineProps<{
    feed: {
        data: Post[]
    },
    groups: {
        data: Group[]
    }
}>()
</script>

<template>
    <Head title="Feed" />

    <AuthenticatedLayout>
        <div class="grid lg:grid-cols-12 gap-3 p-4 h-full">
            <div class="lg:col-span-3 lg:order-1 h-full overflow-hidden">
                <GroupList :groups="groups.data"/>
            </div>
            <div class="lg:col-span-3 lg:order-3 h-full overflow-hidden">
<!--                <FollowingList />-->
            </div>
            <div class="lg:col-span-6 lg:order-2 h-full overflow-hidden flex flex-col">
                <CreatePost />
                <PostList :posts="feed.data"/>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
