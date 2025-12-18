<template>
    <Modal :show="show" title="Criar Nova Tarefa" @update:show="$emit('update:show', $event)">
        <form @submit.prevent="handleSubmit" class="space-y-4">
            <!-- Title -->
            <div>
                <label for="create_title" class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input
                    id="create_title"
                    v-model="formData.title"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Digite o título da tarefa"
                />
            </div>

            <!-- Description -->
            <div>
                <label for="create_description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea
                    id="create_description"
                    v-model="formData.description"
                    rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Digite a descrição da tarefa (opcional)"
                ></textarea>
            </div>

            <!-- Due Date -->
            <div>
                <label for="create_due_date" class="block text-sm font-medium text-gray-700 mb-1">Data de Vencimento</label>
                <input
                    id="create_due_date"
                    v-model="formData.due_date"
                    type="date"
                    :min="minDate"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
            </div>

            <!-- Priority -->
            <div>
                <label for="create_priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridade *</label>
                <select
                    id="create_priority"
                    v-model="formData.priority"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="low">Baixa</option>
                    <option value="medium">Média</option>
                    <option value="high">Alta</option>
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
                {{ loading ? 'Criando...' : 'Criar Tarefa' }}
            </button>
        </template>
    </Modal>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import axios from 'axios';
import Modal from './Modal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:show', 'created']);

const formData = ref({
    title: '',
    description: '',
    due_date: '',
    priority: 'low',
});

const loading = ref(false);
const errorMessage = ref('');

// Get today's date in YYYY-MM-DD format for min date
const minDate = computed(() => {
    const today = new Date();
    return today.toISOString().split('T')[0];
});

// Reset form when modal closes
watch(() => props.show, (newValue) => {
    if (!newValue) {
        formData.value = {
            title: '',
            description: '',
            due_date: '',
            priority: 'low',
        };
        errorMessage.value = '';
    }
});

const handleSubmit = async () => {
    try {
        loading.value = true;
        errorMessage.value = '';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        const response = await axios.post('/tasks', formData.value, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });

        emit('created', response.data.task);
        emit('update:show', false);
    } catch (error) {
        console.error('Erro ao criar tarefa:', error);
        if (error.response?.data?.errors) {
            // Handle validation errors
            const errors = error.response.data.errors;
            errorMessage.value = Object.values(errors).flat().join(', ');
        } else {
            errorMessage.value = error.response?.data?.message || 'Erro ao criar tarefa. Por favor, tente novamente.';
        }
    } finally {
        loading.value = false;
    }
};
</script>

