<x-app-layout title="Plan">
    <style type="text/css">
        thead tr th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff;
        }

        .table-responsive {
            height: 200px;
            overflow: scroll;
        }
    </style>
    <?php
    include_once '../app/Http/Controllers/registros.php'; ?>
    <div class="container">

        <div class="w-full overflow-hidden rounded-lg shadow-xs">



            <div class=" w-full overflow-x-auto">
                <?php
                $obj = new registros();

                ?>

                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <?php $variable = 'jhiodhod'; ?>
                            <th class=" header px-4 py-3">Part No
                            <th class=" header px-4 py-3"></th>
                            <?php
                    $hoy = '20220808';
                    $fin = date('Ymd', strtotime($hoy . '+7 day'));
                    while ($hoy != $fin) {
                       ?>
                            <th colspan="2" align="center" class=" headerpx-4 py-3 text-xs">
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
                            <td class="px-4 py-3 text-xs">
                            </td>
                            <?php
                        $hoy = '20220808';
                        $fin = date('Ymd', strtotime($hoy . '+7 day'));
                        while ($hoy != $fin) {
                           ?> <td class="px-4 py-3 text-xs">
                                D
                            </td>
                            <td class="px-4 py-3 text-xs">
                                N
                            </td><?php
                            $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                        }
                        ?>
                        </tr>
                        @foreach ($plan as $plans)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3 text-xs text-center">
                                    {{ $pro = $plans->IPROD }}
                                    <?php
                                    $hoy = '20220808';
                                    $fin = date('Ymd', strtotime($hoy . '+7 day'));
                                    echo $cont = $obj->contar($pro, $hoy, $fin, '%D%');
                                    echo $contN = $obj->contar($pro, $hoy, $fin, '%N%');
                                    ?>
                                </td>
                                <?php
                                  while ($hoy != $fin) {
                               ?>
                                <td class="px-4 py-3 text-xs">
                                </td>
                                <td class="px-4 py-3 text-xs">
                                </td><?php
                                $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                            }
                            ?>
                            </tr>
                            {{-- forecast --}}
                            <tr class="text-gray-700 dark:text-gray-400">

                                <td class="px-4 py-3 text-xs text-center">
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    Requeriment (Forecast)
                                </td>

                                <?php
                                $hoy= '20220808';
                                $fin= date('Ymd', strtotime($hoy . '+7 day'));
                                if ($cont!=0 &&  $cont!=0) {
                                        While($hoy!=$fin){
                                            if ($cont!=0 ){
                                             $tablaD = $obj->Forecast($pro, $hoy,'%D%');
                                                ?>
                                @foreach ($tablaD as $dates)
                                    <td class="px-4 py-3 text-xs">
                                        {{ $dates->MQTY }}
                                    </td>
                                @endforeach
                                <?php
                                            }else {

                                                ?>
                                <td class="px-4 py-3 text-xs">
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
                                $hoy= '20220808';
                                $fin= date('Ymd', strtotime($hoy . '+7 day'));
                                While($hoy!=$fin){
                               ?>

                                <td class="px-4 py-3 text-xs">0
                                </td>
                                <td class="px-4 py-3 text-xs">0
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
