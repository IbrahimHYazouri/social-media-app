<script setup lang="ts">
import {useForm, usePage} from "@inertiajs/vue3";
import {BookmarkIcon, PaperClipIcon, XMarkIcon, ArrowUturnLeftIcon} from '@heroicons/vue/24/solid'
import BaseModal from "@/Components/app/BaseModal.vue";
import {Post} from "@/types/post";
import {computed, ref, watch} from "vue";
import {isImage} from "@/helper";
import {Attachment} from "@/types/attachment";

interface AttachmentFile {
    file: File;
    url: string | null
}

const props = defineProps<{
    post?: Post,
    show: boolean
}>()

const emit = defineEmits<{
    (e: 'close'): void
}>()

const authUser = usePage().props.auth.user;

const form = useForm<{
    body: string,
    user_id: number,
    attachments: File[],
    deleted_attachment_ids?: Array<number>,
    _method?: string | null
}>({
    body: props.post?.body || '',
    user_id: props.post?.user_id || authUser.id,
    attachments: [],
    deleted_attachment_ids: [],
    _method: ''
})

const attachments = ref<AttachmentFile[]>([]);
const existingAttachments = computed<Attachment[]>(() => props.post?.attachments || []);

watch(() => props.post, (newPost) => {
    form.body = newPost?.body || ''
    form.user_id = newPost?.user_id || authUser.id
})

const onAttachmentChoose = async (event: Event): Promise<void> => {
    const input = event.target as HTMLInputElement;
    const files = input.files;

    if (!files) return;

    const processedFiles: AttachmentFile[] = await Promise.all(
        Array.from(files).map(async (file) => ({
            file,
            url: await readFile(file),
        }))
    );

    attachments.value.push(...processedFiles);
    input.value = '';
}

const readFile = (file: File): Promise<string | null> => {
    return new Promise((resolve, reject) => {
        if (!isImage(file)) {
            return resolve(null);
        }

        const reader = new FileReader();

        reader.onload = () => resolve(reader.result as string);
        reader.onerror = () => reject(new Error('Failed to read file'));

        reader.readAsDataURL(file);
    })
}

const removeAttachment = (attachment: AttachmentFile): void => {
    attachments.value = attachments.value.filter(a => a !== attachment)
}

const removeExistingAttachment = (attachment: Attachment): void => {
    form.deleted_attachment_ids?.push(attachment.id);
    attachment.deleted = true;
}

const undoRemovingExisingAttachment = (attachment: Attachment): void => {
    form.deleted_attachment_ids = form.deleted_attachment_ids?.filter((id) => id !== attachment.id)
    attachment.deleted = false;
}

const submit = () => {
    form.attachments = attachments.value.map(attachment => attachment.file);
    if (props.post && props.post.id) {
        form._method = 'PUT';
        form.post(route('posts.update', props.post.id), {
            preserveScroll: true,
            onSuccess: () => {
                emit('close')
            },
        })
    } else {
        form.post(route('posts.store'), {
            preserveScroll: true,
            onSuccess: () => {
                emit('close')
            }
        })
    }
}
</script>

<template>
    <BaseModal
        :title="post ? 'Update Post' : 'Create Post'"
        :show="show" @close="$emit('close')"
    >
        <div class="p-4">
            <div>
                <textarea
                    v-model="form.body"
                    class="dark:bg-slate-950 dark:text-white py-2 px-3 border-2 border-gray-200 dark:border-slate-900 text-gray-500 rounded-md mb-3 w-full resize-none"></textarea>
            </div>

            <div class="grid gap-3 my-3"
                 :class="attachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'"
            >
                <div v-for="attachment of attachments">
                    <div
                        class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative border-2"
                    >
                        <button
                            @click="removeAttachment(attachment)"
                            class="absolute z-20 right-3 top-3 w-7 h-7 flex items-center justify-center bg-black/30 text-white rounded-full hover:bg-black/40"
                        >
                            <XMarkIcon class="h-5 w-5"/>
                        </button>

                        <img
                            v-if="isImage(attachment.file)"
                            :src="attachment.url || ''"
                            alt="attachment"
                            class="object-contain aspect-square"
                        />
                        <div v-else class="flex flex-col justify-center items-center px-3">
                            <PaperClipIcon class="w-10 h-10 mb-3"/>

                            <small class="text-center">
                                {{ attachment.file.name }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-3 my-3"
                 :class="existingAttachments.length === 1 ? 'grid-cols-1' : 'grid-cols-2'"
            >
                <div v-for="attachment of existingAttachments">
                    <div
                        class="group aspect-square bg-blue-100 flex flex-col items-center justify-center text-gray-500 relative border-2"
                    >
                        <div v-if="attachment.deleted"
                             class="absolute z-10 left-0 bottom-0 right-0 py-2 px-3 text-sm bg-black text-white flex justify-between items-center">
                            To be deleted

                            <ArrowUturnLeftIcon @click="undoRemovingExisingAttachment(attachment)"
                                                class="w-4 h-4 cursor-pointer"/>
                        </div>
                        <button
                            @click="removeExistingAttachment(attachment)"
                            class="absolute z-20 right-3 top-3 w-7 h-7 flex items-center justify-center bg-black/30 text-white rounded-full hover:bg-black/40"
                        >
                            <XMarkIcon class="h-5 w-5"/>
                        </button>

                        <img
                            v-if="attachment.is_image"
                            :src="attachment.preview_url || ''"
                            alt="attachment"
                            class="object-contain aspect-square"
                            :class="{'opacity-50': attachment.deleted}"
                        />
                        <div v-else class="flex flex-col justify-center items-center px-3"
                        :class="{'opacity-50': attachment.deleted}"
                        >
                            <PaperClipIcon class="w-10 h-10 mb-3"/>

                            <small class="text-center">
                                {{ attachment.name }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-2 py-3 px-4">
            <button
                type="button"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full relative"
            >
                <PaperClipIcon class="w-4 h-4 mr-2"/>
                Attach Files
                <input @change="onAttachmentChoose" type="file" multiple
                       class="absolute left-0 top-0 right-0 bottom-0 opacity-0">
            </button>
            <button
                type="button"
                @click="submit"
                class="flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 w-full"
            >
                <BookmarkIcon class="size-4 mr-2"/>
                Submit
            </button>
        </div>
    </BaseModal>
</template>
