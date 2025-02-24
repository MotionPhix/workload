<template>
  <div class="space-y-6">
    <h1 class="text-2xl font-bold">Reports</h1>

    <!-- Brand Analytics Reports -->
    <div>
      <h2 class="text-xl font-semibold mb-4">Brand Analytics Reports</h2>
      <form @submit.prevent="generateBrandAnalyticsReport" class="space-y-4">
        <Select v-model="brandFilters.brand_id">
          <SelectTrigger>
            <SelectValue placeholder="Select Brand" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem v-for="brand in brands" :key="brand.id" :value="brand.id">
              {{ brand.name }}
            </SelectItem>
          </SelectContent>
        </Select>
        <Button type="submit">Generate Brand Analytics Report</Button>
      </form>
    </div>

    <!-- Task Reports -->
    <div>
      <h2 class="text-xl font-semibold mb-4">Task Reports</h2>
      <form @submit.prevent="generateTaskReport" class="space-y-4">
        <Select v-model="taskFilters.status">
          <SelectTrigger>
            <SelectValue placeholder="Status" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="todo">To Do</SelectItem>
            <SelectItem value="in_progress">In Progress</SelectItem>
            <SelectItem value="done">Done</SelectItem>
          </SelectContent>
        </Select>
        <Select v-model="taskFilters.priority">
          <SelectTrigger>
            <SelectValue placeholder="Priority" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="low">Low</SelectItem>
            <SelectItem value="medium">Medium</SelectItem>
            <SelectItem value="high">High</SelectItem>
          </SelectContent>
        </Select>
        <Input v-model="taskFilters.due_date" type="date" placeholder="Due Date" />
        <Button type="submit">Generate Task Report</Button>
      </form>
    </div>

    <!-- Project Reports -->
    <div>
      <h2 class="text-xl font-semibold mb-4">Project Reports</h2>
      <form @submit.prevent="generateProjectReport" class="space-y-4">
        <Select v-model="projectFilters.status">
          <SelectTrigger>
            <SelectValue placeholder="Status" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="planned">Planned</SelectItem>
            <SelectItem value="in_progress">In Progress</SelectItem>
            <SelectItem value="completed">Completed</SelectItem>
          </SelectContent>
        </Select>
        <Input v-model="projectFilters.start_date" type="date" placeholder="Start Date" />
        <Input v-model="projectFilters.end_date" type="date" placeholder="End Date" />
        <Button type="submit">Generate Project Report</Button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  brands: Array,
})

const brands = ref([]);
const brandFilters = ref({
  brand_id: null,
});

const taskFilters = ref({
  status: null,
  priority: null,
  due_date: null,
});

const projectFilters = ref({
  status: null,
  start_date: null,
  end_date: null,
});

const generateTaskReport = () => {
  window.location.href = route('reports.custom-task', taskFilters.value);
};

const generateProjectReport = () => {
  window.location.href = route('reports.custom-project', projectFilters.value);
};

const generateBrandAnalyticsReport = () => {
  window.location.href = route('reports.export-brand-analytics', brandFilters.value.brand_id);
};
</script>
