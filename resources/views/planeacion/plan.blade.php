<x-app-layout title="Plan">
    <div class="container">
        <div class="container">
            <div class="container grid px-6 mx-auto gap-y-2">
                <h2 class="p-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                    Planeación

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
                                    <th class=" header px-4 py-3 sticky "></th>
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

                                            <?php
                                            echo $plans->IPROD;
                                            $hoy = $fecha;
                                            $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));

                                            ?>

                                        </td>
                                        <td class="px-4 py-3 text-xs text-center">
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
                                        <td class="px-4 py-3 text-xs text-center">
                                            Parte final:<br>
                                            @php
                                                if ($plans->BCLAC == 'F1') {
                                                    echo $plans->BCLAC . '/' . $plans->BCHLD;
                                                } else {
                                                    $F1 = $obj->F1($plans->IPROD);

                                                    foreach ($F1 as $F1s) {
                                                        if ($F1s->Bclas != 'F1') {
                                                            $procase = $F1s->BCLAS;
                                                            $propadre=$F1s->BPROD;
                                                            while ($procase != 'F1') {
                                                                $padre1 = $obj->Padre($plans->BPROD);

                                                                foreach ($padre1 as $padre1s) {
                                                                    if ($padre1s->BCLAS == 'F1') {
                                                                        $propadre = $padre1s->BPROD;
                                                                        $procase = $padre1s->BCLAS;
                                                                    }
                                                                }
                                                            }
                                                            echo $propadre;
                                                            echo $procase;
                                                            echo '<br>';

                                                        } else {
                                                            echo $F1s->BPROD;
                                                            echo '<br>';
                                                        }
                                                    }
                                                }

                                            @endphp
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center colmde">
                                            Requeriment (Forecast)
                                        </td>
                                        <?php
                                            $hoy =$fecha;
                                            $fin= date('Ymd', strtotime($hoy . '+'.$dias.' day'));
                                            if ($plans->BCLAC == 'F1') {
                                                $cont = $obj->contar($plans->IPROD, $hoy, $fin);
                                                if($cont!=0){
                                                    $hoy = $fecha;
                                                    $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                                    While($hoy!=$fin){
                                                    ?>
                                        <td class="px-4 py-3 text-xs text-center  ">
                                            <?php
                                            $QTY = $obj->contard($plans->IPROD, $hoy, '%D%');
                                            if ($QTY != 0) {
                                                $QTY = $obj->Forecast($plans->IPROD, $hoy, '%D%');
                                                $totalD1 = 0;
                                                foreach ($QTY as $QTYs) {
                                                    $totalD1 = $totalD1 + $QTYs->MQTY;
                                                }
                                                echo $totalD1;
                                            } else {
                                                echo '0' . '<br>';
                                            }
                                            ?>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center  colmde ">
                                            <?php
                                            $QTY = $obj->contard($plans->IPROD, $hoy, '%N%');
                                            if ($QTY != 0) {
                                                $QTY = $obj->Forecast($plans->IPROD, $hoy, '%N%');
                                                $totaln1 = 0;
                                                foreach ($QTY as $QTYs) {
                                                    $totaln1 = $totaln1 + $QTYs->MQTY;
                                                }
                                                echo $totaln1;
                                            } else {
                                                echo '0' . '<br>';
                                            }
                                            ?>
                                        </td>
                                        <?php
                                                  $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                                    }
                                                }else {
                                                    $hoy = $fecha;
                                                    $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                                    While($hoy!=$fin){
                                                        ?>
                                        <td class="px-4 py-3 text-xs text-center  ">
                                            0
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center  colmde ">
                                            0
                                        </td>
                                        <?php
                                                        $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                                    }
                                                }

                                            }else {

                                                $hoy = $fecha;
                                                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                                While($hoy!=$fin){
                                                    ?>
                                        <td class="px-4 py-3 text-xs text-center ">
                                            <?php
                                            $totalD = 0;
                                            foreach ($F1 as $F1s) {
                                                $QTY = $obj->contard($F1s->BPROD, $hoy, '%D%');
                                                if ($QTY != 0) {
                                                    $QTY = $obj->Forecast($F1s->BPROD, $hoy, '%D%');

                                                    foreach ($QTY as $QTYs) {
                                                        $totalD = $totalD + $QTYs->MQTY;
                                                    }
                                                } else {
                                                    $totalD = $totalD + 0;
                                                }
                                            }
                                            echo $totalD;
                                            ?>
                                        </td>

                                        <td class="px-4 py-3 text-xs text-center  colmde ">
                                            <?php
                                            $totalN = 0;
                                            foreach ($F1 as $F1s) {
                                                $QTY = $obj->contard($F1s->BPROD, $hoy, '%N%');
                                                if ($QTY != 0) {
                                                    $QTY = $obj->Forecast($F1s->BPROD, $hoy, '%N%');
                                                    foreach ($QTY as $QTYs) {
                                                        $totalN = $totalN + $QTYs->MQTY;
                                                    }
                                                } else {
                                                    $totalN = $totalN + 0;
                                                }
                                            }
                                            echo $totalN;
                                            ?>

                                        </td>

                                        <?php
                                                    $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                                    }
                                                }
                                               ?>
                                    </tr>
                                    <tr>
                                        <td class="px-4 py-3 text-xs text-center">
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center">
                                            Parte padre:<br>
                                            @php
                                                if ($plans->BCLAC == 'F1') {
                                                    echo $plans->BCLAC . '/' . $plans->BCHLD;
                                                } else {
                                                    $F1 = $obj->padre($plans->IPROD);

                                                    foreach ($F1 as $F1s) {
                                                        if ($F1s->BCLAS == '01') {
                                                            $C01 = $obj->padre($F1s->BPROD);
                                                            foreach ($C01 as $C01s) {
                                                                echo $C01s->BCLAS . '/' . $C01s->BPROD;
                                                                echo '<br>';
                                                            }
                                                        } else {
                                                            echo $F1s->BCLAS . '/' . $F1s->BPROD;
                                                            echo '<br>';
                                                        }
                                                    }
                                                }

                                            @endphp
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center colmde">
                                            Requeriment (padre)
                                        </td>
                                        <?php
                                        $hoy =$fecha;
                                        $fin= date('Ymd', strtotime($hoy . '+'.$dias.' day'));
                                        if ($plans->BCLAC == 'F1') {
                                                $hoy = $fecha;
                                                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                                While($hoy!=$fin){
                                                ?>
                                        <td class="px-4 py-3 text-xs text-center  ">
                                            0
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center  colmde ">
                                            0
                                        </td>
                                        <?php
                                                $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                                    }
                                                }
                                                    else {
                                                        $hoy = $fecha;
                                                $fin = date('Ymd', strtotime($hoy . '+' . $dias . ' day'));
                                                While($hoy!=$fin){
                                                    ?>
                                        <td class="px-4 py-3 text-xs text-center  ">
                                            <?php

                                            foreach ($F1 as $F1s) {
                                                if ($F1s->BCLAS == 'F1') {
                                                    echo 0;
                                                } else {
                                                    if ($F1s->BCLAS == '01') {
                                                        $C01 = $obj->padre($F1s->BPROD);
                                                        foreach ($C01 as $C01s) {
                                                            echo $ecl = $obj->requerimiento($C01s->BPROD, $hoy, '%D%');
                                                        }
                                                    } else {
                                                        echo $ecl = $obj->requerimiento($F1s->BPROD, $hoy, '%D%');
                                                    }
                                                }
                                            }

                                            ?>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-center   colmde">
                                            <?php
                                            $ecl = 0;
                                            foreach ($F1 as $F1s) {
                                                if ($F1s->BCLAS == 'F1') {
                                                    echo $ecl;
                                                } else {
                                                    $ecl = $ecl + $obj->requerimiento($F1s->BPROD, $hoy, '%N%');
                                                }
                                            }
                                            echo $ecl;

                                            ?>
                                        </td>
                                        <?php
                                            $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                        }

                                    }

                                                ?>
                                    <tr>
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
