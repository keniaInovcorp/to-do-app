<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Minhas Tarefas') }}
            </h2>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Nova Tarefa
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap gap-4">
                    <!-- Search -->
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" placeholder="Buscar tarefas..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ request('search') }}" />
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select name="status" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Todos os Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendentes</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluídas</option>
                        </select>
                    </div>

                    <!-- Priority Filter -->
                    <div>
                        <select name="priority" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all" {{ request('priority') === 'all' ? 'selected' : '' }}>Todas as Prioridades</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Alta</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Média</option>
                            <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Baixa</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Filtrar</button>
                    </div>
                    @if(request()->hasAny(['search', 'status', 'priority']))
                        <div>
                            <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">Limpar</a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Tasks List -->
            @if($tasks->count() > 0)
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($tasks as $task)
                        <div class="bg-white rounded-lg shadow-lg p-6 {{ $task->isCompleted() ? 'opacity-75' : '' }}">
                            <div class="flex justify-between items-start mb-2">
                                <h2 class="text-xl font-bold {{ $task->isCompleted() ? 'line-through text-gray-500' : 'text-gray-900' }}">
                                    {{ $task->title }}
                                </h2>
                                <div class="flex gap-2">
                                    <form action="{{ route('tasks.toggle-status', $task) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 rounded-full flex items-center justify-center {{ $task->isCompleted() ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-200 hover:bg-gray-300' }} text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if($task->description)
                                <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                    {{ $task->description }}
                                </p>
                            @endif

                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $task->priority_color }}-100 text-{{ $task->priority_color }}-800">
                                    @if($task->priority === 'high')
                                        Alta
                                    @elseif($task->priority === 'medium')
                                        Média
                                    @else
                                        Baixa
                                    @endif
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-{{ $task->status_color }}-100 text-{{ $task->status_color }}-800">
                                    {{ $task->status === 'completed' ? 'Concluída' : 'Pendente' }}
                                </span>
                                @if($task->isOverdue())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Atrasada</span>
                                @endif
                            </div>

                            @if($task->due_date)
                                <div class="text-xs text-gray-500 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Vencimento: {{ $task->due_date->format('d/m/Y') }}
                                </div>
                            @endif

                            <div class="flex justify-end gap-2 mt-4">
                                <a href="{{ route('tasks.show', $task) }}" class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Ver</a>
                                <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">Editar</a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700">Excluir</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-lg p-12">
                    <div class="text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-xl font-bold mt-4 text-gray-900">Nenhuma tarefa encontrada</h3>
                        <p class="text-gray-600 mb-4">
                            @if(request()->hasAny(['search', 'status', 'priority']))
                                Tente ajustar os filtros ou criar uma nova tarefa.
                            @else
                                Comece criando sua primeira tarefa!
                            @endif
                        </p>
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">Criar Tarefa</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
