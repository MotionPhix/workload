<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import { Link } from '@inertiajs/vue3';
import { IconPlus } from '@tabler/icons-vue';
import {type Component} from "vue";

interface Props {
  title: string
  description: string
  buttonLabel?: string
  buttonLink?: string
  icon?: Component
  illustration?: string // URL to illustration if needed
  isModal?: boolean
}

withDefaults(defineProps<Props>(), {
  buttonLabel: undefined,
  buttonLink: undefined,
  icon: IconPlus,
  illustration: undefined,
  isModal: false
})
</script>

<template>
  <div class="flex flex-col items-center justify-center py-12 text-center">
    <!-- Illustration or Icon -->
    <div
      v-if="illustration"
      class="mb-6">
      <img
        :src="illustration"
        alt="Empty state illustration"
        class="w-64 h-64"
      />
    </div>

    <div
      v-else-if="icon"
      class="mb-6 rounded-full bg-muted p-4">
      <component
        :is="icon"
        class="h-8 w-8 text-muted-foreground"
      />
    </div>

    <!-- Content -->
    <div class="max-w-md space-y-2">
      <h3 class="text-lg font-semibold">
        {{ title }}
      </h3>

      <p class="text-sm text-muted-foreground">
        {{ description }}
      </p>
    </div>

    <!-- Action Button -->
    <div
      v-if="buttonLabel && buttonLink"
      class="mt-6">
      <ModalLink
        as="Button"
        v-if="isModal"
        :href="buttonLink">
        <IconPlus class="mr-2 h-4 w-4" />
        {{ buttonLabel }}
      </ModalLink>

      <Link
        :href="buttonLink"
        v-else>
        <Button>
          <IconPlus class="mr-2 h-4 w-4" />
          {{ buttonLabel }}
        </Button>
      </Link>
    </div>
  </div>
</template>
