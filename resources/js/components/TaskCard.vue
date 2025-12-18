<template>
    <div class="bg-white rounded-lg shadow-lg p-6" :class="{ 'opacity-75': task.status === 'completed' }">
        <div class="flex justify-between items-start mb-2">
            <h2 class="text-xl font-bold" :class="{ 'line-through text-gray-500': task.status === 'completed', 'text-gray-900': task.status !== 'completed' }">
                {{ task.title }}
            </h2>
            <button
                @click="toggleStatus"
                class="w-8 h-8 rounded-full flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :class="task.status === 'completed' ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </button>
        </div>

        <p v-if="task.description" class="text-sm text-gray-600 mb-2 line-clamp-2">
            {{ task.description }}
        </p>

        <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="priorityBadgeClasses">
                {{ getPriorityLabel(task.priority) }}
            </span>
            <span class="px-2 py-1 text-xs font-semibold rounded-full" :class="task.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                {{ task.status === 'completed' ? 'Concluída' : 'Pendente' }}
            </span>
            <span v-if="isOverdue" class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                Atrasada
            </span>
        </div>

        <div v-if="task.due_date" class="text-xs text-gray-500 mb-4 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Vencimento: {{ formatDate(task.due_date) }}
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <a :href="`/tasks/${task.id}`" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Ver</a>
            <a :href="`/tasks/${task.id}/edit`" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Editar</a>
            <button
                @click="deleteTask"
                class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700"
            >
                Excluir
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    task: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['toggle-status', 'delete-task']);

const isOverdue = computed(() => {
    if (!props.task.due_date) return false;
    const dueDate = new Date(props.task.due_date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return dueDate < today && props.task.status !== 'completed';
});

const priorityBadgeClasses = computed(() => {
    const classMap = {
        high: {
            'bg-red-100': true,
            'text-red-800': true,
        },
        medium: {
            'bg-yellow-100': true,
            'text-yellow-800': true,
        },
        low: {
            'bg-blue-100': true,
            'text-blue-800': true,
        },
    };
    return classMap[props.task.priority] || { 'bg-gray-100': true, 'text-gray-800': true };
});

const toggleStatus = () => {
    emit('toggle-status', props.task.id);
};

const deleteTask = () => {
    if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
        emit('delete-task', props.task.id);
    }
};

const getPriorityLabel = (priority) => {
    const labels = {
        high: 'Alta',
        medium: 'Média',
        low: 'Baixa',
    };
    return labels[priority] || priority;
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('pt-BR');
};
</script>
