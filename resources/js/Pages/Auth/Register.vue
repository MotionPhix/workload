<script setup lang="ts">
import {ref, computed, onBeforeUnmount} from 'vue'
import {Head, Link, useForm} from '@inertiajs/vue3'
import AuthenticationCard from '@/Components/AuthenticationCard.vue'
import {FormField as ShadField, FormItem, FormLabel, FormMessage, FormControl} from "@/Components/ui/form";
import {useStorage} from "@vueuse/core";
import InputError from "@/Components/InputError.vue";

const currentStep = useStorage('registration_steps', 1, sessionStorage)
const totalSteps = 2

const form = useForm({
  // Brand Info
  brand_name: '',
  brand_email: '',
  brand_website: '',
  brand_logo: null,

  // Owner Info
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  terms: false,
})

const logoPreview = ref(null)

const handleLogoChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.brand_logo = file
    logoPreview.value = URL.createObjectURL(file)
  }
}

const nextStep = () => {
  if (currentStep.value < totalSteps) {
    currentStep.value++
  }
}

const prevStep = () => {
  if (currentStep.value > 1) {
    currentStep.value--
  }
}

const getFileInput = () => {
  const el = document.getElementById('logoInput')
  el.click()
}

const submit = () => {
  form.post(route('register'), {
    onFinish: () => {
      form.reset()
    },
  })
}

const progress = computed(() => (currentStep.value / totalSteps) * 100)

onBeforeUnmount(() => {
  currentStep.value = null
})
</script>

<template>
  <AuthenticationCard>
    <Head title="Register"/>

    <Card>
      <CardHeader>
        <CardTitle>Create your workspace</CardTitle>
        <CardDescription>
          Get started by setting up your brand and account
        </CardDescription>
      </CardHeader>

      <CardContent>
        <!-- Progress Bar -->
        <div class="w-full h-2 mb-6">
          <Progress v-model="progress" class="h-2 rounded-none"/>
        </div>

        <form @submit.prevent="submit">
          <!-- Step 1: Brand Information -->
          <div v-show="currentStep === 1" class="space-y-4">
            <FormField
              label="Brand Name"
              v-model="form.brand_name"
              required
              autofocus
              placeholder="Enter your entity name"
              :errors="form.errors.brand_name"
            />

            <FormField
              label="Brand Email"
              v-model="form.brand_email"
              type="email"
              required
              placeholder="Enter your office email"
              :errors="form.errors.brand_email"
            />

            <FormField
              label="Brand Website"
              v-model="form.brand_website"
              type="url"
              placeholder="Enter your entity web url"
              :error="form.errors.brand_website"
            />

            <div class="grid">
              <Label>Brand Logo</Label>

              <div class="flex items-center gap-4">
                <img
                  v-if="logoPreview"
                  :src="logoPreview"
                  class="w-16 h-16 rounded-full object-cover"
                />

                <Button
                  type="button"
                  variant="outline"
                  class="w-full text-left"
                  @click="getFileInput">
                  Choose Logo
                </Button>

                <Input
                  id="logoInput"
                  type="file"
                  class="hidden"
                  accept="image/*"
                  @change="handleLogoChange"
                />
              </div>

              <InputError>{{ form.errors.brand_logo }}</InputError>
            </div>
          </div>

          <!-- Step 2: Owner Information -->
          <div v-show="currentStep === 2" class="space-y-4">
            <h2 class="text-lg font-semibold">Your Information</h2>

            <FormField
              label="Your Name"
              v-model="form.name"
              required
              placeholder="Enter your full name"
              :error="form.errors.name"
            />

            <FormField
              label="our Email"
              v-model="form.email"
              type="email"
              required
              placeholder="Enter your email address"
              :error="form.errors.email"
            />

            <FormField
              label="Password"
              v-model="form.password"
              type="password"
              required
              autocomplete="new-password"
              :error="form.errors.password"
            />

            <FormField
              label="Confirm Password"
              v-model="form.password_confirmation"
              type="password"
              required
              autocomplete="new-password"
              placeholder="Confirm your password"
            />

            <div class="flex items-center">
              <Checkbox
                v-model:checked="form.terms"
                id="terms"
              />
              <Label for="terms" class="ml-2 text-sm text-gray-600">
                I agree to the
                <a href="#" class="text-primary hover:underline">Terms of Service</a>
                and
                <a href="#" class="text-primary hover:underline">Privacy Policy</a>
              </Label>
            </div>
          </div>

          <div class="text-center text-sm text-gray-600">
            Already registered?
            <Link
              :href="route('login')"
              class="text-primary hover:underline">
              Login
            </Link>
          </div>
        </form>
      </CardContent>

      <CardFooter class="flex justify-between">
        <Button
          v-if="currentStep > 1"
          type="button"
          variant="outline"
          @click="prevStep">
          Previous
        </Button>

        <div class="flex justify-end gap-4">
          <Button
            v-if="currentStep < totalSteps"
            type="button"
            @click="nextStep">
            Next
          </Button>

          <Button
            v-if="currentStep === totalSteps"
            type="submit"
            :disabled="form.processing"
            @click="submit">
            Register
          </Button>
        </div>
      </CardFooter>
    </Card>
  </AuthenticationCard>
</template>
