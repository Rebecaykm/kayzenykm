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

        <form action="{{ route('unemployment-record.store') }}" method="post">
            @csrf
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <label class="block mt-2 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                        {{ __('Estación de Trabajo') }}
                    </span>
                    <select name="workcenter_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option>{{ __('Seleccione una Estación') }}</option>
                        @foreach ($workcenters as $workcenter)
                        <option value="{{ $workcenter->id }}">{{ $workcenter->number }} - {{ $workcenter->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block mt-2 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                        {{ __('Paro') }}
                    </span>
                    <select name="unemployment_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option>{{ __('Seleccione una Estación') }}</option>
                        @foreach ($unemployments as $unemployment)
                        <option value="{{ $unemployment->id }}">{{ $unemployment->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block mt-2 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Hora Inicio') }}</span>
                    <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="time_start" type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}" max="{{ now()->format('Y-m-d\TH:i') }}" value="{{ now()->format('Y-m-d\TH:i') }}" autocomplete="off" />
                </label>
                <label class="block mt-2 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Hora Fin') }}</span>
                    <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" name="time_end" type="datetime-local" min="{{ now()->format('Y-m-d\TH:i') }}" max="{{ now()->addDay(2)->format('Y-m-d\TH:i') }}" value="{{ now()->format('Y-m-d\TH:i') }}"  autocomplete="off" />
                </label>
                <div class="flex justify-end mt-4 gap-4">
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
        </form>
    </div>
</x-app-layout>
