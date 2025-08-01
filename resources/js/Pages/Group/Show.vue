<script setup lang="ts">
import {Group} from "@/types/group";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {XMarkIcon, CheckCircleIcon, CameraIcon} from '@heroicons/vue/24/solid'
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Tab, TabGroup, TabList, TabPanel, TabPanels} from "@headlessui/vue";
import TabItem from "@/Components/TabItem.vue";
import {ref} from "vue";
import {Head, useForm} from "@inertiajs/vue3";
import InviteUserToGroupModal from "@/Components/app/InviteUserToGroupModal.vue";

const props = defineProps<{
    group: {
        data: Group
    }
}>()

const form = useForm<{
    avatar_path: File | null;
    cover_path: File | null;
    _method: string
}>({
    avatar_path: null,
    cover_path: null,
    _method: 'PATCH'
});

const coverImageSrc = ref<string>('');
const avatarImageSrc = ref<string>('');

const showInviteUserModal = ref(false);

const onCoverImageChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.cover_path = target.files[0];

        const reader = new FileReader();
        reader.onload = () => {
            coverImageSrc.value = reader.result as string;
        };
        reader.readAsDataURL(form.cover_path);
    }
}

const onAvatarImageChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.avatar_path = target.files[0];

        const reader = new FileReader();
        reader.onload = () => {
            avatarImageSrc.value = reader.result as string;
        };
        reader.readAsDataURL(form.avatar_path);
    }
}

const reset = () => {
    form.cover_path = null;
    form.cover_path = null;
    coverImageSrc.value = '';
    avatarImageSrc.value = '';
}

const submit = () => {
    form.post(route('groups.images.update', props.group.data.slug), {
        onSuccess: () => {
            reset();
        }
    })
}
</script>

<template>
    <Head title="Group Profile"/>

    <AuthenticatedLayout>
        <div class="max-w-[1100px] mx-auto h-full overflow-auto">
            <div class="px-4">
                <div class="group relative bg-white dark:bg-slate-950 dark:text-gray-100">
                    <img
                        :src="group.data.cover_url || coverImageSrc || '/img/default_cover.jpg'"
                        alt="group-cover"
                        class="w-full h-[200px] object-cover"
                    />
                    <div v-if="group.data.can.manage" class="absolute top-2 right-2">
                        <button
                            v-if="!coverImageSrc"
                            class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center opacity-0 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-3 h-3 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                            </svg>
                            Update Cover Image
                            <input
                                @change="onCoverImageChange"
                                type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"/>
                        </button>
                        <div v-else class="flex gap-2 bg-white p-2 opacity-0 group-hover:opacity-100">
                            <button
                                @click="coverImageSrc = ''"
                                class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center">
                                <XMarkIcon class="h-3 w-3 mr-2"/>
                                Cancel
                            </button>
                            <button
                                @click="submit"
                                class="bg-gray-800 hover:bg-gray-900 text-gray-100 py-1 px-2 text-xs flex items-center">
                                <CheckCircleIcon class="h-3 w-3 mr-2"/>
                                Submit
                            </button>
                        </div>
                    </div>

                    <div class="flex">
                        <div  class="flex items-center justify-center relative group/thumbnail -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full">
                            <img :src="group.data.avatar_url || avatarImageSrc || '/img/no_image.png'"
                                 alt="avatar-image"
                                 class="w-full h-full object-cover rounded-full">
                            <button
                                v-if="group.data.can.manage && !avatarImageSrc"
                                class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/thumbnail:opacity-100">
                                <CameraIcon class="w-8 h-8"/>

                                <input @change="onAvatarImageChange" type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"/>
                            </button>

                            <div v-else-if="group.data.can.manage" class="absolute top-1 right-0 flex flex-col gap-2">
                                <button
                                    @click="avatarImageSrc = ''"
                                    class="w-7 h-7 flex items-center justify-center bg-red-500/80 text-white rounded-full">
                                    <XMarkIcon class="h-5 w-5"/>
                                </button>
                                <button
                                    @click="submit"
                                    class="w-7 h-7 flex items-center justify-center bg-emerald-500/80 text-white rounded-full">
                                    <CheckCircleIcon class="h-5 w-5"/>
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-between items-center flex-1 p-4">
                            <h2 class="font-bold text-lg">{{ group.data.name }}</h2>
                            <PrimaryButton
                                v-if="group.data.can.manage"
                                @click="showInviteUserModal = true"
                            >
                                Invite Users
                            </PrimaryButton>

                            <PrimaryButton v-else-if="group.data.can.join">
                                {{ group.data.auto_approval ? 'Join' : 'Request to join' }}
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t m-4 mt-0">
                <TabGroup>
                    <TabList class="flex bg-white dark:bg-slate-950 dark:text-white">
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem :selected="selected" text="Posts" />
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Users" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Pending Requests" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="Photos" :selected="selected"/>
                        </Tab>
                        <Tab v-slot="{ selected }" as="template">
                            <TabItem text="About" :selected="selected"/>
                        </Tab>
                    </TabList>

                    <TabPanels class="mt-2">
                        <TabPanel>
                            Posts
                        </TabPanel>
                        <TabPanel>
                            Joined users
                        </TabPanel>
                        <TabPanel>
                            Pending Users
                        </TabPanel>
                        <TabPanel>
                            Photos
                        </TabPanel>
                        <TabPanel>
                            Update
                        </TabPanel>
                    </TabPanels>
                </TabGroup>
            </div>
        </div>
    </AuthenticatedLayout>

    <InviteUserToGroupModal
        :group="group.data"
        :show="showInviteUserModal"
        @close="showInviteUserModal = false"
    />
</template>
