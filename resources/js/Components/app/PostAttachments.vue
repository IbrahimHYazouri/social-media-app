<script setup lang="ts">
import {PaperClipIcon} from "@heroicons/vue/24/solid/index.js";
import {computed} from "vue";
import {Attachment} from "@/types/attachment";

const props = defineProps<{
    attachments: Attachment[]
}>()

const attachmentList = computed(() => Object.values(props.attachments))
</script>

<template>
    <div v-for="(attachment, index) of attachmentList.slice(0, 4)">
        <div class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer">
            <div v-if="index === 3 && attachmentList.length > 4" class="absolute left-0 top-0 right-0 bottom-0 z-10 bg-black/60 text-white flex items-center justify-center text-2xl">
                +{{ attachmentList.length - 4 }} more
            </div>

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
