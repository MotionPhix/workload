<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import { useDark, useToggle } from '@vueuse/core'
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar'
import { Button } from '@/Components/ui/button'
import { Sheet, SheetContent, SheetTrigger } from "@/Components/ui/sheet"
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger
} from '@/Components/ui/dropdown-menu'
import {
  IconSun,
  IconMoon,
  IconLogout,
  IconMenu2,
  IconDashboard,
  IconClipboardList,
  IconChecks,
  IconSettings
} from '@tabler/icons-vue'
import { Toaster } from "vue-sonner"
import BrandSwitch from "@/Layouts/BrandSwitch.vue"

const props = defineProps<{
  title: string
}>()

const page = usePage()
const user = page.props.auth.user
const isMobileMenuOpen = ref(false)

// Dark mode
const isDark = useDark()
const toggleDark = useToggle(isDark)

// Navigation items
const navigation = [
  {
    name: 'Dashboard',
    href: route('dashboard'),
    icon: IconDashboard,
  },
  {
    name: 'Projects',
    href: route('projects.index'),
    icon: IconClipboardList,
  },
  {
    name: 'Tasks',
    href: '#',
    icon: IconChecks,
  },
]

// Profile navigation
const profileNavigation = [
  {
    name: 'Settings',
    href: route('profile.show'),
    icon: IconSettings,
  },
  {
    name: 'Logout',
    href: '#',
    icon: IconLogout,
    onClick: () => router.post(route('logout')),
  },
]

const isCurrentRoute = (href: string) => {
  return window.location.pathname === href ||
    window.location.pathname.startsWith(href + '/')
}
</script>

<template>
  <Toaster rich-colors expand position="top-right" />

  <Head :title="title" />

  <div class="min-h-screen bg-background">
    <!-- Mobile menu -->
    <Sheet v-model:open="isMobileMenuOpen">
      <SheetTrigger asChild>
        <Button
          variant="ghost"
          size="icon"
          class="md:hidden"
        >
          <IconMenu2 class="h-6 w-6" />
        </Button>
      </SheetTrigger>
      <SheetContent side="left" class="w-[300px] sm:w-[400px]">
        <nav class="flex flex-col gap-4">
          <div
            v-for="item in navigation"
            :key="item.name"
            class="flex items-center">

            <Link
              :href="item.href"
              :class="[
                'flex items-center gap-3 w-full rounded-lg px-3 py-2 text-sm font-medium',
                isCurrentRoute(item.href)
                  ? 'bg-primary/10 text-primary'
                  : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground'
              ]">
              <component :is="item.icon" class="h-4 w-4" />
              {{ item.name }}
            </Link>
          </div>
        </nav>
      </SheetContent>
    </Sheet>

    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div class="container flex h-16 items-center justify-between px-4">
        <div class="flex items-center gap-6">
          <!-- Logo -->
          <Link
            :href="route('dashboard')"
            class="flex items-center space-x-2">
            <span class="font-bold">Workload</span>
          </Link>

          <!-- Desktop navigation -->
          <nav class="hidden md:flex items-center gap-6">
            <Link
              v-for="item in navigation"
              :key="item.name"
              :href="item.href"
              :class="[
                'text-sm font-medium transition-colors',
                isCurrentRoute(item.href)
                  ? 'text-primary'
                  : 'text-muted-foreground hover:text-primary'
              ]">
              {{ item.name }}
            </Link>
          </nav>
        </div>

        <!-- Right section -->
        <div class="flex items-center gap-4">
          <BrandSwitch />

          <!-- Theme toggle -->
          <Button
            variant="ghost"
            size="icon"
            @click="toggleDark()">
            <IconSun
              v-if="!isDark"
              class="h-4 w-4"
            />
            <IconMoon
              v-else
              class="h-4 w-4"
            />
          </Button>

          <!-- User menu -->
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button
                variant="ghost"
                size="icon"
                class="relative h-8 w-8 rounded-full">
                <Avatar class="h-8 w-8">
                  <AvatarImage
                    :src="user.profile_photo_url"
                    :alt="user.name"
                  />
                  <AvatarFallback>{{ user.initials }}</AvatarFallback>
                </Avatar>
              </Button>
            </DropdownMenuTrigger>

            <DropdownMenuContent class="w-56" align="end">
              <DropdownMenuLabel class="font-normal">
                <div class="flex flex-col space-y-1">
                  <p class="text-sm font-medium leading-none">
                    {{ user.name }}
                  </p>
                  <p class="text-xs leading-none text-muted-foreground">
                    {{ user.email }}
                  </p>
                </div>
              </DropdownMenuLabel>
              <DropdownMenuSeparator />
              <DropdownMenuItem
                v-for="item in profileNavigation"
                :key="item.name"
                :as="item.onClick ? 'button' : Link"
                :href="!item.onClick ? item.href : undefined"
                @click="item.onClick">
                <component :is="item.icon" class="mr-2 h-4 w-4" />
                <span>{{ item.name }}</span>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
    </header>

    <!-- Page Wrapper -->
    <div class="flex min-h-[calc(100vh-4rem)] flex-col">
      <!-- Main Content -->
      <main class="flex-1">
        <div class="container py-6 px-4">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>
