<script setup lang="ts">
import {ref, onMounted} from 'vue';
import {router, usePage} from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";

const notifications = ref([]);
const {brand} = usePage().props.brand

onMounted(() => {
  setTimeout(() => {
    window.Echo.private(`brand.${brand?.id}`)
      .listen('InvitationSent', (data) => {
        notifications.value.push(data);
      });
  }, 300)
});

const acceptInvitation = (invitationId) => {
  router.get(route('invitations.accept', invitationId));
};

const rejectInvitation = (invitationId) => {
  router.get(route('invitations.reject', invitationId));
};
</script>

<template>
  <AppLayout title="Welcome">
    <div>
      <h1 class="text-2xl font-bold">Dashboard</h1>
      <div v-if="notifications.length > 0" class="mt-4">
        <div v-for="notification in notifications" :key="notification.id" class="p-4 border rounded-lg mb-2">
          <p>You have been invited to join <strong>{{ notification.brand }}</strong> as a <strong>{{
              notification.role
            }}</strong>.</p>
          <Button @click="acceptInvitation(notification.id)" class="mt-2">Accept</Button>
          <Button @click="rejectInvitation(notification.id)" variant="destructive" class="mt-2 ml-2">Reject</Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
