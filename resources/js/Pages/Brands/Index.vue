<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { MoreVertical, Pencil, Trash2 } from 'lucide-vue-next'

defineProps({
  brands: {
    type: Array,
    required: true
  }
})
</script>

<template>
  <AppLayout>
    <Head title="Brands" />

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-semibold">Brands</h2>
          <Link
            :href="route('brands.create')"
            class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90"
          >
            Add Brand
          </Link>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Logo</TableHead>
                <TableHead>Name</TableHead>
                <TableHead>Website</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Actions</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="brand in brands" :key="brand.id">
                <TableCell>
                  <img
                    :src="brand.logo_url"
                    :alt="brand.name"
                    class="w-10 h-10 object-cover rounded-full"
                  />
                </TableCell>
                <TableCell>{{ brand.name }}</TableCell>
                <TableCell>
                  <a
                    v-if="brand.website"
                    :href="brand.website"
                    target="_blank"
                    class="text-blue-600 hover:underline"
                  >
                    {{ brand.website }}
                  </a>
                </TableCell>
                <TableCell>{{ brand.email }}</TableCell>
                <TableCell>
                  <DropdownMenu>
                    <DropdownMenuTrigger as="Button" variant="ghost" size="icon">
                      <MoreVertical class="w-4 h-4" />
                    </DropdownMenuTrigger>
                    <DropdownMenuContent>
                      <DropdownMenuItem>
                        <Link
                          :href="route('brands.edit', brand.id)"
                          class="flex items-center"
                        >
                          <Pencil class="w-4 h-4 mr-2" />
                          Edit
                        </Link>
                      </DropdownMenuItem>
                      <DropdownMenuItem>
                        <Link
                          :href="route('brands.destroy', brand.id)"
                          method="delete"
                          as="button"
                          class="flex items-center text-red-600"
                        >
                          <Trash2 class="w-4 h-4 mr-2" />
                          Delete
                        </Link>
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
