<script setup lang="ts">
import {computed, ref, watch} from 'vue';
import {Link, router} from '@inertiajs/vue3';
import {Button} from '@/Components/ui/button';
import {Input} from '@/Components/ui/input';
import {Select, SelectContent, SelectItem, SelectTrigger, SelectValue} from '@/Components/ui/select';
import {Table, TableBody, TableCell, TableHead, TableHeader, TableRow} from '@/Components/ui/table';
import {Checkbox} from '@/Components/ui/checkbox';
import {Badge} from '@/Components/ui/badge';
import {DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger} from '@/Components/ui/dropdown-menu';
import {IconPlus, IconDots} from '@tabler/icons-vue';
import Pagination from '@/Components/Pagination.vue';
import PageHeader from "@/Components/PageHeader.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import EmptyState from "@/Components/EmptyState.vue";
import { IconFolders } from '@tabler/icons-vue';

interface Project {
  id: number;
  name: string;
  status: 'planned' | 'in_progress' | 'completed';
  priority: 'low' | 'medium' | 'high';
  due_date: string;
}

interface Filters {
  search?: string;
  status?: string;
  sort?: string;
}

const props = defineProps<{
  projects: {
    data: Project[];
  };
  filters?: Filters;
}>();

const search = ref(props.filters?.search ?? '');
const statusFilter = ref(props.filters?.status ?? 'all');
const sortBy = ref(props.filters?.sort ?? 'name');
const selectedProjects = ref<number[]>([]);
const selectAll = ref(false);

watch([search, statusFilter, sortBy], () => {
  router.get(route('projects.index'), {
    search: search.value,
    status: statusFilter.value,
    sort: sortBy.value,
  }, {preserveState: true});
});

const pageActions = [
  {
    label: 'New Project',
    icon: IconPlus,
    href: route('projects.create')
  }
];

const breadcrumbs = [
  {
    label: 'Dashboard',
    href: route('dashboard')
  },
  {
    label: 'Projects'
  }
];

const hasProjects = computed(() => {
  return props.projects.data && props.projects.data.length > 0;
});

// Add this computed property to check if we're filtering
const isFiltering = computed(() => {
  return search.value || statusFilter.value !== 'all' || sortBy.value !== 'name';
});

watch([search, statusFilter, sortBy], () => {
  router.get(route('projects.index'), {
    search: search.value,
    status: statusFilter.value,
    sort: sortBy.value,
  }, {
    preserveState: true,
    preserveScroll: true
  });
});

const toggleSelection = (id: number) => {
  if (selectedProjects.value.includes(id)) {
    selectedProjects.value = selectedProjects.value.filter(projectId => projectId !== id);
  } else {
    selectedProjects.value.push(id);
  }
};

const deleteSelectedProjects = () => {
  if (!confirm('Are you sure you want to delete the selected projects?')) return;

  router.delete(route('projects.destroy-many'), {
    data: {ids: selectedProjects.value},
    onSuccess: () => {
      selectedProjects.value = [];
    },
  });
};

const statusVariant = (status: Project['status']) => {
  switch (status) {
    case 'planned':
      return 'secondary';
    case 'in_progress':
      return 'primary';
    case 'completed':
      return 'success';
    default:
      return 'default';
  }
};

const priorityVariant = (priority: Project['priority']) => {
  switch (priority) {
    case 'low':
      return 'secondary';
    case 'medium':
      return 'primary';
    case 'high':
      return 'destructive';
    default:
      return 'default';
  }
};

const formatDate = (date: string) => {
  if (!date) return '—';
  return new Date(date).toLocaleDateString();
};

// Methods that need to be implemented
const editProject = (project: Project) => {
  router.get(route('projects.edit', project.id));
};

const deleteProject = (project: Project) => {
  if (!confirm('Are you sure you want to delete this project?')) return;

  router.delete(route('projects.destroy', project.id));
};
</script>

<template>
  <AppLayout title="Projects">
    <div class="space-y-6">
      <PageHeader
        title="Projects"
        description="Manage and track all your organization's projects"
        :breadcrumbs="breadcrumbs"
        :actions="pageActions"
      />

      <!-- Empty States -->
      <template v-if="!hasProjects">
        <!-- No projects at all -->
        <EmptyState
          v-if="!isFiltering"
          :icon="IconFolders"
          title="No projects yet"
          description="Get started by creating your first project. Projects help you organize and track your work effectively."
          buttonLabel="New Project"
          :buttonLink="route('projects.create')"
          :is-modal="true"
        />

        <!-- No results from filtering -->
        <EmptyState
          v-else
          icon="IconFolders"
          title="No matching projects"
          description="Try adjusting your search or filter criteria to find what you're looking for."
          buttonLabel="Clear filters"
          :buttonLink="route('projects.index')"
        />
      </template>

      <div
        v-else
        class="rounded-md border">

        <!-- Filters -->
        <div class="flex items-center gap-4">
          <Input v-model="search" placeholder="Search projects..." class="max-w-sm"/>
          <Select v-model="statusFilter">
            <SelectTrigger class="w-[180px]">
              <SelectValue placeholder="Status"/>
            </SelectTrigger>

            <SelectContent>
              <SelectItem value="all">All</SelectItem>
              <SelectItem value="planned">Planned</SelectItem>
              <SelectItem value="in_progress">In Progress</SelectItem>
              <SelectItem value="completed">Completed</SelectItem>
            </SelectContent>
          </Select>

          <Select v-model="sortBy">
            <SelectTrigger class="w-[180px]">
              <SelectValue placeholder="Sort by"/>
            </SelectTrigger>

            <SelectContent>
              <SelectItem value="name">Name</SelectItem>
              <SelectItem value="due_date">Due Date</SelectItem>
              <SelectItem value="priority">Priority</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <!-- Projects Table -->
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead class="w-[100px]">
                <Checkbox v-model:checked="selectAll"/>
              </TableHead>
              <TableHead>Name</TableHead>
              <TableHead>Status</TableHead>
              <TableHead>Priority</TableHead>
              <TableHead>Due Date</TableHead>
              <TableHead>Actions</TableHead>
            </TableRow>
          </TableHeader>

          <TableBody>
            <TableRow v-for="project in projects.data" :key="project.id">
              <TableCell>
                <Checkbox :checked="selectedProjects.includes(project.id)"
                          @update:checked="toggleSelection(project.id)"/>
              </TableCell>
              <TableCell>{{ project.name }}</TableCell>
              <TableCell>
                <Badge :variant="statusVariant(project.status)">
                  {{ project.status }}
                </Badge>
              </TableCell>
              <TableCell>
                <Badge :variant="priorityVariant(project.priority)"/>
                {{ project.priority }}
              </TableCell>
              <TableCell>{{ formatDate(project.due_date) }}</TableCell>
              <TableCell>
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="sm" class="w-8 h-8 p-0">
                      <IconDots class="w-4 h-4"/>
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent>
                    <DropdownMenuItem @click="editProject(project)">
                      Edit
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="deleteProject(project)" class="text-red-600">
                      Delete
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>

        <!-- Bulk Actions -->
        <div v-if="selectedProjects.length > 0" class="flex items-center gap-4">
          <Button variant="destructive" @click="deleteSelectedProjects">
            Delete Selected
          </Button>
        </div>

        <!-- Pagination -->
        <Pagination :pagination="projects"/>

      </div>
    </div>
  </AppLayout>
</template>
