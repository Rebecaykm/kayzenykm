<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LoadToInfor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:load-to-infor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call the process LX834OU02/YSF013C in Infor.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //     try {
        //         $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");

        //         if ($conn === false) {
        //             Log::critical("Error al conectar con la base de datos Infor.");
        //             return;
        //         }

        //         $query = "CALL LX834OU02.YSF013C";
        //         $result = odbc_exec($conn, $query);

        //         if ($result) {
        //             Log::info("LX834OU02.YSF013C.- La consulta se ejecutó con éxito en " . date('Y-m-d H:i:s'));
        //         } else {
        //             Log::critical("Error en la consulta: " . odbc_errormsg($conn));
        //         }
        //     } catch (\Exception $e) {
        //         Log::alert($e->getMessage());
        //     } finally {
        //         if (isset($conn)) {
        //             odbc_close($conn);
        //         }
        //     }

        //     $this->info('Process completed');
        // }

        try {
            $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");
            if (!$conn) {
                throw new Exception("No se pudo establecer la conexión ODBC");
            }

            $query = "CALL LX834OU02.YSF013C";
            $result = odbc_exec($conn, $query);

            if (!$result) {
                // Si no se pudo ejecutar la consulta, registra un mensaje de error
                throw new Exception("Error al ejecutar la consulta: " . odbc_errormsg($conn));
            }

            // Si la consulta se ejecuta con éxito, registra un mensaje de éxito
            Log::info("LX834OU02.YSF013C - La consulta se ejecutó con éxito en " . date('Y-m-d H:i:s'));

            // Cierra la conexión ODBC
            odbc_close($conn);
        } catch (Exception $e) {
            // Captura cualquier excepción que ocurra durante la ejecución
            Log::error("Error durante la ejecución de la consulta: " . $e->getMessage());
        }
    }
}
