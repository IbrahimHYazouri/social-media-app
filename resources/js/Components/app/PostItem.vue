<script setup lang="ts">
import {Disclosure, DisclosureButton} from '@headlessui/vue'
import {ChatBubbleLeftRightIcon, HandThumbUpIcon} from '@heroicons/vue/24/outline'
</script>

<template>
    <div class="bg-white border dark:bg-slate-950 dark:border-slate-900 dark:text-gray-100 rounded p-4 mb-3">
        <div class="mb-3">
            Lorem ipsum
        </div>
        <Disclosure v-slot="{ open }">
            <div class="flex gap-2">
                <button
                    class="text-gray-800 dark:text-gray-100 flex gap-1 items-center justify-center  rounded-lg py-2 px-4 flex-1"
                >
                    <HandThumbUpIcon class="w-5 h-5"/>
                    <span class="mr-2">55</span>
                    Like
                </button>
                <DisclosureButton
                    class="text-gray-800 dark:text-gray-100 flex gap-1 items-center justify-center bg-gray-100 dark:bg-slate-900 dark:hover:bg-slate-800 rounded-lg hover:bg-gray-200  py-2 px-4 flex-1"
                >
                    <ChatBubbleLeftRightIcon class="w-5 h-5"/>
                    <span class="mr-2">17</span>
                    Comment
                </DisclosureButton>
            </div>
        </Disclosure>
    </div>
</template>
