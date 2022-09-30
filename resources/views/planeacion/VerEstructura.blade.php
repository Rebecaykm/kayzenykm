<x-app-layout title="Plan">
    <div class="xl:container lg:container md:container sm:container">
        <div class="container grid px-6 mx-auto gap-y-2">
            <div class="container grid px-6 mx-auto">
                <form method="get" action="{{ route('ShowStructure.index') }}">

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
    <form action="{{ route('ShowStructure.update') }}" method="POST">
        <input type="hidden" name="SeProject" id="SeProject" value={{ $SEpro }}>
        @csrf
        <div class="container">
            <div class="containe mt-4">
                <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Estructura del projecto
                </h2>
                <div class="flex justify-center">

                    <button type="submit"
                        class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                        <span class="mr-2">Actualizar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                    </button>
                </div>


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
                                        <th class=" header px-4 py-3 sticky">No de parte final</th>
                                        <th class=" header px-4 py-3 sticky">Final compartido<< /th>
                                        <th class=" header px-4 py-3 sticky ">
                                            Componentes
                                        </th>
                                        <th class=" header px-4 py-3 sticky ">
                                            Activo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                    @foreach ($plan as $plans)
                                        <tr class="text-gray-700 dark:text-gray-400">
                                            <td class="px-4 py-3 text-xs text-center bg-teal-300">
                                                {{ $plans->IPROD }}
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center">
                                            </td>
                                            <td class="px-4 py-3 text-xs text-center">
                                            </td>
                                            <td class='px-4 py-3 text-xs text-center'>
                                            </td>
                                        </tr>
                                        {{-- forecast --}}
                                        @php
                                            $cF1 = $obj->cargar($plans->IPROD);

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
                                                        {{ $Finales->Final }}<br>
                                                    @endforeach
                                                </td>

                                                <td class="px-4 py-3 text-xs text-center ">
                                                    {{ $registro->Componente }}
                                                </td>
                                                <td class="px-4 py-3 text-xs text-center">
                                                    <div class="flex justify-center">
                                                        <div>
                                                            @php
                                                                $namen = 'CH-' . $registro->Componente;
                                                            @endphp
                                                            @if ($registro->Activo != 1)
                                                                <div class="flex mt-6 text-sm">
                                                                    <label class="flex items-center dark:text-gray-400">
                                                                        <input name={{ $namen }}
                                                                            id={{ $namen }}
                                                                            onclick=" myFunction(this.id)"
                                                                            type="checkbox"
                                                                            class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                                                                            checked />
                                                                        <span class="ml-2">
                                                                            No Planear
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @else
                                                                <div class="flex mt-6 text-sm">
                                                                    <label class="flex items-center dark:text-gray-400">
                                                                        <input name={{ $namen }}
                                                                            id={{ $namen }} type="checkbox"
                                                                            onclick=" myFunction(this.id)"
                                                                            class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                                                                        <span class="ml-2">
                                                                            No Planear
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

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
    </form>

    <script>
        function myFunction(xx) {
            console.log(xx, document.getElementById(xx).value);
            if (document.getElementById(xx).checked == true) {
                document.getElementById(xx).value = 1;
            } else {
                document.getElementById(xx).value = 0;
            }
            console.log(xx, document.getElementById(xx).value);


        }
    </script>


</x-app-layout>
