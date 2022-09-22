<x-app-layout title="Plan">
    <div class="container">
        <div class="container">
            <div class="container grid px-6 mx-auto gap-y-2">
            </div>
        </div>


        <div class="container">
            @php
                include_once '../app/Http/Controllers/registros.php';
                $obj = new registros();
            @endphp
            <div class="container w-full">
                <div class="flex flex-col h-screen">
                    <div class="flex-grow overflow-auto h-96">
                        <form action="{{ route('planeacion.update') }}" method="POST">
                            @csrf

                            <table class=" table-auto ">
                                <thead>
                                    <tr
                                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800 sticky top-0 ">
                                        <th class=" header px-4 py-3 sticky" rowspan="4">Part No </th>
                                        <th class=" header px-4 py-3 sticky ">

                                        </th>
                                        <th class=" header px-4 py-3 sticky colmde"></th>
                                    </tr>

                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                    @foreach ($plan as $plans)
                                        <tr class="text-gray-700 dark:text-gray-400">
                                            <td class="px-4 py-3 text-xs text-center ">
                                                {{ $plans->IPROD }}
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center">
                                            </td>
                                            <td class='px-4 py-3 text-xs text-center colmde'>
                                            </td>
                                        </tr>
                                        {{-- forecast --}}
                                        <tr class="text-gray-700 dark:text-gray-400">
                                            <td class="px-4 py-3 text-xs text-center">
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center">
                                                Componentes:<br>
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
                                                    <br>
                                                    @foreach ($registro as $valor)
                                                        @if ($i == 1)
                                                            @php
                                                                $sub = $valor;

                                                            @endphp
                                                        @else
                                                            @php
                                                                $clase = $valor;
                                                                $obj->guardar($prod, $sub, $clase);
                                                            @endphp
                                                        @endif
                                                        @php
                                                            $i++;
                                                        @endphp
                                                        {{ $valor }}
                                                    @endforeach
                                                @endforeach
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center colmde">

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div
                        class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                        <span class="flex items-center col-span-3">
                            Show {{ $plan->firstItem() }} - {{ $plan->lastItem() }}
                        </span>
                        <!-- Pagination -->
                        <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
                            {{ $plan->links() }}
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
