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
        <div v-for="attachment in task.attachments" :key="attachment.id" class="p-4 border rounded-lg">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <IconPaperclip class="h-4 w-4" />
              <a :href="attachment.path" target="_blank" class="hover:underline">
                {{ attachment.filename }}
              </a>
            </div>
            <Button variant="ghost" size="sm" @click="deleteAttachment(attachment)">
              <IconTrash class="h-4 w-4" />
            </Button>
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
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Textarea } from '@/Components/ui/textarea';
import { Input } from '@/Components/ui/input';
import { IconTrash, IconPaperclip } from '@tabler/icons-vue';

const { task } = defineProps({ task: Object });

const commentForm = ref({ content: '' });
const attachmentForm = ref({ file: null });

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

const deleteAttachment = (attachment) => {
  if (confirm('Are you sure you want to delete this file?')) {
    router.delete(route('attachments.destroy', attachment.id));
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleString();
};
</script>
