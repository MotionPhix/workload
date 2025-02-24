<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Badge } from '@/Components/ui/badge';
import { IconPlus, IconDots } from '@tabler/icons-vue';

const props = defineProps({
  project: Object,
  tasks: Array,
});

const search = ref('');
const statusFilter = ref('all');
const sortBy = ref('due_date');
const isTaskFormOpen = ref(false);
const isEditingTask = ref(false);
const taskForm = ref({
  id: null,
  title: '',
  description: '',
  status: 'todo',
  priority: 'medium',
  due_date: '',
});

const progressPercentage = computed(() => {
  const totalTasks = props.tasks.length;
  const completedTasks = props.tasks.filter((task) => task.status === 'done').length;
  return totalTasks ? Math.round((completedTasks / totalTasks) * 100) : 0;
});

const filteredTasks = computed(() => {
  return props.tasks
    .filter((task) => {
      const matchesSearch = task.title.toLowerCase().includes(search.value.toLowerCase());
      const matchesStatus = statusFilter.value === 'all' || task.status === statusFilter.value;
      return matchesSearch && matchesStatus;
    })
    .sort((a, b) => {
      if (sortBy.value === 'due_date') {
        return new Date(a.due_date) - new Date(b.due_date);
      } else if (sortBy.value === 'priority') {
        const priorityOrder = { low: 1, medium: 2, high: 3 };
        return priorityOrder[b.priority] - priorityOrder[a.priority];
      }
      return 0;
    });
});

const openTaskForm = (task = null) => {
  if (task) {
    taskForm.value = { ...task };
    isEditingTask.value = true;
  } else {
    taskForm.value = {
      id: null,
      title: '',
      description: '',
      status: 'todo',
      priority: 'medium',
      due_date: '',
    };
    isEditingTask.value = false;
  }
  isTaskFormOpen.value = true;
};

const submitTaskForm = () => {
  const url = isEditingTask.value
    ? route('tasks.update', taskForm.value.id)
    : route('tasks.store');
  const method = isEditingTask.value ? 'put' : 'post';

  router[method](url, {
    ...taskForm.value,
    project_id: props.project.id,
  }, {
    onSuccess: () => {
      isTaskFormOpen.value = false;
    },
  });
};

const deleteTask = (task) => {
  if (confirm('Are you sure you want to delete this task?')) {
    router.delete(route('tasks.destroy', task.id));
  }
};

const statusVariant = (status) => {
  switch (status) {
    case 'todo': return 'secondary';
    case 'in_progress': return 'primary';
    case 'done': return 'success';
    default: return 'default';
  }
};

const priorityVariant = (priority) => {
  switch (priority) {
    case 'low': return 'secondary';
    case 'medium': return 'primary';
    case 'high': return 'destructive';
    default: return 'default';
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};
</script>

<template>
  <div class="space-y-6">
    <!-- Project Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold">{{ project.name }}</h1>
        <p class="text-muted-foreground">{{ project.description }}</p>
      </div>
      <div class="flex items-center gap-4">
        <Button variant="outline" @click="openTaskForm">
          <IconPlus class="mr-2 h-4 w-4" />
          Add Task
        </Button>
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
              <IconDots class="h-4 w-4" />
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent>
            <DropdownMenuItem @click="editProject(project)">
              Edit Project
            </DropdownMenuItem>
            <DropdownMenuItem @click="deleteProject(project)" class="text-red-600">
              Delete Project
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </div>

    <!-- Progress Bar -->
    <div class="space-y-2">
      <div class="flex items-center justify-between">
        <span class="text-sm font-medium">Progress</span>
        <span class="text-sm text-muted-foreground">{{ progressPercentage }}%</span>
      </div>
      <Progress :value="progressPercentage" class="h-2" />
    </div>

    <!-- Task Filters -->
    <div class="flex items-center gap-4">
      <Input v-model="search" placeholder="Search tasks..." class="max-w-sm" />
      <Select v-model="statusFilter">
        <SelectTrigger class="w-[180px]">
          <SelectValue placeholder="Status" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="all">All</SelectItem>
          <SelectItem value="todo">To Do</SelectItem>
          <SelectItem value="in_progress">In Progress</SelectItem>
          <SelectItem value="done">Done</SelectItem>
        </SelectContent>
      </Select>
      <Select v-model="sortBy">
        <SelectTrigger class="w-[180px]">
          <SelectValue placeholder="Sort by" />
        </SelectTrigger>
        <SelectContent>
          <SelectItem value="due_date">Due Date</SelectItem>
          <SelectItem value="priority">Priority</SelectItem>
        </SelectContent>
      </Select>
    </div>

    <!-- Task List -->
    <Table>
      <TableHeader>
        <TableRow>
          <TableHead>Title</TableHead>
          <TableHead>Status</TableHead>
          <TableHead>Priority</TableHead>
          <TableHead>Due Date</TableHead>
          <TableHead>Actions</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="task in filteredTasks" :key="task.id">
          <TableCell>{{ task.title }}</TableCell>
          <TableCell>
            <Badge :variant="statusVariant(task.status)">
              {{ task.status }}
            </Badge>
          </TableCell>
          <TableCell>
            <Badge :variant="priorityVariant(task.priority)">
              {{ task.priority }}
            </Badge>
          </TableCell>
          <TableCell>{{ formatDate(task.due_date) }}</TableCell>
          <TableCell>
            <DropdownMenu>
              <DropdownMenuTrigger as-child>
                <Button variant="ghost" size="sm" class="h-8 w-8 p-0">
                  <IconDots class="h-4 w-4" />
                </Button>
              </DropdownMenuTrigger>
              <DropdownMenuContent>
                <DropdownMenuItem @click="openTaskForm(task)">
                  Edit
                </DropdownMenuItem>
                <DropdownMenuItem @click="deleteTask(task)" class="text-red-600">
                  Delete
                </DropdownMenuItem>
              </DropdownMenuContent>
            </DropdownMenu>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>

    <!-- Task Form Drawer -->
    <Drawer :open="isTaskFormOpen" @update:open="isTaskFormOpen = $event">
      <DrawerContent>
        <DrawerHeader>
          <DrawerTitle>{{ isEditingTask ? 'Edit Task' : 'Add Task' }}</DrawerTitle>
          <DrawerDescription>
            {{ isEditingTask ? 'Update the task details.' : 'Add a new task to the project.' }}
          </DrawerDescription>
        </DrawerHeader>
        <form @submit.prevent="submitTaskForm">
          <div class="space-y-4 p-4">
            <Input v-model="taskForm.title" placeholder="Task Title" required />
            <Textarea v-model="taskForm.description" placeholder="Task Description" />
            <Select v-model="taskForm.status">
              <SelectTrigger>
                <SelectValue placeholder="Status" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="todo">To Do</SelectItem>
                <SelectItem value="in_progress">In Progress</SelectItem>
                <SelectItem value="done">Done</SelectItem>
              </SelectContent>
            </Select>
            <Select v-model="taskForm.priority">
              <SelectTrigger>
                <SelectValue placeholder="Priority" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="low">Low</SelectItem>
                <SelectItem value="medium">Medium</SelectItem>
                <SelectItem value="high">High</SelectItem>
              </SelectContent>
            </Select>
            <Input v-model="taskForm.due_date" type="date" />
          </div>
          <DrawerFooter>
            <Button type="submit">Save</Button>
            <Button variant="outline" @click="isTaskFormOpen = false">Cancel</Button>
          </DrawerFooter>
        </form>
      </DrawerContent>
    </Drawer>
  </div>
</template>
