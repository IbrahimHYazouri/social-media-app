<script setup lang="ts">
import {PaperClipIcon} from "@heroicons/vue/24/solid/index.js";
import {ArrowDownTrayIcon} from '@heroicons/vue/24/outline'
import {Attachment} from "@/types/attachment";

const props = defineProps<{
    attachments: Attachment[]
}>()

defineEmits<{
    (e: 'preview'): void
}>()
</script>

<template>
    <div v-for="(attachment, index) of attachments.slice(0, 4)">
        <div
            @click="$emit('preview', index)"
            class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer">
            <div v-if="index === 3 && attachments.length > 4" class="absolute left-0 top-0 right-0 bottom-0 z-10 bg-black/60 text-white flex items-center justify-center text-2xl">
                +{{ attachments.length - 4 }} more
            </div>

            <a
                @click.stop
                :href="route('attachments.download', attachment.id)"
               class="z-50 opacity-0 group-hover:opacity-100 transition-all w-8 h-8 flex items-center justify-center text-gray-100 bg-gray-700 rounded absolute right-2 top-2 cursor-pointer hover:bg-gray-800">
                <ArrowDownTrayIcon class="size-4"/>
            </a>

            <img v-if="attachment.is_image"
                 :src="attachment.preview_url"
                 alt="attachment"
                 class="object-contain aspect-square"/>
            <div v-else class="flex flex-col justify-center items-center">
                <PaperClipIcon class="w-10 h-10 mb-3"/>

                <small>{{ attachment.name }}</small>
            </div>
        </div>
    </div>
</template>
