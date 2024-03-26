<x-app-layout title="{{ __('Registro de Producción') }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Registro de Producción') }}
        </h2>
        <div class="grid gap-6 mb-8">
            @foreach ($cleanedArrayProduction as $departament => $departamentData)
                @foreach ($departamentData as $noParte => $record)
                    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                        <h4 class="mb-4 font-semibold uppercase text-center text-gray-800 dark:text-gray-300">
                            {{ $departament }} - {{ $noParte }}
                        </h4>
                        <canvas id="chart_{{ $departament }}_{{ $noParte }}"></canvas>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <script>
        const arrayProduction = @json($cleanedArrayProduction ?? null);
    </script>
</x-app-layout>
