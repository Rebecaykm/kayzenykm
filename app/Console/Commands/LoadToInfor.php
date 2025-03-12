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
        try {
            $conn = odbc_connect("Driver={Client Access ODBC Driver (32-bit)};System=192.168.200.7;", "LXSECOFR;", "LXSECOFR;");

            if ($conn === false) {
                throw new Exception("Error al conectar con la base de datos Infor.");
            }

            $query = "CALL LX834OU.YSF013C";
            $result = odbc_exec($conn, $query);

            if ($result) {
                Log::info("LX834OU.YSF013C : La consulta se ejecutó con éxito en " . date('Y-m-d H:i:s'));
            } else {
                throw new Exception("LX834OU.YSF013C : Error en la consulta: " . odbc_errormsg($conn));
            }
        } catch (Exception $e) {
            Log::alert($e->getMessage());
        } finally {
            if (isset($conn)) {
                odbc_close($conn);
            }
        }
    }
}
