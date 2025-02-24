<script setup lang="ts">
import {useForm} from "@inertiajs/vue3";

defineProps<{
  project: Object
}>()

const form = useForm({
  name: '',
  description: '',
  start_date: '',
  end_date: '',
})

const submit = () => {
  form.post(route('projects.store'), {
    onSuccess: () => {
      isOpen.value = false
      form.reset()
    },
  })
}
</script>

<template>
  <GlobalModal
    modal-title="Create New Project"
    description="Add a new project to your workspace">

    <form
      @submit.prevent="submit"
      class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">
          Name
        </label>
        <Input
          v-model="form.name"
          type="text"
          class="mt-1"
          required
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">
          Description
        </label>
        <Textarea
          v-model="form.description"
          class="mt-1"
          rows="3"
        />
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">
            Start Date
          </label>
          <Input
            v-model="form.start_date"
            type="date"
            class="mt-1"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">
            End Date
          </label>
          <Input
            v-model="form.end_date"
            type="date"
            class="mt-1"
          />
        </div>
      </div>
    </form>

    <template #footer>
      <Button
        type="submit"
        :disabled="form.processing">
        Create Project
      </Button>
    </template>
  </GlobalModal>
</template>
