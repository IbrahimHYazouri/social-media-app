<script setup lang="ts">
import {Group} from "@/types/group";

defineProps<{
    group: Group
}>()
</script>

<template>
    <div class="cursor-pointer hover:bg-gray-100 dark:hover:bg-slate-800">
        <div class="flex items-start gap-1 py-2 px-2">
            <img src="/img/no_image.png" alt="group-avatar" class="w-[32px] rounded-full"/>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h3 class="font-black">{{ group.name }}</h3>
                </div>
                <span class="text-xs">
                    {{ group.status === 'approved' ? (group.role === 'admin' ? group.role : '') : 'not approved' }}
                </span>
            </div>
        </div>
    </div>
</template>
