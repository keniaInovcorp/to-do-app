import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';

// Vue Components
import TaskCard from './components/TaskCard.vue';
import TaskFilter from './components/TaskFilter.vue';
import TaskList from './components/TaskList.vue';
import DashboardStats from './components/DashboardStats.vue';

/**
 * Helper function to safely mount Vue components
 * @param {string} elementId - HTML element ID
 * @param {Object} component - Vue component
 * @param {Object} props - Props to pass to the component
 */
function mountComponent(elementId, component, props = {}) {
    const element = document.getElementById(elementId);
    if (!element) return;

    const app = createApp(component, props);
    app.mount(`#${elementId}`);
}

// Mount Vue application when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Mount TaskFilter if element exists
    const taskFilterElement = document.getElementById('task-filter');
    if (taskFilterElement) {
        const clearUrl = taskFilterElement.dataset.clearUrl || '/tasks';
        mountComponent('task-filter', TaskFilter, { clearUrl });
    }

    // Mount TaskList if element exists
    const taskListElement = document.getElementById('task-list');
    if (taskListElement) {
        try {
            const initialTasks = JSON.parse(taskListElement.dataset.initialTasks || '[]');
            const createUrl = taskListElement.dataset.createUrl || '/tasks/create';
            mountComponent('task-list', TaskList, {
                initialTasks,
                createUrl
            });
        } catch (error) {
            console.error('Erro ao parsear tarefas iniciais:', error);
        }
    }

    // Mount DashboardStats if element exists
    const dashboardStatsElement = document.getElementById('dashboard-stats');
    if (dashboardStatsElement) {
        const total = parseInt(dashboardStatsElement.dataset.total || 0);
        const pending = parseInt(dashboardStatsElement.dataset.pending || 0);
        const completed = parseInt(dashboardStatsElement.dataset.completed || 0);

        mountComponent('dashboard-stats', DashboardStats, {
            initialTotal: total,
            initialPending: pending,
            initialCompleted: completed,
        });
    }
});
