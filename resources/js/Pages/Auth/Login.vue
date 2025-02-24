<script setup lang="ts">
import {Head, Link, useForm} from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import {toast} from "vue-sonner";

defineProps<{
  canResetPassword: boolean
  status?: string,
}>()

const form = useForm({
  email: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onSuccess: () => {
      toast.success("Welcome back!", {
        description: "You have successfully logged in.",
      })
    },

    onError: (errors) => {
      if (errors.email) {
        toast.error("Login failed", {
          description: errors.email,
        })
      }
    },

    onFinish: () => {
      form.reset('password')
    },
  })
}
</script>

<template>
  <AuthenticationCard>
    <Head title="Log in"/>

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div class="space-y-4">
        <FormField
          label="Email"
          v-model="form.email"
          type="email"
          required
          autofocus
          placeholder="Enter your email address"
          :error="form.errors.email"
        />

        <FormField
          label="Password"
          v-model="form.password"
          type="password"
          required
          autocomplete="current-password"
          :errors="form.errors.password"
        />

        <div class="flex items-center justify-between">
          <label class="flex items-center">
            <Checkbox
              v-model:checked="form.remember"
            />
            <span class="ml-2 text-sm text-gray-600">Remember me</span>
          </label>

          <Link
            v-if="canResetPassword"
            :href="route('password.request')"
            class="text-sm text-primary hover:underline">
            Forgot your password?
          </Link>
        </div>

        <Button
          class="w-full"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing">
          {{ form.processing ? 'Logging in...' : 'Log in' }}
        </Button>
      </div>
    </form>
  </AuthenticationCard>
</template>
