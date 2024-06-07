<x-app-layout title="{{ __('Plan de Producción') }}">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Plan de Producción') }}
        </h2>
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
