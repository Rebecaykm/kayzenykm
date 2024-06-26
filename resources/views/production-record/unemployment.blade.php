<x-app-layout title="Registro de Paro">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Registro de Paro') }}
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

        <form action="{{ route('prodcution-record.unemployment-production') }}" method="post">
            @csrf
            <div>
                <input name="productionPlananId" type="text" value="{{ $productionPlananId }}" hidden />
                <input name="workcenterId" type="text" value="{{ $workcenter->id }}" hidden />
            </div>
            <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                        {{ __('Paro') }}
                    </span>
                    <select name="unemploymentId" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option value="">{{ __('Seleccione un Tipo de Paro') }}</option>
                        @foreach ($unemployments as $unemployment)
                        <option value="{{ $unemployment->id }}">{{ $unemployment->code ?? '' }} - {{ $unemployment->name ?? '' }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="block mt-2 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Comntario') }}</span>
                    <textarea name="description" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-textarea focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" rows="3" placeholder="Ingresar motivo del paro y/o como se restablecio"></textarea>
                </label>

                <div class="mt-4">
                    <div class="flex justify-end gap-4">
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
