<template>
    <Modal :show="show" title="Editar Tarefa" @update:show="$emit('update:show', $event)">
        <form v-if="task" @submit.prevent="handleSubmit" class="space-y-4">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input
                    id="title"
                    v-model="formData.title"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea
                    id="description"
                    v-model="formData.description"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
            </div>

            <!-- Due Date -->
            <div>
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Data de Vencimento</label>
                <input
                    id="due_date"
                    v-model="formData.due_date"
                    type="date"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <!-- Priority -->
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridade</label>
                <select
                    id="priority"
                    v-model="formData.priority"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="low">Baixa</option>
                    <option value="medium">Média</option>
                    <option value="high">Alta</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select
                    id="status"
                    v-model="formData.status"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="pending">Pendente</option>
                    <option value="completed">Concluída</option>
                </select>
            </div>

            <div v-if="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ errorMessage }}
            </div>
        </form>

        <template #footer>
            <button
                type="button"
                @click="$emit('update:show', false)"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors"
            >
                Cancelar
            </button>
            <button
                type="submit"
                @click="handleSubmit"
                :disabled="loading"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
                {{ loading ? 'Salvando...' : 'Salvar' }}
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
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

const emit = defineEmits(['update:show', 'updated']);

const formData = ref({
    title: '',
    description: '',
    due_date: '',
    priority: 'low',
    status: 'pending',
});

const loading = ref(false);
const errorMessage = ref('');

// Watch for task changes and update form
watch(() => props.task, (newTask) => {
    if (newTask) {
        formData.value = {
            title: newTask.title || '',
            description: newTask.description || '',
            due_date: newTask.due_date ? newTask.due_date.split('T')[0] : '',
            priority: newTask.priority || 'low',
            status: newTask.status || 'pending',
        };
    }
}, { immediate: true });

const handleSubmit = async () => {
    if (!props.task) return;

    try {
        loading.value = true;
        errorMessage.value = '';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        // Laravel expects PUT method to be sent as POST with _method field
        const data = {
            ...formData.value,
            _method: 'PUT',
        };
        
        const response = await axios.post(`/tasks/${props.task.id}`, data, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        emit('updated', response.data.task);
        emit('update:show', false);
    } catch (error) {
        console.error('Erro ao atualizar tarefa:', error);
        errorMessage.value = error.response?.data?.message || 'Erro ao atualizar tarefa. Por favor, tente novamente.';
    } finally {
        loading.value = false;
    }
};
</script>

