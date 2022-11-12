@php
    include_once '../app/Http/Controllers/registros.php';
    $obj = new registros();
@endphp
<table class="w-full whitespace-no-wrap">
    <thead>
        <tr
            class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-800">
            <th class=" header px-4 py-3 sticky">No de parte final</th>
            <th class=" header px-4 py-3 sticky ">Fecha Plan</th>
            <th class=" header px-4 py-3 sticky ">Turno Plan</th>
            <th class=" header px-4 py-3 sticky">Cantidad</th>
            <th class=" header px-4 py-3 sticky">Rango</th>
            <th class=" header px-4 py-3 sticky">Ultima actualización</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        @foreach ($plan as $plans)
            <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3 text-xs text-center bg-teal-300">
                    {{ $plans['K6PROD'] }}
                </td>
                <td class="px-4 py-3 text-xs text-center">
                    {{ $plans['K6DDTE'] }}
                </td>
                <td class="px-4 py-3 text-xs text-center">
                    {{ $plans['K6DSHT'] }}
                </td>
                <td class='px-4 py-3 text-xs text-center'>
                    {{ $plans['K6PFQY'] }}
                </td>
                <td class='px-4 py-3 text-xs text-center'>
                    {{ $plans['K6SDTE'] }}- {{ $plans['K6EDTE'] }}
                </td>
                <td class='px-4 py-3 text-xs text-center'>
                    {{ $plans['K6CCDT'] }}- {{ $plans['K6CCTM'] }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{--
'K6PROD',
'K6WRKC',
'K6SDTE',
'K6EDTE',
'K6DDTE',
'K6DSHT',
'K6PFQY',
'K6CUSR',
'K6CCDT',
'K6CCTM',
'K6FIL1',
'K6FIL2' --}}
