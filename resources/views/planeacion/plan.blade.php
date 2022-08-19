<x-app-layout title="Plan">
    <div class="container">
        <div class="container">
            <div class="container grid px-6 mx-auto gap-y-2">
                <h2 class="p-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Planeación
                </h2>
                <form method="post" action="{{ route('planeacion.create') }}">
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
            <?php
            include_once '../app/Http/Controllers/registros.php'; ?>
            <div class="container">
                <div class="flex flex-col h-screen">
                    <div class="flex-grow overflow-auto">
                        <?php
                        $obj = new registros();
                        ?>
                        <table class="relative w-full border">
                            <thead class='fija'>
                                <tr
                                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                    <th class=" header px-4 py-3 sticky" rowspan="4">Part No
                                    <th class=" header px-4 py-3 sticky colmde"></th>
                                    <?php
                    $hoy =$fecha;
                    $fin = date('Ymd', strtotime($hoy . '+'.$dias.' day'));
                    while ($hoy != $fin) {
                       ?>
                                    <th colspan="2" align="center"
                                        class="sticky headerpx-4 py-3 text-xs text-center colmde">
                                        <?php echo date('Ymd', strtotime($hoy)) . '<br>' . date('l', strtotime($hoy)); ?>
                                    </th>

                                    <?php
                        $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                    }
                        ?>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                <tr>
                                    <td class="px-4 py-3 text-xs text-center">
                                    </td>
                                    <td class="px-4 py-3 text-xs text-center colmde ">
                                    </td>
                                    <?php
                        $hoy = '20220815';
                        $fin = date('Ymd', strtotime($hoy . '+7 day'));
                        while ($hoy != $fin) {
                           ?> <td class="px-4 py-3 text-xs text-center ">
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
                                            {{ $pro = $plans->IPROD }}
                                            <?php
                                            $hoy = '20220815';
                                            $fin = date('Ymd', strtotime($hoy . '+7 day'));
                                            $cont = $obj->contar($plans->IPROD, '20220815', $fin);
                                            $cont1 = $obj->contar($plans->IPROD, $hoy, $fin);
                                            ?>
                                        </td>
                                        <td class='px-4 py-3 text-xs text-center colmde'>
                                        </td>
                                        <?php
                                  while ($hoy != $fin) {
                               ?>
                                        <td class="px-4 py-3 text-xs text-center ">
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center colmde  ">
                                        </td><?php
                                $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                            }
                            ?>
                                    </tr>
                                    {{-- forecast --}}
                                    <tr class="text-gray-700 dark:text-gray-400">

                                        <td class="px-4 py-3 text-xs text-center">
                                            Parte final: <?php
                                            //    echo $plans->BPROD;
                                            //      echo "-";
                                            $prodclas = $plans->BCLAS;
                                            if ($prodclas != 'F1') {
                                                $prodf1 = $plans->BPROD;
                                                $prodclas = $plans->BCLAS;
                                                while ($prodclas != 'F1') {
                                                    $cicloF1 = $obj->F1($prodf1);

                                                    foreach ($cicloF1 as $f1) {
                                                        $prodf1 = $f1->BPROD;
                                                        $prodclas = $f1->BCLAS;
                                                    }
                                                    if ($prodclas == 'F1') {
                                                        echo $prodf1;
                                                        $prodclas;
                                                    }
                                                }
                                            } else {
                                                echo $plans->IPROD;
                                                //  echo "-";
                                                //   $plans->BCLAS;
                                            }

                                            ?>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center colmde">
                                            Requeriment (Forecast)
                                        </td>

                                        <?php
                                $hoy= '20220815';
                                $fin= date('Ymd', strtotime($hoy . '+7 day'));

                                if ($cont!=0) {
                                        While($hoy!=$fin){

                                            if($plans->BCLAC!='F1')
                                            {
                                                $final=$prodf1;
                                            }
                                            else {
                                                $final=$plans->IPROD;
                                            }
                                             $contarD = $obj->contard($final,$hoy ,'%D%');

                                             if($contarD !=0 )
                                             {
                                                $tablaD = $obj->Forecast($final, $hoy,'%D%');
                                                ?>
                                        <td class="px-4 py-3 text-xs text-center ">
                                            @foreach ($tablaD as $dates)
                                                {{ $dates->MQTY }}
                                            @endforeach
                                        </td>
                                        <?php
                                             }
                                             else {
                                               ?>
                                        <td class="px-4 py-3 text-xs text-center ">
                                            0
                                        </td>
                                        <?php
                                             }
                                             $contarN= $obj->contard($final, $hoy,'%N%');
                                             if($contarN !=0 )
                                             {
                                                $tablaN = $obj->Forecast($final, $hoy,'%N%');
                                                ?>
                                        <td class="px-4 py-3 text-xs text-center  colmde ">
                                            @foreach ($tablaN as $datesN)
                                                {{ $datesN->MQTY }}
                                            @endforeach
                                        </td>
                                        <?php

                                             } else {
                                               ?>
                                        <td class="px-4 py-3 text-xs text-center  colmde ">
                                            0
                                        </td>
                                        <?php
                                             }
                                                ?>
                                        <?php
                                              $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                             }
                            }
                            else {
                                $hoy= '20220815';
                                $fin= date('Ymd', strtotime($hoy . '+7 day'));
                                While($hoy!=$fin){
                               ?>

                                        <td class="px-4 py-3 text-xs text-center ">0
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center colmde">0
                                        </td>

                                        <?php
                            $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                }
                            }
                            ?>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-xs text-center ">
                                            Parte padre: <?php
                                            if ($plans->ICLAS != 'F1') {
                                                echo $plans->BPROD;
                                            } else {
                                                echo $plans->IPROD;
                                            }

                                            ?>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center colmde">
                                            Requirement
                                        </td>
                                        <?php
                               $hoy= '20220815';
                                $fin= date('Ymd', strtotime($hoy . '+7 day'));
                                While($hoy!=$fin){
                               ?>
                                        <td class="px-4 py-3 text-xs text-center ">0
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center colmde">0
                                        </td>

                                        <?php
                            $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                }


                              ?>
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
