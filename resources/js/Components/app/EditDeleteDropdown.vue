<script setup lang="ts">
import {Post} from "@/types/post";
import {EllipsisVerticalIcon, PencilIcon, TrashIcon} from "@heroicons/vue/20/solid/index.js";
import {MapPinIcon} from "@heroicons/vue/24/outline";
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {computed} from "vue";
import {Comment} from "@/types/comment";

const props = defineProps<{
    post?: Post,
    comment ?: Comment
}>()

defineEmits<{
    (e: 'update'): void,
    (e: 'delete'): void,
    (e: 'pin'): void
}>()

const canUpdate = computed(() => props.post?.can.update ?? props.comment?.can.update);
const canDelete = computed(() => props.post?.can.delete ?? props.comment?.can.delete);
const canPin = computed(() => props.post?.can.pin ?? props.post?.can.pinToGroup)
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
                class="absolute z-20 right-0 mt-2 w-48 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none"
            >
                <div class="px-1 py-1">
                    <MenuItem v-if="canUpdate" v-slot="{ active }">
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
                    <MenuItem v-if="canDelete" v-slot="{ active }">
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
                    <MenuItem v-if="canPin" v-slot="{ active }">
                        <button
                            @click="$emit('pin')"
                            :class="[
                              active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                              'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                            ]"
                        >
                            <MapPinIcon class="mr-2 size-5"/>
                            {{ post.isPinned ? 'Unpin' : 'Pin' }}
                        </button>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>
</template>
