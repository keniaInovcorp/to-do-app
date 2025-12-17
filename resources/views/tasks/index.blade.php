<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Minhas Tarefas') }}
            </h2>
            <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nova Tarefa
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-wrap gap-4">
                        <!-- Search -->
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search" placeholder="Buscar tarefas..." 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                   value="{{ request('search') }}" />
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Todos os Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendentes</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluídas</option>
                            </select>
                        </div>

                        <!-- Priority Filter -->
                        <div>
                            <select name="priority" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="all" {{ request('priority') === 'all' ? 'selected' : '' }}>Todas as Prioridades</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Alta</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Média</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Baixa</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring ring-blue-300 transition ease-in-out duration-150">
                                Filtrar
                            </button>
                        </div>
                        @if(request()->hasAny(['search', 'status', 'priority']))
                            <div>
                                <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring ring-gray-300 transition ease-in-out duration-150">
                                    Limpar
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Tasks List -->
            @if($tasks->count() > 0)
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($tasks as $task)
                        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg {{ $task->isCompleted() ? 'opacity-75' : '' }}">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h2 class="text-lg font-semibold text-gray-900 {{ $task->isCompleted() ? 'line-through' : '' }}">
                                        {{ $task->title }}
                                    </h2>
                                    <form action="{{ route('tasks.toggle-status', $task) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1 rounded {{ $task->isCompleted() ? 'text-green-600 hover:text-green-700' : 'text-gray-400 hover:text-gray-500' }}">
                                            @if($task->isCompleted())
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                </div>

                                @if($task->description)
                                    <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                                        {{ $task->description }}
                                    </p>
                                @endif

                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800') }}">
                                        @if($task->priority === 'high')
                                            Alta
                                        @elseif($task->priority === 'medium')
                                            Média
                                        @else
                                            Baixa
                                        @endif
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->isCompleted() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $task->status === 'completed' ? 'Concluída' : 'Pendente' }}
                                    </span>
                                    @if($task->isOverdue())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Atrasada
                                        </span>
                                    @endif
                                </div>

                                @if($task->due_date)
                                    <div class="text-xs text-gray-500 mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        Vencimento: {{ $task->due_date->format('d/m/Y') }}
                                    </div>
                                @endif

                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('tasks.show', $task) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        Ver
                                    </a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-xs font-medium text-white bg-blue-600 hover:bg-blue-700">
                                        Editar
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent rounded-md text-xs font-medium text-white bg-red-600 hover:bg-red-700">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $tasks->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-xl font-bold mt-4 text-gray-900">Nenhuma tarefa encontrada</h3>
                        <p class="text-gray-600 mb-4">
                            @if(request()->hasAny(['search', 'status', 'priority']))
                                Tente ajustar os filtros ou criar uma nova tarefa.
                            @else
                                Comece criando sua primeira tarefa!
                            @endif
                        </p>
                        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring ring-blue-300 transition ease-in-out duration-150">
                            Criar Tarefa
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
