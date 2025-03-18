<script setup lang="ts">
import { ref } from 'vue'
import { router } from "@inertiajs/vue3"
import { toast } from 'vue-sonner'
import AppLayout from '@/Layouts/AppLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import { IconArrowLeft } from '@tabler/icons-vue'
import ProjectForm, {Project} from "@/Pages/Projects/Partials/ProjectForm.vue";

const props = defineProps<{
  project: Project
}>()

const isSubmitting = ref(false)

const breadcrumbs = [
  {
    label: 'Projects',
    href: route('projects.index')
  },
  {
    label: props.project.name,
    href: route('projects.show', props.project.id)
  },
  {
    label: 'Edit'
  }
]

const handleSubmit = (data: Project) => {
  isSubmitting.value = true

  router.put(route('projects.update', props.project.id), data, {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Project updated successfully')
    },
    onError: () => {
      toast.error('Failed to update project')
    },
    onFinish: () => {
      isSubmitting.value = false
    }
  })
}

const handleCancel = () => {
  router.get(route('projects.show', props.project.id))
}
</script>

<template>
  <AppLayout :title="`Edit ${project.name}`">
    <div class="space-y-6">
      <PageHeader
        :title="`Edit ${project.name}`"
        description="Update project details"
        :breadcrumbs="breadcrumbs"
        :actions="[
          {
            label: 'Back to Project',
            icon: IconArrowLeft,
            href: route('projects.show', project.id),
            variant: 'outline'
          }
        ]"
      />

      <div class="max-w-2xl">
        <ProjectForm
          :project="project"
          :is-submitting="isSubmitting"
          @submit="handleSubmit"
          @cancel="handleCancel"
        />
      </div>
    </div>
  </AppLayout>
</template>
