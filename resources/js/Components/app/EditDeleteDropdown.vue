<script setup lang="ts">
import {Post} from "@/types/post";
import {usePage} from "@inertiajs/vue3";
import {EllipsisVerticalIcon, PencilIcon, TrashIcon} from "@heroicons/vue/20/solid/index.js";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {computed} from "vue";
import {User} from "@/types";

const props = defineProps<{
    post: Post
}>()

defineEmits<{
    (e: 'update'): void,
    (e: 'delete'): void
}>()

const authUser = usePage().props.auth.user;
const owner = computed<User>(() => props.post?.user);

const editAllowed = computed(() => owner.value.id === authUser.id);
const deleteAllowed = computed(() => owner.value.id === authUser.id);
</script>

<template>
    <Menu as="div" class="relative inline-block text-left">
        <div>
            <MenuButton
                class="size-8 z-10 rounded-full hover:bg-black/5 transition flex items-center justify-center"
            >

                <EllipsisVerticalIcon
                    class="size-5"
                    aria-hidden="true"
                />
            </MenuButton>
        </div>

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <MenuItems
                v-if="authUser"
                class="absolute z-20 right-0 mt-2 w-48 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
            >
                <div class="px-1 py-1">
                    <MenuItem v-if="editAllowed" v-slot="{ active }">
                        <button
                            @click="$emit('update')"
                            :class="[
                          active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                          'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                        ]">
                            <PencilIcon
                                class="mr-2 size-5"
                                aria-hidden="true"
                            />
                            Edit
                        </button>
                    </MenuItem>
                    <MenuItem v-if="deleteAllowed" v-slot="{ active }">
                        <button
                            @click="$emit('delete')"
                            :class="[
                              active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                              'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                        >
                            <TrashIcon
                                class="mr-2 size-5"
                                aria-hidden="true"
                            />
                            Delete
                        </button>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>
