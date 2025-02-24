<script setup lang="ts">
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/Layouts/AppLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Button } from '@/components/ui/button'
import { Textarea } from '@/components/ui/textarea'

const props = defineProps({
  brand: {
    type: Object,
    default: () => ({})
  }
})

const form = useForm({
  name: props.brand.name || '',
  description: props.brand.description || '',
  website: props.brand.website || '',
  email: props.brand.email || '',
  phone: props.brand.phone || '',
  address: props.brand.address || '',
  city: props.brand.city || '',
  state: props.brand.state || '',
  country: props.brand.country || '',
  postal_code: props.brand.postal_code || '',
  logo: null
})

const logoPreview = ref(props.brand.logo_url || null)

const handleLogoChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.logo = file
    logoPreview.value = URL.createObjectURL(file)
  }
}

const submit = () => {
  if (props.brand.id) {
    form.post(route('brands.update', props.brand.id), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset()
      }
    })
  } else {
    form.post(route('brands.store'), {
      onSuccess: () => {
        form.reset()
      }
    })
  }
}
</script>

<template>
  <AppLayout>
    <Head :title="brand.id ? 'Edit Brand' : 'Create Brand'" />

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <form @submit.prevent="submit" class="space-y-6">
            <div>
              <Label for="logo">Logo</Label>
              <div class="mt-2 flex items-center gap-x-3">
                <img
                  v-if="logoPreview"
                  :src="logoPreview"
                  alt="Logo preview"
                  class="h-12 w-12 rounded-full object-cover"
                />
                <Button
                  type="button"
                  variant="outline"
                  @click="$refs.logoInput.click()"
                >
                  Change
                </Button>
                <input
                  ref="logoInput"
                  type="file"
                  class="hidden"
                  accept="image/*"
                  @change="handleLogoChange"
                />
              </div>
            </div>

            <div>
              <Label for="name">Name</Label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                class="mt-1 block w-full"
                required
              />
            </div>

            <div>
              <Label for="description">Description</Label>
              <Textarea
                id="description"
                v-model="form.description"
                class="mt-1 block w-full"
                rows="3"
              />
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <Label for="website">Website</Label>
                <Input
                  id="website"
                  v-model="form.website"
                  type="url"
                  class="mt-1 block w-full"
                />
              </div>

              <div>
                <Label for="email">Email</Label>
                <Input
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="mt-1 block w-full"
                />
              </div>

              <div>
                <Label for="phone">Phone</Label>
                <Input
                  id="phone"
                  v-model="form.phone"
                  type="tel"
                  class="mt-1 block w-full"
                />
              </div>
            </div>

            <div>
              <Label for="address">Address</Label>
              <Textarea
                id="address"
                v-model="form.address"
                class="mt-1 block w-full"
                rows="2"
              />
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
              <div>
                <Label for="city">City</Label>
                <Input
                  id="city"
                  v-model="form.city"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>

              <div>
                <Label for="state">State</Label>
                <Input
                  id="state"
                  v-model="form.state"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>

              <div>
                <Label for="country">Country</Label>
                <Input
                  id="country"
                  v-model="form.country"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>

              <div>
                <Label for="postal_code">Postal Code</Label>
                <Input
                  id="postal_code"
                  v-model="form.postal_code"
                  type="text"
                  class="mt-1 block w-full"
                />
              </div>
            </div>

            <div class="flex justify-end gap-x-4">
              <Button
                type="button"
                variant="outline"
                :href="route('brands.index')"
              >
                Cancel
              </Button>
              <Button type="submit" :disabled="form.processing">
                {{ brand.id ? 'Update' : 'Create' }}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
