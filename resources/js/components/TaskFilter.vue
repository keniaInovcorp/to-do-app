<template>
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
        <form @submit.prevent="applyFilters" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input
                    v-model="filters.search"
                    type="text"
                    placeholder="Buscar tarefas..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
            </div>

            <div>
                <select v-model="filters.status" class="px-4 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="min-width: 200px;">
                    <option value="all">Todos os Status</option>
                    <option value="pending">Pendentes</option>
                    <option value="completed">Concluídas</option>
                </select>
            </div>

            <div>
                <select v-model="filters.priority" class="px-4 py-2 pr-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" style="min-width: 220px;">
                    <option value="all">Todas as Prioridades</option>
                    <option value="high">Alta</option>
                    <option value="medium">Média</option>
                    <option value="low">Baixa</option>
                </select>
            </div>

            <div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Filtrar</button>
            </div>
            <div v-if="hasActiveFilters">
                <a :href="clearUrl" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">Limpar</a>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    clearUrl: {
        type: String,
        default: '/tasks',
    },
});

const getUrlParam = (name) => {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
};

const filters = ref({
    search: getUrlParam('search') || '',
    status: getUrlParam('status') || 'all',
    priority: getUrlParam('priority') || 'all',
});

const hasActiveFilters = computed(() => {
    return filters.value.search ||
           filters.value.status !== 'all' ||
           filters.value.priority !== 'all';
});

const applyFilters = () => {
    const params = new URLSearchParams();

    if (filters.value.search) {
        params.append('search', filters.value.search);
    }
    if (filters.value.status !== 'all') {
        params.append('status', filters.value.status);
    }
    if (filters.value.priority !== 'all') {
        params.append('priority', filters.value.priority);
    }

    window.location.href = `/tasks?${params.toString()}`;
};
</script>
