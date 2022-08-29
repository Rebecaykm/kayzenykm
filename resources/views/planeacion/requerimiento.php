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
                                                        echo $F1s->BPROD;
                                                        echo '<br>';
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
                                                foreach ($QTY as $QTYs) {
                                                    echo $QTYs->MQTY;
                                                    echo '<br>';
                                                }
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
                                                foreach ($QTY as $QTYs) {
                                                    echo $QTYs->MQTY;
                                                    echo '<br>';
                                                }
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
                                            foreach ($F1 as $F1s) {
                                                $QTY = $obj->contard($F1s->BPROD, $hoy, '%D%');
                                                if ($QTY != 0) {
                                                    $QTY = $obj->Forecast($F1s->BPROD, $hoy, '%D%');
                                                    foreach ($QTY as $QTYs) {
                                                        echo $QTYs->MQTY;
                                                        echo '<br>';
                                                    }
                                                } else {
                                                    echo '0' . '<br>';
                                                }
                                            }
                                            ?>
                                        </td>

                                        <td class="px-4 py-3 text-xs text-center  colmde ">
                                            <?php

                                            foreach ($F1 as $F1s) {
                                                $QTY = $obj->contard($F1s->BPROD, $hoy, '%N%');
                                                if($QTY != 0){
                                                    $QTY = $obj->Forecast($F1s->BPROD, $hoy, '%N%');
                                                    foreach ($QTY as $QTYs) {
                                                        echo $QTYs->MQTY;
                                                        echo '<br>';
                                                    }
                                                }else {
                                                    echo '0'. '<br>';
                                                }
                                            }
                                            ?>

                                        </td>

                                        <?php
                                                    $hoy= date('Ymd', strtotime($hoy . '+1 day'));
                                                    }
                                                }
                                               ?>
                                    </tr>
