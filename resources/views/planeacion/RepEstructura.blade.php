@php
    include_once '../app/Http/Controllers/registros.php';
    $obj = new registros();

@endphp
<table class="w-full whitespace-no-wrap">
    <thead>
        <tr
            class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-800">
            <th class=" header px-4 py-3 sticky">No de parte final</th>
            <th class=" header px-4 py-3 sticky">Final compartido</th>
            <th class=" header px-4 py-3 sticky ">
                Componentes
            </th>
            <th class=" header px-4 py-3 sticky ">
                Planear
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
        @foreach ($plan as $plans)
            <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3 text-xs text-center bg-teal-300">
                    {{ $plans['IPROD'] }}
                </td>
                <td class="px-4 py-3 text-xs text-center">
                </td>
                <td class="px-4 py-3 text-xs text-center">
                </td>
                <td class='px-4 py-3 text-xs text-center'>
                </td>
            </tr>
            @php
                $cF1 = $obj->cargarestructura($plans['IPROD']);
            @endphp
            @foreach ($cF1 as $registro)
                <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3 text-xs text-center">
                    </td>
                    <td class="px-4 py-3 text-xs text-center">
                        @php
                            $Final = $obj->cargarF1($registro->Componente);
                        @endphp
                        @foreach ($Final as $Finales)
                            {{ $Finales['final'] }}<br>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-xs text-center ">
                        {{ $registro->Componente }}
                    </td>
                    <td class="px-4 py-3 text-xs text-center">
                        <div class="flex justify-center">
                            @php
                                $namenA = strtr($registro->Componente, ' ', '_');
                            @endphp


                        </div>
                    </td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
