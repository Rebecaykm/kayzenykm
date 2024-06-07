<x-app-layout title="{{ __('Plan de Producción') }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            ¡{{ __('Hola') }}, {{ Auth::user()->name }}!
        </h2>
        <div class="grid gap-6 mb-8">
            @foreach ($cleanedArrayProduction as $lineName => $workCenters)
                @foreach ($workCenters as $workCenter => $data)
                    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                        <h4 class="mb-4 font-semibold uppercase text-center text-gray-800 dark:text-gray-300">
                            {{ $lineName }} - {{ $workCenter }}
                        </h4>
                        <canvas id="chart_{{ $lineName }}_{{ $workCenter }}"></canvas>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <script>
        const arrayProduction = @json($cleanedArrayProduction ?? null);
        const colors = [
            'rgba(255, 99, 132, 0.5)',
            'rgba(54, 162, 235, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)',
            'rgba(255, 159, 64, 0.5)'
        ];
    </script>
</x-app-layout>
