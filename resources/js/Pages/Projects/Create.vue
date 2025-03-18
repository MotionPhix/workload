<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from "@inertiajs/vue3"
import { format } from 'date-fns'
import { toast } from 'vue-sonner'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Textarea } from '@/Components/ui/textarea'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import {
  Form,
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import PageHeader from '@/Components/PageHeader.vue'
import { IconArrowLeft } from '@tabler/icons-vue'

const today = format(new Date(), 'yyyy-MM-dd')

interface FormData {
  name: string
  description: string
  status: 'planned' | 'in_progress' | 'completed'
  priority: 'low' | 'medium' | 'high'
  due_date: string
}

const form = useForm<FormData>({
  name: '',
  description: '',
  status: 'planned',
  priority: 'medium',
  due_date: today,
})

const breadcrumbs = [
  {
    label: 'Projects',
    href: route('projects.index')
  },
  {
    label: 'Create Project'
  }
]

const isSubmitting = ref(false)

const submit = () => {
  isSubmitting.value = true
  form.post(route('projects.store'), {
    preserveScroll: true,
    onSuccess: () => {
      toast.success('Project created successfully')
    },
    onError: (errors) => {
      toast.error('Failed to create project')
    },
    onFinish: () => {
      isSubmitting.value = false
    }
  })
}
</script>

<template>
  <div class="space-y-6">
    <PageHeader
      title="Create Project"
      description="Add a new project to your workspace"
      :breadcrumbs="breadcrumbs"
      :actions="[
        {
          label: 'Back to Projects',
          icon: IconArrowLeft,
          href: route('projects.index'),
          variant: 'outline'
        }
      ]"
    />

    <div class="max-w-2xl">
      <form @submit.prevent="submit" class="space-y-8">
        <!-- Name -->
        <FormField
          v-model="form.name"
          name="name"
          :error="form.errors.name"
        >
          <FormLabel required>Project Name</FormLabel>
          <FormControl>
            <Input
              v-model="form.name"
              placeholder="Enter project name"
              :error="form.errors.name"
            />
          </FormControl>
          <FormDescription>
            Give your project a clear and descriptive name
          </FormDescription>
          <FormMessage />
        </FormField>

        <!-- Description -->
        <FormField
          v-model="form.description"
          name="description"
          :error="form.errors.description"
        >
          <FormLabel>Description</FormLabel>
          <FormControl>
            <Textarea
              v-model="form.description"
              placeholder="Enter project description"
              rows="4"
              :error="form.errors.description"
            />
          </FormControl>
          <FormDescription>
            Provide additional details about your project
          </FormDescription>
          <FormMessage />
        </FormField>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
          <!-- Status -->
          <FormField
            v-model="form.status"
            name="status"
            :error="form.errors.status"
          >
            <FormLabel required>Status</FormLabel>
            <Select v-model="form.status">
              <FormControl>
                <SelectTrigger :error="form.errors.status">
                  <SelectValue placeholder="Select status" />
                </SelectTrigger>
              </FormControl>
              <SelectContent>
                <SelectItem value="planned">Planned</SelectItem>
                <SelectItem value="in_progress">In Progress</SelectItem>
                <SelectItem value="completed">Completed</SelectItem>
              </SelectContent>
            </Select>
            <FormDescription>
              Current status of the project
            </FormDescription>
            <FormMessage />
          </FormField>

          <!-- Priority -->
          <FormField
            v-model="form.priority"
            name="priority"
            :error="form.errors.priority"
          >
            <FormLabel required>Priority</FormLabel>
            <Select v-model="form.priority">
              <FormControl>
                <SelectTrigger :error="form.errors.priority">
                  <SelectValue placeholder="Select priority" />
                </SelectTrigger>
              </FormControl>
              <SelectContent>
                <SelectItem value="low">Low</SelectItem>
                <SelectItem value="medium">Medium</SelectItem>
                <SelectItem value="high">High</SelectItem>
              </SelectContent>
            </Select>
            <FormDescription>
              Project priority level
            </FormDescription>
            <FormMessage />
          </FormField>

          <!-- Due Date -->
          <FormField
            v-model="form.due_date"
            name="due_date"
            :error="form.errors.due_date"
          >
            <FormLabel required>Due Date</FormLabel>
            <FormControl>
              <Input
                type="date"
                v-model="form.due_date"
                :min="today"
                :error="form.errors.due_date"
              />
            </FormControl>
            <FormDescription>
              When should this project be completed?
            </FormDescription>
            <FormMessage />
          </FormField>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-x-4">
          <Button
            type="button"
            variant="ghost"
            :disabled="isSubmitting"
            @click="() => router.get(route('projects.index'))"
          >
            Cancel
          </Button>
          <Button
            type="submit"
            :disabled="isSubmitting || form.processing"
          >
            {{ isSubmitting ? 'Creating...' : 'Create Project' }}
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>
