<x-app-layout title="Plan">
    <div class="container">
        <div class="container">
            <div class="container grid px-6 mx-auto gap-y-2">
                <h2 class="p-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Planeación componente

                </h2>
                <form method="post" action="{{ route('planeacion.create') }}">
                    @csrf
                    {{-- @php
                         echo $tp . '<br>';
                    echo $cp . '<br>';
                    echo $wc . '<br>';
                    @endphp --}}

                    <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
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
        <div class="container">
            @php
            include_once '../app/Http/Controllers/registros.php';
            $obj = new registros();
            @endphp
            <div class="container">
                <div class="flex flex-col h-screen">
                    <div class="flex-grow overflow-auto">

                        <table class="relative w-full border">
                            <thead class='fija'>
                                <tr
                                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                    <th class=" header px-4 py-3 sticky" rowspan="4">Part No
                                    <th class=" header px-4 py-3 sticky "></th>
                                    <th class=" header px-4 py-3 sticky colmde"></th>
                                    @php
                                        $hoy =$fecha;
                                        $fin = date('Ymd', strtotime($hoy . '+'.$dias.' day'));
                                        while ($hoy != $fin) {
                                 echo '
                                    <th colspan="2" align="center"
                                        class="sticky headerpx-4 py-3 text-xs text-center colmde">
                                         date("Ymd", strtotime($hoy)) . "<br>" . date("l", strtotime($hoy));
                                    </th>';

                                            $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                        }
                                    @endphp
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                <tr>
                                    <td class="px-4 py-3 text-xs text-center">
                                    </td>
                                    <td class="px-4 py-3 text-xs text-center">
                                    </td>
                                    <td class="px-4 py-3 text-xs text-center colmde ">
                                    </td>
                                    <?php
                                        $hoy =$fecha;
                                        $fin = date('Ymd', strtotime($hoy . '+'.$dias.' day'));
                                        while ($hoy != $fin) {
                                    ?>
                                    <td class="px-4 py-3 text-xs text-center ">
                                        D
                                    </td>
                                    <td class="px-4 py-3 text-xs text-center colmde ">
                                        N
                                    </td><?php
                                     $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                    }
                                    ?>
                                </tr>
                                @foreach ($plan as $plans)
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3 text-xs text-center ">

                                            @php
                                            echo $plans->IPROD;
                                            $hoy = $fecha;
                                            $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));

                                            @endphp

                                        </td>
                                        <td class="px-4 py-3 text-xs text-center">
                                        </td>
                                        <td class='px-4 py-3 text-xs text-center colmde'>
                                        </td>
                                        @php

                                        while ($hoy != $fin) {
                                    ?>
                                        <td class="px-4 py-3 text-xs text-center ">
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center colmde  ">
                                        </td><?php
                                    $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                    }
                                @endphp
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
</x-app-layout>
