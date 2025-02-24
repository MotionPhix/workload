<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const { brand } = defineProps({ brand: Object });

const form = useForm({
  email: '',
  role: 'employee',
});

const submit = () => {
  form.post(route('invitations.store', brand.id), {
    onSuccess: () => form.reset(),
  });
};
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Invite to {{ brand.name }}</h1>
    <form @submit.prevent="submit">
      <Input v-model="form.email" type="email" placeholder="Email" required />
      <Select v-model="form.role" class="mt-4">
        <SelectTrigger>
          <SelectValue placeholder="Select Role" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="admin">Admin</SelectItem>
          <SelectItem value="employee">Employee</SelectItem>
        </SelectContent>
      </Select>
      <Button type="submit" class="mt-4">Send Invitation</Button>
    </form>
  </div>
</template>
