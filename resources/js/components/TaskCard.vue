<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow" :class="{ 'opacity-75': task.status === 'completed' }">
        <div class="flex items-center gap-4">
            <!-- Checkbox/Status Button -->
            <button
                @click="toggleStatus"
                class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                :class="task.status === 'completed' ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-gray-200 hover:bg-gray-300 text-gray-700 border-2 border-gray-300'"
            >
                <svg v-if="task.status === 'completed'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </button>

            <!-- Title and Description -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h2 class="text-base font-semibold" :class="{ 'line-through text-gray-500': task.status === 'completed', 'text-gray-900': task.status !== 'completed' }">
                        {{ task.title }}
                    </h2>
                    
                    <!-- Priority Badge -->
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full" :class="priorityBadgeClasses">
                        {{ getPriorityLabel(task.priority) }}
                    </span>
                    
                    <!-- Status Badge -->
                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full" :class="task.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                        {{ task.status === 'completed' ? 'Concluída' : 'Pendente' }}
                    </span>
                    
                    <!-- Overdue Badge -->
                    <span v-if="isOverdue" class="px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                        Atrasada
                    </span>
                </div>
                
                <p v-if="task.description" class="text-sm text-gray-600 mt-1 truncate">
                    {{ task.description }}
                </p>
            </div>

            <!-- Due Date -->
            <div v-if="task.due_date" class="flex-shrink-0 text-sm text-gray-600 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span :class="isOverdue ? 'text-red-600 font-semibold' : ''">{{ formatDate(task.due_date) }}</span>
            </div>

            <!-- Actions -->
            <div class="flex-shrink-0 flex items-center gap-2">
                <button
                    @click="$emit('view-task', task)"
                    class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded transition-colors"
                    title="Ver"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
                <button
                    @click="$emit('edit-task', task)"
                    class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition-colors"
                    title="Editar"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </button>
                <button
                    @click="deleteTask"
                    class="p-2 text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition-colors"
                    title="Excluir"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
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

const emit = defineEmits(['toggle-status', 'delete-task', 'view-task', 'edit-task']);

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
