<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Nova Tarefa') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring ring-gray-300 transition ease-in-out duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf

                    <!-- Title -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Título *
                        </label>
                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @else border-gray-300 @enderror"
                               placeholder="Ex: Comprar mantimentos"
                               required />
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Descrição
                        </label>
                        <textarea name="description"
                                  class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @else border-gray-300 @enderror"
                                  rows="4"
                                  placeholder="Adicione uma descrição detalhada...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Data de Vencimento
                        </label>
                        <div class="relative">
                            <input type="date"
                                   name="due_date"
                                   value="{{ old('due_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('due_date') border-red-500 @else border-gray-300 @enderror" />
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-3 top-3 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Prioridade *
                        </label>
                        <select name="priority"
                                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @else border-gray-300 @enderror"
                                required>
                            <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>
                                Baixa
                            </option>
                            <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>
                                Média
                            </option>
                            <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>
                                Alta
                            </option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('tasks.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Cancelar</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Criar Tarefa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

