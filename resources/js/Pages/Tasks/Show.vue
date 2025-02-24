<template>
  <div class="space-y-6">
    <!-- Task Details -->
    <div>
      <h1 class="text-2xl font-bold">{{ task.title }}</h1>
      <p class="text-muted-foreground">{{ task.description }}</p>
    </div>

    <!-- Comments Section -->
    <div>
      <h2 class="text-xl font-semibold mb-4">Comments</h2>
      <div class="space-y-4">
        <div v-for="comment in task.comments" :key="comment.id" class="p-4 border rounded-lg">
          <div class="flex items-center justify-between">
            <div class="text-sm text-muted-foreground">
              {{ comment.user.name }} - {{ formatDate(comment.created_at) }}
            </div>
            <Button variant="ghost" size="sm" @click="deleteComment(comment)">
              <IconTrash class="h-4 w-4" />
            </Button>
          </div>
          <p class="mt-2">{{ comment.content }}</p>
        </div>
      </div>
      <form @submit.prevent="submitComment" class="mt-4">
        <Textarea v-model="commentForm.content" placeholder="Add a comment..." required />
        <Button type="submit" class="mt-2">Add Comment</Button>
      </form>
    </div>

    <!-- Attachments Section -->
    <div>
      <h2 class="text-xl font-semibold mb-4">Attachments</h2>
      <div class="space-y-4">
        <div v-for="media in task.media" :key="media.id" class="p-4 border rounded-lg">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <IconPaperclip class="h-4 w-4" />
              <a :href="media.original_url" target="_blank" class="hover:underline">
                {{ media.file_name }}
              </a>
            </div>
            <Button variant="ghost" size="sm" @click="deleteAttachment(media.id)">
              <IconTrash class="h-4 w-4" />
            </Button>
          </div>
          <!-- File Preview -->
          <div v-if="isImage(media.mime_type)" class="mt-4">
            <img :src="media.original_url" :alt="media.file_name" class="max-w-full h-auto rounded-lg" />
          </div>
          <div v-else-if="isPdf(media.mime_type)" class="mt-4">
            <iframe :src="media.original_url" class="w-full h-96 rounded-lg" frameborder="0"></iframe>
          </div>
        </div>
      </div>
      <form @submit.prevent="submitAttachment" class="mt-4">
        <Input type="file" @change="handleFileUpload" required />
        <Button type="submit" class="mt-2">Upload File</Button>
      </form>
    </div>
  </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Textarea } from '@/Components/ui/textarea';
import { Input } from '@/Components/ui/input';
import { IconTrash, IconPaperclip } from '@tabler/icons-vue';

const { task } = defineProps({ task: Object });

const commentForm = ref({ content: '' });
const attachmentForm = ref({ file: null });
const comments = ref(task.comments);
const media = ref(task.media);

const submitComment = () => {
  router.post(route('comments.store', task.id), {
    content: commentForm.value.content,
  }, {
    onSuccess: () => commentForm.value.content = '',
  });
};

const deleteComment = (comment) => {
  if (confirm('Are you sure you want to delete this comment?')) {
    router.delete(route('comments.destroy', comment.id));
  }
};

const handleFileUpload = (event) => {
  attachmentForm.value.file = event.target.files[0];
};

const submitAttachment = () => {
  const formData = new FormData();
  formData.append('file', attachmentForm.value.file);

  router.post(route('attachments.store', task.id), formData, {
    onSuccess: () => attachmentForm.value.file = null,
  });
};

const deleteAttachment = (mediaId) => {
  if (confirm('Are you sure you want to delete this file?')) {
    router.delete(route('attachments.destroy', { task: task.id, mediaId }));
  }
};

const isImage = (mimeType) => {
  return mimeType.startsWith('image/');
};

const isPdf = (mimeType) => {
  return mimeType === 'application/pdf';
};

const formatDate = (date) => {
  return new Date(date).toLocaleString();
};

onMounted(() => {
  // Listen for new comments
  window.Echo.private(`task.${task.id}`)
    .listen('CommentCreated', (data) => {
      comments.value.push(data);
    });

  // Listen for new attachments
  window.Echo.private(`task.${task.id}`)
    .listen('AttachmentCreated', (data) => {
      media.value.push(data);
    });
});
</script>
