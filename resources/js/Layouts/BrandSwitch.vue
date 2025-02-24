<script setup lang="ts">
import {IconBuildingArch, IconChevronDown} from "@tabler/icons-vue";
import {router, usePage} from "@inertiajs/vue3";
import {onMounted, ref} from "vue";
import axios from "axios";
import Avatar from "@/Components/ui/avatar/Avatar.vue";
import AvatarFallback from "@/Components/ui/avatar/AvatarFallback.vue";
import { ChevronsUpDown, Plus } from "lucide-vue-next";
import AvatarImage from "@/Components/ui/avatar/AvatarImage.vue";

const currentBrand = usePage().props?.brand
const brands = ref<Object>([])

// Switch brand
const switchBrand = (brand) => {
  router.put(route('brands.switch', brand.id), {}, {
    onSuccess: () => {
      window.location.reload(); // Refresh to reflect the new active brand
    },
  });
};

onMounted(() => {
  axios.get(route('api.brands.index')).then(resp => {
    if( resp.status != 200 ){
      throw resp.status;
    }else{
      brands.value = resp.data
    }
  })
})
</script>

<template>
  <!-- Brand Switcher -->
  <!-- <DropdownMenu v-if="brands.length > 0">
    <DropdownMenuTrigger class="flex items-center gap-x-1">

<IconBuildingArch class="w-4 h-4" v-if="! currentBrand.media['original_url']" />
      <Avatar v-else>
        <AvatarImage :src="currentBrand.media['original_url']" />

        <AvatarFallback>
          {{ currentBrand.name }}
        </AvatarFallback>
      </Avatar>
      <span>{{ currentBrand?.name || 'Select Brand' }}</span>
      <IconChevronDown class="w-4 h-4" />
    </DropdownMenuTrigger>

    <DropdownMenuContent align="end">
      <DropdownMenuItem
        v-for="brand in brands"
        :key="brand.id"
        @click="switchBrand(brand)"
        :class="{ 'bg-accent': brand.id === currentBrand?.id }">
        {{ brand.name }}
      </DropdownMenuItem>
    </DropdownMenuContent>
  </DropdownMenu> -->

  <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <Button
            size="sm">
            <div class="flex items-center justify-center rounded-lg aspect-square size-8 bg-sidebar-primary text-sidebar-primary-foreground">
              <IconBuildingArch class="w-4 h-4" v-if="! currentBrand.media['original_url']" />
      <Avatar v-else>
        <AvatarImage :src="currentBrand.logo" />

        <AvatarFallback>
          {{ currentBrand.name }}
        </AvatarFallback>
      </Avatar>
      <span>{{ currentBrand?.name || 'Select Brand' }}</span>
            </div>
            <div class="grid flex-1 text-sm leading-tight text-left">
              <span class="font-semibold truncate">
                {{ currentBrand.name }}
              </span>
              <!-- <span class="text-xs truncate">{{ currentBrand.plan }}</span> -->
            </div>
            <ChevronsUpDown class="ml-auto" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent
          class="w-[--reka-dropdown-menu-trigger-width] min-w-56 rounded-lg"
          align="start"
          :side="'right'"
          :side-offset="4"
        >
          <DropdownMenuLabel class="text-xs text-muted-foreground">
            Brands
          </DropdownMenuLabel>

          <DropdownMenuItem
            v-for="(brand, index) in brands"
            :key="brand.name"
            class="gap-2 p-2"
            @click="currentBrand.id = brand.id"
          >
            <div class="flex items-center justify-center border rounded-sm size-6">
              <component :is="brand.logo" class="size-4 shrink-0" />
            </div>
            {{ brand.name }}
            <DropdownMenuShortcut>⌘{{ index + 1 }}</DropdownMenuShortcut>
          </DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem class="gap-2 p-2">
            <div class="flex items-center justify-center border rounded-md size-6 bg-background">
              <Plus class="size-4" />
            </div>
            <div class="font-medium text-muted-foreground">
              Add brand
            </div>
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
</template>
