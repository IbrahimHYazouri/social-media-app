<script setup lang="ts">
import {Attachment} from "@/types/attachment";
import {PaperClipIcon} from "@heroicons/vue/24/solid/index.js";

defineProps<{
    attachments: Attachment[]
}>();
</script>

<template>
    <div class="grid grid-cols-2 ms:grid-cols-3 gap-2">
        <div v-for="attachment in attachments"
             class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative cursor-pointer">
            <img
                v-if="attachment.is_image"
                :src="attachment.url"
                alt="photo"
                class="object-contain aspect-square"
            />
            <div v-else class="flex flex-col justify-center items-center">
                <PaperClipIcon class="w-10 h-10 mb-3"/>

                <small>{{ attachment.name }}</small>
            </div>
        </div>
    </div>
</template>
