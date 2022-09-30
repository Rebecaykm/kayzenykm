<x-app-layout title="Plan">
    <div class="container">
        <div class="container grid px-6 mx-auto gap-y-2">
            <div class="container grid px-6 mx-auto">
                <form method="get" action="{{ route('Structure.index') }}">

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">
                            Selecciona el Projecto
                        </span>
                        <select id='SeProject' name='SeProject' onchange='PCenable()'
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                            <option value=''>---Select---</option>
                            @if ($SEpro != '')
                                <option value={{ $SEpro }} selected="selected">{{ $SEpro }} </option>
                            @endif

                            @foreach ($LWK as $Projec)
                                <option value={{ $Projec->CCCODE }}>{{ $Projec->CCCODE }}//{{ $Projec->CCDESC }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <div class="container grid px-6 mx-auto mt-3 ">
                        <button
                            type='submit'class="px-10 py-4 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Procesar
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <div class="container">
        <div class="containe mt-4">
            <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                Estructura del projecto
            </h2>

            @php
                include_once '../app/Http/Controllers/registros.php';
                $obj = new registros();
            @endphp
            <div class="container w-full">
                <div class="flex flex-col h-screen">
                    <div class="flex-grow overflow-auto h-96">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="text-xs font-semibold tracking-wide text-center text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-200 dark:bg-gray-800">
                                    <th class=" header px-4 py-3 sticky">No de parte final(es) </th>
                                    <th class=" header px-4 py-3 sticky ">
                                        Componentes
                                    </th>
                                    <th class=" header px-4 py-3 sticky colmde">
                                        Planeacion
                                    </th>
                                </tr>
                            </thead>
                        </thead>
                        <tbody
                            class="text-center bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 dark:text-gray-200">
                               @foreach ($plan as $plans)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-xs text-center ">
                                            {{ $plans->IPROD }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center">
                                        </td>
                                        <td class='px-4 py-3 text-xs text-center <'>
                                        </td>
                                    </tr>
                                    {{-- forecast --}}
                                    @php
                                        $cF1 = $obj->buscarF1($plans->IPROD);
                                        $prod = $plans->IPROD;
                                        $sub = '';
                                        $clase = '';
                                    @endphp
                                    @foreach ($cF1 as $registro)
                                        @php
                                            $i = 1;
                                        @endphp
                                        <tr class="text-gray-700 dark:text-gray-400">

                                            @foreach ($registro as $valor)
                                                @if ($i == 1)
                                                    @php
                                                        $sub = $valor;
                                                    @endphp
                                                    <td class="px-4 py-3 text-xs text-center">
                                                    </td>
                                                    <td class="px-4 py-3 text-xs text-center">
                                                        {{ $valor }}
                                                    </td>
                                                @else
                                                    @php
                                                        $clase = $valor;
                                                        $obj->guardar($prod, $sub, $clase);
                                                    @endphp
                                                    <td class="px-4 py-3 text-xs text-center">
                                                        {{ $valor }}
                                                    </td>
                                                @endif
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div
                        class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">

                        <span class="flex items-center col-span-3">
                            Show {{ $plan->firstItem() }} - {{ $plan->lastItem() }}
                        </span>
                        <!-- Pagination -->
                        <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
                            {{ $plan->withQueryString()->links() }}
                        </span>
                    </div>
                    <div
                        class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                        <span class="flex items-center col-span-3">
                            Y - TEC KEYLEX MÉXICO
                        </span>
                        <span class="col-span-2"></span>

                        <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                            <nav aria-label="Table navigation">
                                <ul class="inline-flex items-center">
                                </ul>
                            </nav>
                        </span>
                    </div>
                </div>
            </div>
        </div>


        <script>
            function myFunction(xx) {
                console.log(xx);
            }
        </script>

</x-app-layout>
