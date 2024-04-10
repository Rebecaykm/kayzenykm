<x-app-layout title="Plan de Producción">
    <div class="xl:container lg:container md:container sm:container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Plan de Producción
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

        @if(Auth::user()->departaments->contains('name', 'Estampado'))
        <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('production-plan.upload-file') }}" method="post" enctype="multipart/form-data">
                @csrf
                <label class="block text-sm">
                    <div class="relative text-gray-500 focus-within:text-purple-600">
                        <input type="file" name="plan_file" accept=".csv,.xlsx" class="block w-full pr-20 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe" />
                        <button class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Cargar
                        </button>
                    </div>
                </label>
            </form>
        </div>
        @endif

        <form action="{{ route('production-plan.store') }}" method="post">
            @csrf
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <div>
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">
                            Número de Parte
                        </span>
                        <select name="partNumber" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                            <option>Seleccione un Número de Parte</option>
                            @foreach ($parts as $part)
                            <option value="{{ $part->part_number_id }}">{{ $part->wc_name ?? '' }} - {{ $part->number ?? ''}}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Cantidad a Produccir</span>
                        <input name="planQuantity" type="number" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" autocomplete="off" />
                    </label>

                    <label class="block  mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Fecha</span>
                        <input name="date" type="date" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">
                            Turno
                        </span>
                        <select name="shift" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                            <option>Selecciona un Turno</option>
                            @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->abbreviation }} - {{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
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
