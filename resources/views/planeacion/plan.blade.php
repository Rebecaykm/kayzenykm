<x-app-layout title="Plan">
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
                            <?php $variable = 'jhiodhod'; ?>
                            <th class=" header px-4 py-3 sticky" rowspan="4">Part No
                            <th class=" header px-4 py-3 sticky colmde"></th>
                            <?php
                    $hoy = '20220815';
                    $fin = date('Ymd', strtotime($hoy . '+7 day'));
                    while ($hoy != $fin) {
                       ?>
                            <th colspan="2" align="center" class="sticky headerpx-4 py-3 text-xs text-center colmde">
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
                                </td>
                                <td class="px-4 py-3 text-xs text-center colmde">
                                    Requeriment (Forecast)
                                </td>

                                <?php
                                $hoy= '20220815';
                                $fin= date('Ymd', strtotime($hoy . '+7 day'));

                                if ($cont!=0) {
                                        While($hoy!=$fin){


                                             $contarD = $obj->contard($plans->IPROD,$hoy ,'%D%');

                                             if($contarD !=0 )
                                             {
                                                $tablaD = $obj->Forecast($pro, $hoy,'%D%');
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
                                             $contarN= $obj->contard($plans->IPROD, $hoy,'%N%');
                                             if($contarN !=0 )
                                             {
                                                $tablaN = $obj->Forecast($pro, $hoy,'%N%');
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
</x-app-layout>
