<template>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Total Tasks -->
        <div class="bg-blue-50 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-600">Total de Tarefas</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-8 h-8 text-blue-600">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-blue-600">{{ stats.total }}</div>
            <div class="text-sm text-gray-500 mt-1">
                {{ totalMessage }}
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-yellow-50 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-600">Tarefas Pendentes</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-8 h-8 text-yellow-600">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-yellow-600">{{ stats.pending }}</div>
            <div class="text-sm text-gray-500 mt-1">
                {{ pendingMessage }}
            </div>
        </div>

        <!-- Tasks Completed -->
        <div class="bg-green-50 p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-600">Tarefas Concluídas</h3>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-8 h-8 text-green-600">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-green-600">{{ stats.completed }}</div>
            <div class="text-sm text-gray-500 mt-1">
                {{ completedMessage }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
import axios from 'axios';

const props = defineProps({
    initialTotal: {
        type: Number,
        default: 0,
    },
    initialPending: {
        type: Number,
        default: 0,
    },
    initialCompleted: {
        type: Number,
        default: 0,
    },
});

const stats = ref({
    total: props.initialTotal,
    pending: props.initialPending,
    completed: props.initialCompleted,
});

const loading = ref(false);
let refreshInterval = null;

const totalMessage = computed(() => {
    return stats.value.total === 0
        ? 'Ainda não há tarefas'
        : `${stats.value.total} tarefa${stats.value.total !== 1 ? 's' : ''}`;
});

const pendingMessage = computed(() => {
    return stats.value.pending === 0
        ? 'Nenhuma pendente'
        : `${stats.value.pending} pendente${stats.value.pending !== 1 ? 's' : ''}`;
});

const completedMessage = computed(() => {
    return stats.value.completed === 0
        ? 'Nenhuma concluída'
        : `${stats.value.completed} concluída${stats.value.completed !== 1 ? 's' : ''}`;
});

const fetchStats = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/dashboard/stats', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        stats.value = {
            total: response.data.total || 0,
            pending: response.data.pending || 0,
            completed: response.data.completed || 0,
        };
    } catch (error) {
        console.error('Erro ao atualizar estatísticas:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    // Update statistics every 30 seconds
    refreshInterval = setInterval(() => {
        fetchStats();
    }, 30000);

    // Also update stats when window gains focus
    window.addEventListener('focus', fetchStats);
});

onBeforeUnmount(() => {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
    window.removeEventListener('focus', fetchStats);
});
</script>
