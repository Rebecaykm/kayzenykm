<x-app-layout title="Plan">
    <div class="container">
        <div class="container">
            <div class="container grid px-6 mx-auto gap-y-2">


                <form method="post" action="{{ route('planeacion.create') }}">
                    <h2>
                        Partes componentes
                    </h2>
                    @csrf
                    <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                        <input type="hidden" name="SeTP" id="SeTP" value={{ $tp }}>
                        <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                        <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                        <label class="block text-sm ">
                            <span class="text-gray-700 dark:text-gray-400 text-xs">Dias</span>
                            <input id="dias" name="dias" type="number" max="7" min="1"
                                class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                        </label>
                        <label class="block text-sm ">
                            <span class="text-gray-700 dark:text-gray-400 text-xs">Fecha inicial</span>
                            <input id="fecha" name="fecha" type="date"
                                class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                        </label>
                        <div class="flex justify-center">
                            <button type="submit"
                                class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                                <span class="mr-2">Search</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if ($tp != 'NO')
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


                                <input type="hidden" name="SeTP" id="SeTP" value={{ $tp }}>
                                <input type="hidden" name="SePC" id="SePC" value={{ $cp }}>
                                <input type="hidden" name="SeWC" id="SeWC" value={{ $wc }}>
                                <input type="hidden" name="fecha" id="SePC" value={{ $fecha }}>
                                <input type="hidden" name="dias" id="SeWC" value={{ $dias }}>
                                <table class=" table-auto ">
                                    <thead>
                                        <tr
                                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800 sticky top-0 ">
                                            <th class=" header px-4 py-3 sticky" rowspan="4">Part No</th>
                                            <th class=" header px-4 py-3 sticky ">
                                                <div class="flex justify-center">
                                                    <button type="submit"
                                                        class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                                                        <span class="mr-2">Cargar</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                            stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </th>
                                            <th class=" header px-4 py-3 sticky colmde"></th>
                                            @php
                                                $hoy = $fecha;
                                                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                                $totalD = 0;
                                            @endphp

                                            @while ($hoy != $fin)
                                                <th colspan="2" align="center"
                                                    class="sticky headerpx-4 py-3 text-xs text-center colmde">
                                                    <div class='W-full'>
                                                        {{ date('Ymd', strtotime($hoy)) }}
                                                    </div>
                                                    <div class='w-full'>
                                                        {{ date('l', strtotime($hoy)) }}
                                                    </div>
                                                    <div class="flex">
                                                        <div class=" flex-1 w-1/2" aling="center">
                                                            D
                                                        </div>
                                                        <div class=" flex-1 w-1/2" aling="center">
                                                            N
                                                        </div>
                                                    </div>


                                                </th>
                                                @php
                                                    $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                                    $totalD = $totalD + 1;
                                                @endphp
                                            @endwhile
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
                                                @php
                                                    $coni = 0;
                                                @endphp
                                                @while ($coni < $totalD)
                                                    <td class="px-4 py-3 text-xs text-center ">
                                                    </td>
                                                    <td class="px-4 py-3 text-xs text-center colmde  ">
                                                    </td>
                                                    @php
                                                        $coni++;
                                                    @endphp
                                                @endwhile
                                            </tr>
                                            {{-- forecast --}}
                                            <tr class="text-gray-700 dark:text-gray-400">
                                                <td class="px-4 py-3 text-xs text-center">
                                                </td>
                                                <td class="px-4 py-3 text-xs text-center">
                                                    Parte final:<br>
                                                    @php
                                                        $F1 = $obj->F1($plans->IPROD);
                                                        $cF1 = $obj->contarF1($plans->IPROD);

                                                    @endphp
                                                    @foreach ($F1 as $F1s)
                                                    {{$F1s->BPROD}}/{{$F1s->BCLAS}}<br>
                                                        @switch($F1s->BCLAS)
                                                            @case('F1')

                                                                **{{ $F1s->BPROD }}**
                                                                <br>
                                                            @break

                                                            @case('01')
                                                                @php
                                                                    $procase = $F1s->BCLAS;
                                                                    $propadre = $F1s->BPROD;
                                                                    $Fp1 = $obj->F1($F1s->BPROD);
                                                                @endphp
                                                                   <br>
                                                                @foreach ($Fp1 as $F1ps)
                                                                    +++{{ $F1s->BPROD }}+++
                                                                @endforeach


                                                            @endswitch
                                                        @endforeach


                                                    </td>
                                                    <td class="px-4 py-3 text-xs text-center colmde">
                                                        <div class='w-40  border  border-b-4 border-rose-600 '>
                                                            <input value=" Requeriment (Forecast)"
                                                                class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                disabled />
                                                        </div>

                                                        <div class='w-40  border  border-b-4 mt-2  border-rose-600'>
                                                            <input value="Cantidad de plan"
                                                                class="block w-full text-xs text-center border-rose-600 form-input"
                                                                disabled />
                                                        </div>
                                                        <div class='w-40  border border-t-4 mt-2'>
                                                            <input value="Firme"
                                                                class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                disabled />
                                                        </div>
                                                        <div class='w-40 border mt-2'>
                                                            <input value="Shop Order"
                                                                class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                disabled />
                                                        </div>
                                                    </td>
                                                    @php
                                                        $cont = $obj->contar($plans->IPROD, $hoy, $fin);
                                                        $conplanq = $obj->contarplan($plans->IPROD, $hoy, $fin);
                                                        $confirme = $obj->contarfirme($plans->IPROD, $hoy, $fin);
                                                        $conshop = $obj->contarShopO($plans->IPROD, $hoy, $fin);
                                                    @endphp

                                                    @php
                                                        $hoy = $fecha;
                                                        $coni = 0;
                                                    @endphp
                                                    @while ($coni < $totalD)
                                                        <td class="px-4 py-3 text-xs text-center  ">
                                                            <div class=' border '>
                                                                @if ($cont != 0)
                                                                    @php
                                                                        $totalD1 = $obj->Forecast($plans->IPROD, $hoy, '%D%');
                                                                        $totalD1 = $totalD1 + 0;
                                                                    @endphp
                                                                    <input value={{ $totalD1 }}
                                                                        class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @else
                                                                    <input value="0"
                                                                        class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @endif
                                                            </div>
                                                            <div class=' border mt-2'>
                                                                @if ($conplanq == 0)
                                                                    <input value=" 0"
                                                                        class="block w-16 text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @else
                                                                    @php
                                                                        $totalplan = 0;
                                                                        $planq = $obj->plan($plans->IPROD, $hoy, '%D%');
                                                                        $planq = $planq + 0;
                                                                    @endphp
                                                                    <input value={{ $planq }}
                                                                        class="block w-16 text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @endif

                                                            </div>
                                                            <div class=' border border-t-2 mt-2'>
                                                                @php
                                                                    $nam = $plans->IPROD . '/' . $hoy . '/D';
                                                                @endphp
                                                                @if ($confirme != 0)
                                                                    @php
                                                                        $firmeq = $obj->firme($plans->IPROD, $hoy, '%D%');
                                                                        $firmeq = $firmeq + 0;
                                                                    @endphp
                                                                    <input type="number" maxlength="6"
                                                                        id="{{ $nam }}"
                                                                        name="{{ $nam }}"
                                                                        onchange="myFunction(this.id)"
                                                                        value={{ $firmeq }}
                                                                        class="  block w-16 text-xs text-center dark:text-gray-300 dark:border-green-600 dark:bg-green-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input caret-green-100" />
                                                                @else
                                                                    <input type="number" maxlength="6" value="0"
                                                                        id="{{ $nam }}"
                                                                        name="{{ $nam }}"
                                                                        onchange="myFunction(this.id)"
                                                                        class=" block w-16 text-xs text-center dark:text-gray-300 dark:border-green-600 dark:bg-green-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input caret-green-100" />
                                                                @endif
                                                            </div>
                                                            <div class='border border-t-2 mt-2'>
                                                                @if ($conshop != 0)
                                                                    @php
                                                                        $shop = $obj->ShopO($plans->IPROD, $hoy, '%D%');
                                                                        $shop = $shop + 0;
                                                                    @endphp
                                                                    <input value={{ $shop }}
                                                                        class=" block w-16 text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @else
                                                                    <input value="0"
                                                                        class="block w-16 text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="px-4 py-3 text-xs text-center  colmde ">
                                                            <div class=' border '>
                                                                @if ($cont != 0)
                                                                    @php
                                                                        $totaln1 = $obj->Forecast($plans->IPROD, $hoy, '%N%');
                                                                        $totaln1 = $totaln1 + 0;
                                                                    @endphp
                                                                    <input value={{ $totaln1 }}
                                                                        class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @else
                                                                    <input value="0"
                                                                        class="block w-full text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @endif
                                                            </div>
                                                            <div class=' border border-b-2 mt-2'>
                                                                @if ($conplanq == 0)
                                                                    <input value="0"
                                                                        class="block w-16 text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @else
                                                                    @php
                                                                        $planqn = $obj->plan($plans->IPROD, $hoy, '%N%');
                                                                        $planqn = $planqn + 0;
                                                                    @endphp
                                                                    <input value={{ $planqn }}
                                                                        class="block w-16 text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @endif
                                                            </div>
                                                            <div class=' border border-t-2 mt-2 border-rose-600 '>
                                                                @php
                                                                    $namen = $plans->IPROD . '/' . $hoy . '/N';
                                                                @endphp
                                                                @if ($confirme != 0)
                                                                    @php
                                                                        $firmeN = $obj->firme($plans->IPROD, $hoy, '%N%');
                                                                        $firmeN = $firmeN + 0;

                                                                    @endphp
                                                                    <input id="{{ $namen }}"
                                                                        name="{{ $namen }}"
                                                                        onchange="myFunction(this.id)" type="number"
                                                                        maxlength="6" value={{ $firmeN }}
                                                                        class="  block w-16 text-xs text-center dark:text-gray-300 dark:border-rose-600 dark:bg-green-700 focus:border-rose-600 focus:outline-none focus:shadow-outline-green dark:focus:shadow-outline-green form-input" />
                                                                @else
                                                                    <input id="{{ $namen }}"
                                                                        name="{{ $namen }}"
                                                                        onchange="myFunction(this.id)" value="0"
                                                                        class="  block w-16 text-xs text-center dark:text-gray-300 dark:border-green-600 dark:bg-green-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-green dark:focus:shadow-outline-green form-input caret-green-100" />
                                                                @endif
                                                            </div>
                                                            <div class=' border border-t-2 mt-2'>

                                                                @if ($conshop != 0)
                                                                    @php
                                                                        $shopN = $obj->ShopO($plans->IPROD, $hoy, '%N%');
                                                                        $shopN = $shopN + 0;
                                                                    @endphp

                                                                    <input type="number" maxlength="6"
                                                                        value={{ $shopN }}
                                                                        class="block w-16 text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @else
                                                                    <input type="number" maxlength="6" value='0'
                                                                        class="block w-16 text-xs text-center dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
                                                                        disabled />
                                                                @endif
                                                            </div>

                                                        </td>
                                                        @php
                                                            $hoy = date('Ymd', strtotime($hoy . '+1 day'));
                                                            $coni++;
                                                        @endphp
                                                    @endwhile

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
            @endif

            <script>
                function myFunction(xx) {
                    console.log(xx);
                }
            </script>

    </x-app-layout>
