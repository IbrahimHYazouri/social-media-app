<script setup lang="ts">
import {User} from "@/types";
import {Head} from "@inertiajs/vue3";
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Components/TabItem.vue";

defineProps<{
    user: User
}>()
</script>

<template>
    <Head title="Profile"/>

    <AuthenticatedLayout>
        <div class="max-w-[768px] mx-auto h-full overflow-auto">
            <div class="px-4">
                <div class="group relative bg-white dark:bg-slate-950 dark:text-gray-100">
                    <img :src="user.cover_path || '/img/default_cover.jpg'"
                         :alt="user.username"
                         class="w-full h-[200px] object-cover">
                    <div class="flex">
                        <div class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full">
                            <img :src="user.avatar_path || '/img/default_avatar.webp'"
                                 :alt="user.username"
                                 class="w-full h-full object-cover rounded-full">
                        </div>
                    </div>
                    <div class="flex justify-between items-center flex-1 p-4">
                        <div>
                            <h2 class="font-bold text-lg">{{ user.name }}</h2>
                            <p class="text-xs text-gray-500">50 follower(s)</p>
                        </div>
                    </div>
                </div>
                <div class="border-t mt-0">
                    <TabGroup>
                        <TabList  class="flex bg-white dark:bg-slate-950 dark:text-white">
                            <Tab v-slot="{ selected }" as="template">
                                <TabItem text="Posts" :selected="selected"/>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <TabItem text="Followers" :selected="selected"/>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <TabItem text="Followings" :selected="selected"/>
                            </Tab>
                            <Tab v-slot="{ selected }" as="template">
                                <TabItem text="Photos" :selected="selected"/>
                            </Tab>
                        </TabList>
                        <TabPanels class="mt-2">
                            <TabPanel>
                                <div>
                                    Posts
                                </div>
                            </TabPanel>
                            <TabPanel>
                                <div>
                                    Followers
                                </div>
                            </TabPanel>
                            <TabPanel>
                                <div>
                                    Following
                                </div>
                            </TabPanel>
                            <TabPanel>
                                <div>
                                    Photos
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </TabGroup>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

