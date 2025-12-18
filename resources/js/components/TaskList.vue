<template>
    <div>
        <!-- Success Message -->
        <transition name="fade">
            <div v-if="successMessage"
                 class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ successMessage }}</span>
            </div>
        </transition>

        <div v-if="loading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <div v-else-if="tasks.length === 0" class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold mt-4 text-gray-900">Nenhuma tarefa encontrada</h3>
                <p class="text-sm text-gray-600 mb-4 mt-2">
                    {{ hasFilters ? 'Tente ajustar os filtros ou criar uma nova tarefa.' : 'Comece criando sua primeira tarefa!' }}
                </p>
                <a :href="createUrl" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Criar Tarefa
                </a>
            </div>
        </div>

        <div v-else class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <TaskCard
                v-for="task in tasks"
                :key="task.id"
                :task="task"
                @toggle-status="handleToggleStatus"
                @delete-task="handleDeleteTask"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import TaskCard from './TaskCard.vue';

const props = defineProps({
    initialTasks: {
        type: Array,
        default: () => [],
    },
    createUrl: {
        type: String,
        default: '/tasks/create',
    },
});

const tasks = ref([...props.initialTasks]);
const loading = ref(false);
const successMessage = ref('');
let successTimeout = null;

const hasFilters = computed(() => {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.has('search') || urlParams.has('status') || urlParams.has('priority');
});

const handleToggleStatus = async (taskId) => {
    try {
        loading.value = true;

        const response = await axios.post(`/tasks/${taskId}/toggle-status`, {}, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        // Update task in list
        const task = tasks.value.find(t => t.id === taskId);
        if (task) {
            const wasCompleted = task.status === 'completed';
            task.status = task.status === 'completed' ? 'pending' : 'completed';

            // Show success message only when completing a task
            if (!wasCompleted && task.status === 'completed') {
                // Clear previous timeout if exists
                if (successTimeout) {
                    clearTimeout(successTimeout);
                }

                successMessage.value = 'Tarefa concluída com sucesso!';

                successTimeout = setTimeout(() => {
                    successMessage.value = '';
                    successTimeout = null;
                }, 5000);
            }
        }
    } catch (error) {
        console.error('Erro ao atualizar status:', error);
        alert('Erro ao atualizar status da tarefa. Por favor, recarregue a página.');
    } finally {
        loading.value = false;
    }
};

const handleDeleteTask = async (taskId) => {
    try {
        loading.value = true;

        await axios.delete(`/tasks/${taskId}`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        });

        // Remove task from list
        tasks.value = tasks.value.filter(t => t.id !== taskId);

        // Reload page to show success message
        window.location.reload();
    } catch (error) {
        console.error('Erro ao excluir tarefa:', error);
        alert('Erro ao excluir tarefa. Por favor, tente novamente.');
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
