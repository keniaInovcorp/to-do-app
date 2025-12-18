<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold mb-4">Bem-vindo ao To-Do App!</h1>
                    <p class="text-lg mb-6 text-gray-600">Gerencie suas tarefas de forma simples e eficiente.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 p-6 rounded-lg shadow">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-600">Total de Tarefas</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-8 h-8 text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-blue-600">{{ $totalTasks }}</div>
                            <div class="text-sm text-gray-500 mt-1">
                                @if($totalTasks === 0)
                                    Ainda não há tarefas
                                @else
                                    {{ $totalTasks }} tarefa{{ $totalTasks !== 1 ? 's' : '' }}
                                @endif
                            </div>
                        </div>

                        <div class="bg-yellow-50 p-6 rounded-lg shadow">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-600">Tarefas Pendentes</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-8 h-8 text-yellow-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-yellow-600">{{ $pendingTasks }}</div>
                            <div class="text-sm text-gray-500 mt-1">
                                @if($pendingTasks === 0)
                                    Nenhuma pendente
                                @else
                                    {{ $pendingTasks }} pendente{{ $pendingTasks !== 1 ? 's' : '' }}
                                @endif
                            </div>
                        </div>

                        <div class="bg-green-50 p-6 rounded-lg shadow">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium text-gray-600">Tarefas Concluídas</h3>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-8 h-8 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-green-600">{{ $completedTasks }}</div>
                            <div class="text-sm text-gray-500 mt-1">
                                @if($completedTasks === 0)
                                    Nenhuma concluída
                                @else
                                    {{ $completedTasks }} concluída{{ $completedTasks !== 1 ? 's' : '' }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Ver Minhas Tarefas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
