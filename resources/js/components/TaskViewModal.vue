<template>
    <Modal :show="show" title="Detalhes da Tarefa" @update:show="$emit('update:show', $event)">
        <div v-if="task">
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-4" :class="{ 'line-through text-gray-500': task.status === 'completed', 'text-gray-900': task.status !== 'completed' }">
                    {{ task.title }}
                </h2>

                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full" :class="priorityBadgeClasses">
                        {{ getPriorityLabel(task.priority) }}
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full" :class="task.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                        {{ task.status === 'completed' ? 'Concluída' : 'Pendente' }}
                    </span>
                    <span v-if="isOverdue" class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                        Atrasada
                    </span>
                </div>

                <div v-if="task.description" class="mb-6">
                    <h3 class="font-semibold text-lg mb-2 text-gray-900">Descrição</h3>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ task.description }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-if="task.due_date">
                        <h3 class="font-semibold mb-2 text-gray-900">Data de Vencimento</h3>
                        <p class="text-gray-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ formatDate(task.due_date) }}
                            <span v-if="isOverdue" class="text-red-600 font-semibold ml-2">(Atrasada)</span>
                        </p>
                    </div>

                    <div>
                        <h3 class="font-semibold mb-2 text-gray-900">Criada em</h3>
                        <p class="text-gray-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ formatDateTime(task.created_at) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <template #footer>
            <button
                @click="$emit('update:show', false)"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors"
            >
                Fechar
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { computed } from 'vue';
import Modal from './Modal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    task: {
        type: Object,
        default: null,
    },
});

defineEmits(['update:show']);

const isOverdue = computed(() => {
    if (!props.task?.due_date) return false;
    const dueDate = new Date(props.task.due_date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return dueDate < today && props.task.status !== 'completed';
});

const priorityBadgeClasses = computed(() => {
    if (!props.task) return {};
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

const formatDateTime = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleString('pt-BR');
};
</script>

