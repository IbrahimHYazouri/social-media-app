<script setup lang="ts">
import {User} from "@/types";
import {Head, useForm, usePage} from "@inertiajs/vue3";
import {TabGroup, TabList, Tab, TabPanels, TabPanel} from '@headlessui/vue'
import {XMarkIcon, CheckCircleIcon, CameraIcon} from '@heroicons/vue/24/solid'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TabItem from "@/Components/TabItem.vue";
import {computed, ref} from "vue";
import Edit from "@/Pages/Profile/Edit.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CreatePost from "@/Components/app/CreatePost.vue";
import PostList from "@/Components/app/PostList.vue";
import AttachmentTab from "@/Components/app/AttachmentTab.vue";

const props = defineProps<{
    user: User
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

const authUser = usePage().props.auth.user;

const isMyProfile = computed(() => authUser && authUser.id === props.user.id);

const coverImageSrc = ref<string>('');
const avatarImageSrc = ref<string>('');

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
    form.post(route('profile.images.update', props.user.id), {
        onSuccess: () => {
            reset()
        }
    })
}

const follow = () => {
    const form = useForm({});

    form.post(route('follows.store', props.user.id))
}

const unfollow = () => {
    const form = useForm({});

    form.delete(route('follows.destroy', props.user.id))
}
</script>

<template>
    <Head title="Profile"/>

    <AuthenticatedLayout>
        <div class="max-w-[768px] lg:max-w-[1100px] mx-auto h-full overflow-auto">
            <div class="px-4">
                <div class="group relative bg-white dark:bg-slate-950 dark:text-gray-100">
                    <img :src="coverImageSrc || user.cover_url || '/img/default_cover.jpg'"
                         :alt="user.username"
                         class="w-full h-[200px] object-cover">
                    <div v-if="isMyProfile" class="absolute top-2 right-2">
                        <button v-if="!coverImageSrc" class="bg-gray-50 hover:bg-gray-100 text-gray-800 py-1 px-2 text-xs flex items-center opacity-0 group-hover:opacity-100">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-3 h-3 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                            </svg>

                            Update Cover Image
                            <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                   @change="onCoverImageChange"/>
                        </button>
                        <div v-else class="flex gap-2 bg-white p-2 opacity-0 group-hover:opacity-100">
                            <button
                                @click="reset"
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
                        <div class="flex items-center justify-center relative group/avatar -mt-[64px] ml-[48px] w-[128px] h-[128px] rounded-full">
                            <img :src="avatarImageSrc || user.avatar_url || '/img/default_avatar.webp'"
                                 :alt="user.username"
                                 class="w-full h-full object-cover rounded-full">
                            <div v-if="isMyProfile">
                                <button
                                    v-if="!avatarImageSrc"
                                    class="absolute left-0 top-0 right-0 bottom-0 bg-black/50 text-gray-200 rounded-full opacity-0 flex items-center justify-center group-hover/avatar:opacity-100">
                                    <CameraIcon class="w-8 h-8"/>

                                    <input type="file" class="absolute left-0 top-0 bottom-0 right-0 opacity-0"
                                           @change="onAvatarImageChange"/>
                                </button>
                                <div v-else class="absolute top-1 right-0 flex flex-col gap-2">
                                    <button
                                        @click="reset"
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
                        </div>
                    </div>
                    <div class="flex justify-between items-center flex-1 p-4">
                        <div>
                            <h2 class="font-bold text-lg">{{ user.name }}</h2>
                            <p class="text-xs text-gray-500">{{user.followers_count}} follower(s)</p>
                        </div>

                        <div v-if="!isMyProfile">
                            <PrimaryButton v-if="!user.is_following" @click="follow">
                                Follow
                            </PrimaryButton>
                            <PrimaryButton v-if="user.is_following" @click="unfollow">
                                Unfollow
                            </PrimaryButton>
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
                            <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
                                <TabItem text="My Profile" :selected="selected"/>
                            </Tab>
                        </TabList>
                        <TabPanels class="mt-2">
                            <TabPanel>
                                <CreatePost />
                                <PostList
                                    v-if="user.posts"
                                    :posts="user.posts"
                                />
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
                                <AttachmentTab v-if="user.attachments" :attachments="user.attachments" />
                            </TabPanel>
                            <TabPanel>
                                <Edit />
                            </TabPanel>
                        </TabPanels>
                    </TabGroup>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

