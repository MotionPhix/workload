<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Progress } from '@/Components/ui/progress';
import { Button } from '@/Components/ui/button';
import { IconEye } from '@tabler/icons-vue';
import Pagination from '@/Components/Pagination.vue';
import axios from "axios";

const { brand } = defineProps({ brand: Object });

const memberActivity = ref({ data: [], links: [] });
const projectProgress = ref([]);

onMounted(() => {
  setTimeout(() => {
    const response = axios.get(route('analytics.member-activity', brand.id));
    memberActivity.value = response.data;

    const progressResponse = axios.get(route('analytics.project-progress', brand.id));
    projectProgress.value = progressResponse.data;
  }, 300)
});

const formatDate = (date) => {
  return new Date(date).toLocaleString();
};

const viewDetails = (log) => {
  console.log(log.details);
};
</script>

<template>
  <div class="space-y-6">
    <h1 class="text-2xl font-bold">Brand Analytics</h1>

    <!-- Member Activity -->
    <div>
      <h2 class="text-xl font-semibold mb-4">Member Activity</h2>
      <ul>
        <li v-for="log in memberActivity.data" :key="log.id" class="p-4 border rounded-lg mb-2">
          <div class="flex items-center justify-between">
            <div>
              <p class="font-medium">{{ log.user.name }}</p>
              <p class="text-sm text-muted-foreground">{{ log.action }} - {{ formatDate(log.created_at) }}</p>
            </div>
            <Button variant="ghost" size="sm" @click="viewDetails(log)">
              <IconEye class="h-4 w-4" />
            </Button>
          </div>
        </li>
      </ul>
      <Pagination :pagination="memberActivity" />
    </div>

    <!-- Project Progress -->
    <div>
      <h2 class="text-xl font-semibold mb-4">Project Progress</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div v-for="progress in projectProgress" :key="progress.id" class="p-4 border rounded-lg">
          <p class="font-medium">{{ progress.project.name }}</p>
          <p class="text-sm text-muted-foreground">{{ progress.completed_tasks }} / {{ progress.total_tasks }} tasks completed</p>
          <Progress :value="progress.progress" class="mt-2" />
        </div>
      </div>
    </div>
  </div>
</template>
