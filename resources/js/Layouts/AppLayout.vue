<script setup lang="ts">
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { IconSun, IconMoon, IconLogout } from '@tabler/icons-vue';
import {Toaster} from "vue-sonner";
import BrandSwitch from "@/Layouts/BrandSwitch.vue";

defineProps<{
  title: string
}>()

const user = usePage().props.auth.user

// Dark mode
const isDark = useDark();
const toggleDark = useToggle(isDark);

// Logout
const logout = () => {
  router.post(route('logout'));
};
</script>

<template>
  <Toaster rich-colors expand />

  <Head :title="title" />

  <div class="min-h-screen bg-background text-foreground">
    <!-- Header -->
    <header class="border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
      <div class="container flex items-center justify-between h-16 px-4">
        <!-- Logo and Navigation -->
        <div class="flex items-center gap-6">
          <Link :href="route('dashboard')" class="text-lg font-semibold">
            Workload
          </Link>
          <nav class="items-center hidden gap-6 md:flex">
            <Link :href="route('projects.index')" class="text-sm font-medium hover:text-primary">
              Projects
            </Link>
            <Link href="#" class="text-sm font-medium hover:text-primary">
              Tasks
            </Link>
          </nav>
        </div>

        <!-- Brand Switcher and User Menu -->
        <div class="flex items-center gap-4">
          <BrandSwitch />

          <!-- Theme Toggler -->
          <Button variant="ghost" size="icon" @click="toggleDark()">
            <IconSun v-if="!isDark" class="w-4 h-4" />
            <IconMoon v-else class="w-4 h-4" />
          </Button>

          <!-- User Menu -->
          <DropdownMenu>
            <DropdownMenuTrigger as-child>
              <Button variant="ghost" size="icon" class="relative w-8 h-8 rounded-full">
                <Avatar class="w-8 h-8">
                  <AvatarImage :src="user.profile_photo_url" :alt="user.name" />
                  <AvatarFallback>{{ user.initials }}</AvatarFallback>
                </Avatar>
              </Button>
            </DropdownMenuTrigger>

            <DropdownMenuContent
              class="w-56" align="end">
              <DropdownMenuItem @click="logout">
                <IconLogout class="w-4 h-4 mr-2" />
                <span>Log out</span>
              </DropdownMenuItem>
            </DropdownMenuContent>
          </DropdownMenu>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="container py-6">
      <slot />
    </main>
  </div>
</template>
