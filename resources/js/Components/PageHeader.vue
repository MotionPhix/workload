<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { cn } from '@/lib/utils'
import { Button } from '@/Components/ui/button'
import { IconChevronRight } from '@tabler/icons-vue'

interface Breadcrumb {
  label: string
  href?: string
}

interface Action {
  label: string
  icon?: any // Component type for icon
  href?: string
  onClick?: () => void
  variant?: 'default' | 'secondary' | 'destructive' | 'outline' | 'ghost' | 'link'
}

const props = defineProps<{
  title: string
  description?: string
  breadcrumbs?: Breadcrumb[]
  actions?: Action[]
  noDivider?: boolean
}>()

const lastBreadcrumbIndex = computed(() => {
  return props.breadcrumbs ? props.breadcrumbs.length - 1 : 0
})
</script>

<template>
  <div
    :class="[
      'flex flex-col gap-4 pb-6',
      { 'border-b': !noDivider }
    ]"
  >
    <!-- Breadcrumbs -->
    <nav
      v-if="breadcrumbs && breadcrumbs.length > 0"
      class="flex items-center text-sm text-muted-foreground"
    >
      <ol class="flex items-center gap-2">
        <li v-for="(crumb, index) in breadcrumbs" :key="index">
          <div class="flex items-center gap-2">
            <template v-if="crumb.href">
              <Link
                :href="crumb.href"
                class="hover:text-foreground transition-colors"
              >
                {{ crumb.label }}
              </Link>
            </template>
            <template v-else>
              <span :class="{ 'text-foreground': index === lastBreadcrumbIndex }">
                {{ crumb.label }}
              </span>
            </template>

            <IconChevronRight
              v-if="index !== lastBreadcrumbIndex"
              class="h-4 w-4"
            />
          </div>
        </li>
      </ol>
    </nav>

    <!-- Header Content -->
    <div class="flex items-center justify-between gap-4">
      <div class="space-y-1">
        <h1 class="text-2xl font-semibold tracking-tight">
          {{ title }}
        </h1>
        <p
          v-if="description"
          class="text-sm text-muted-foreground"
        >
          {{ description }}
        </p>
      </div>

      <!-- Actions -->
      <div
        v-if="actions && actions.length > 0"
        class="flex items-center gap-2"
      >
        <template v-for="(action, index) in actions" :key="index">
          <Link
            v-if="action.href"
            :href="action.href"
          >
            <Button :variant="action.variant || 'default'">
              <component
                v-if="action.icon"
                :is="action.icon"
                class="mr-2 h-4 w-4"
              />
              {{ action.label }}
            </Button>
          </Link>

          <Button
            v-else-if="action.onClick"
            :variant="action.variant || 'default'"
            @click="action.onClick"
          >
            <component
              v-if="action.icon"
              :is="action.icon"
              class="mr-2 h-4 w-4"
            />
            {{ action.label }}
          </Button>
        </template>
      </div>
    </div>
  </div>
</template>
