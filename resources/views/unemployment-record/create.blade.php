<x-app-layout title="Registro de Paro">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Registro de Paro')}}
        </h2>

        @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">¡Oh no! Algo salió mal.</div>

            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="py-2">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @elseif(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif
        </div>

        <form action="{{ route('unemployment-record.store') }}" method="post">
            @csrf
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                {{ __('Estación de Trabajo') }}
                            </span>
                            <select name="workcenter_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                <option value="">{{ __('Seleccione una Estación') }}</option>
                                @foreach ($workcenters as $workcenter)
                                <option value="{{ $workcenter->id }}">{{ $workcenter->number }} - {{ $workcenter->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                {{ __('Paro') }}
                            </span>
                            <select name="unemployment_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                <option value="">{{ __('Seleccione un Tipo de Paro') }}</option>
                                @foreach ($unemployments as $unemployment)
                                <option value="{{ $unemployment->id }}">{{ $unemployment->code ?? '' }} - {{ $unemployment->name ?? '' }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">{{__('Hora de Inicio')}}</span>
                            <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                                <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" type="datetime-local" id="time_start_input" name="time_start" oninput="validateTime()" />
                                <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">{{__('Hora de Fin')}}</span>
                            <div class="relative text-gray-500 focus-within:text-purple-600 dark:focus-within:text-purple-400">
                                <input class="block w-full pl-10 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" type="datetime-local" id="time_end_input" name="time_end" oninput="validateTime()" />
                                <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Descripción') }}</span>
                    <textarea name="description" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Ingresar motivo del paro"></textarea>
                </label>

                <div class="mt-4">
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('production-plan.index') }}" class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray" type="submit">
                            <span>{{ __('Regresar')}}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                            </svg>
                        </a>
                        <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple" type="submit">
                            <span>{{ __('Guardar')}}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
