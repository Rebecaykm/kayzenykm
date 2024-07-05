<x-app-layout title="{{ __('Plan de Producción') }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Gráficas de Plan de Producción') }}
        </h2>

        <form action="{{ route('chart.productionPlanChart') }}" method="get">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Fecha Inicio') }}</span>
                    <input name="start" type="date" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>

                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Fecha Final') }}</span>
                    <input name="end" type="date" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <div class="col-span-1 md:col-span-2">
                    <button class="flex items-center justify-between w-full md:w-auto px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <span class="mr-2">{{ __('Buscar')}}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16l2.879-2.879m0 0a3 3 0 104.243-4.242 3 3 0 00-4.243 4.242zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>

        <div class="grid gap-6 mb-8">
            @foreach ($arrayPlan as $line => $lineData)
                @foreach ($lineData as $planDate => $shifts)
                    @foreach ($shifts as $shift => $records)
                        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                            <h4 class="mb-4 font-semibold uppercase text-center text-gray-800 dark:text-gray-300">
                                {{ $line }} - {{ $planDate }} - {{ $shift }}
                            </h4>
                            <canvas id="chart_{{ $line }}_{{ $planDate }}_{{ $shift }}"></canvas>
                        </div>
                    @endforeach
                @endforeach
            @endforeach
        </div>
    </div>

    <script>
        const arrayPlan = @json($arrayPlan ?? null);
    </script>
</x-app-layout>
