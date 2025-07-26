<script setup lang="ts">
import PostItem from "@/Components/app/PostItem.vue";
import {Post} from "@/types/post";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import PostModal from "@/Components/app/PostModal.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

defineProps<{
    posts: Post[]
}>()

const selectedPost = ref<Post|null>(null);
const showEditModal = ref(false);
const showDeleteModal = ref(false);

const openEditModal = (post: Post) => {
    selectedPost.value = post;
    showEditModal.value = true;
}

const openConfirmDeleteModal = (post: Post) => {
    selectedPost.value = post;
    showDeleteModal.value = true;
}

const destroy = () => {
    if (! selectedPost.value) {
        return;
    }

    const form = useForm({});
    form.delete(route('posts.destroy', selectedPost.value.id), {
        onSuccess: () => {
            showDeleteModal.value = false;
            selectedPost.value = null;
        }
    })
}

const closeEditModal = () => {
    showEditModal.value  = false;
    selectedPost.value   = null;
}

const closeDeleteModal = () => {
    showDeleteModal.value  = false;
    selectedPost.value   = null;
}
</script>

<template>
    <div class="overflow-auto">
        <PostItem
            v-for="post in posts"
            :post="post"
            @update="openEditModal"
            @destroy="openConfirmDeleteModal"
        />

        <PostModal v-if="selectedPost"
          :show="showEditModal"
          :post="selectedPost"
          @close="closeEditModal"
        />

        <Modal
          :show="showDeleteModal"
          @close="closeDeleteModal"
        >
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Are you sure you want to delete this post?
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    This action cannot be undone.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeDeleteModal">
                        Cancel
                    </SecondaryButton>
                    <DangerButton
                        class="ms-3"
                        @click="destroy"
                    >
                        Delete post
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </div>
</template>
