<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Tarefa') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </a>
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-3xl font-bold {{ $task->isCompleted() ? 'line-through text-gray-500' : 'text-gray-900' }}">
                        {{ $task->title }}
                    </h1>
                    <form action="{{ route('tasks.toggle-status', $task) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-12 h-12 rounded-full flex items-center justify-center {{ $task->isCompleted() ? 'bg-green-500 hover:bg-green-600' : 'bg-yellow-500 hover:bg-yellow-600' }} text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            @if($task->isCompleted())
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </button>
                    </form>
                </div>

                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-{{ $task->priority_color }}-100 text-{{ $task->priority_color }}-800">
                        @if($task->priority === 'high')
                            Prioridade Alta
                        @elseif($task->priority === 'medium')
                            Prioridade Média
                        @else
                            Prioridade Baixa
                        @endif
                    </span>
                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-{{ $task->status_color }}-100 text-{{ $task->status_color }}-800">
                        {{ $task->status === 'completed' ? 'Concluída' : 'Pendente' }}
                    </span>
                    @if($task->isOverdue())
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Atrasada
                        </span>
                    @endif
                </div>

                @if($task->description)
                    <div class="mb-6">
                        <h3 class="font-semibold text-lg mb-2 text-gray-900">Descrição</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
                    </div>
                @endif

                <div class="border-t border-gray-200 my-6"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    @if($task->due_date)
                        <div>
                            <h3 class="font-semibold mb-2 text-gray-900">Data de Vencimento</h3>
                            <p class="text-gray-600 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $task->due_date->format('d/m/Y') }}
                                @if($task->isOverdue())
                                    <span class="text-red-600 font-semibold ml-2">(Atrasada)</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    <div>
                        <h3 class="font-semibold mb-2 text-gray-900">Criada em</h3>
                        <p class="text-gray-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $task->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('tasks.edit', $task) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 inline-flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Editar
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir esta tarefa?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

