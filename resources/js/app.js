import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import { ModalLink, renderApp } from '@inertiaui/modal-vue'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// UI Components
import {Calendar} from '@/Components/ui/calendar'
import {Button} from '@/Components/ui/button'
import {Label} from '@/Components/ui/label'
import {Progress} from '@/Components/ui/progress'
import {Badge} from '@/Components/ui/badge'
import {Textarea} from '@/Components/ui/textarea'
import {Checkbox} from "@/Components/ui/checkbox";
import {Input} from '@/Components/ui/input'
import FormField from '@/Components/FormField.vue'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow
} from '@/Components/ui/table'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuSeparator,
  DropdownMenuLabel,
  DropdownMenuShortcut,
  DropdownMenuItem,
  DropdownMenuTrigger
} from '@/Components/ui/dropdown-menu'
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle
} from '@/Components/ui/card'
import {
  NumberField,
  NumberFieldContent,
  NumberFieldDecrement,
  NumberFieldIncrement,
  NumberFieldInput,
} from '@/Components/ui/number-field'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
  SelectSeparator,
  SelectLabel,
  SelectGroup
} from '@/Components/ui/select';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({el, App, props, plugin}) {
    return createApp({ render: renderApp(App, props) })
      .use(plugin)
      .use(ZiggyVue)

      // components for the ui globally imported
      .component('FormField', FormField)
      .component('Calendar', Calendar)
      .component('ModalLink', ModalLink)
      .component('Button', Button)
      .component('Label', Label)
      .component('Textarea', Textarea)
      .component('Checkbox', Checkbox)
      .component('Input', Input)
      .component('Progress', Progress)
      .component('Badge', Badge)
      .component('Table', Table)
      .component('TableBody', TableBody)
      .component('TableCell', TableCell)
      .component('TableHead', TableHead)
      .component('TableHeader', TableHeader)
      .component('TableRow', TableRow)
      .component('DropdownMenu', DropdownMenu)
      .component('DropdownMenuContent', DropdownMenuContent)
      .component('DropdownMenuSeparator', DropdownMenuSeparator)
      .component('DropdownMenuShortcut', DropdownMenuShortcut)
      .component('DropdownMenuLabel', DropdownMenuLabel)
      .component('DropdownMenuItem', DropdownMenuItem)
      .component('DropdownMenuTrigger', DropdownMenuTrigger)
      .component('Card', Card)
      .component('CardContent', CardContent)
      .component('CardDescription', CardDescription)
      .component('CardFooter', CardFooter)
      .component('CardHeader', CardHeader)
      .component('CardTitle', CardTitle)
      .component('NumberField', NumberField)
      .component('NumberFieldContent', NumberFieldContent)
      .component('NumberFieldDecrement', NumberFieldDecrement)
      .component('NumberFieldIncrement', NumberFieldIncrement)
      .component('NumberFieldInput', NumberFieldInput)
      .component('Select', Select)
      .component('SelectItem', SelectItem)
      .component('SelectTrigger', SelectTrigger)
      .component('SelectValue', SelectValue)
      .component('SelectGroup', SelectGroup)
      .component('SelectContent', SelectContent)
      .component('SelectSeparator', SelectSeparator)
      .component('SelectLabel', SelectLabel)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
