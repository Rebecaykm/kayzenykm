<?php

namespace Database\Seeders;

use App\Models\Scrap;
use App\Models\TypeScrap;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScrapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Scrap::create(['code' => '01', 'name' => 'PROBLEMA DIMENSIONAL (PIEZA FUERA DE ESPECIFICACIÓN DIMENSIONAL, CORTE, SUPERFICIE, DIÁMETROS, ÁNGULO, POSICIÓN)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '02', 'name' => 'FORMA (DEFORMACIÓN, DOBLEZ, TORCIDO, GOLPES) BARRENOS, SUPERFICIES, HOLES, CONTORNOS', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '03', 'name' => 'GROSOR (ESPESOR) DEL ACERO (ESPESOR INCORRECTO, ADELGAZAMIENTO, ARRUGAS, ARRASTRE)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '04', 'name' => 'MARCA DE SCRAP, INCRUSTACIÓN DE SCRAP', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '05', 'name' => 'REBABA (BARRENOS, CONTORNO, SLOTS)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '06', 'name' => 'RAYADURAS', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '07', 'name' => 'FALTANTE DE MATERIAL', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '08', 'name' => 'EXCESO DE MATERIAL', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '09', 'name' => 'MAL ESTAMPADO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '10', 'name' => 'FALTANTE DE BARRENOS', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '11', 'name' => 'EXCESO DE MATERIAL', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '12', 'name' => 'ACERO FIN DE ROLLO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '13', 'name' => 'ACERO PRINCIPIO DE ROLLO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '14', 'name' => 'EMPALME DE ACERO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '15', 'name' => 'MAL ESTAMPADO EN MARC DE ID (LOGO, LH, LR)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '16', 'name' => 'MAL FORMADO POR FALTA DE NITRÓGENO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '17', 'name' => 'DEFECTO DE INCLUSIÓN DE MATERILA EN EL ACERO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '18', 'name' => 'OMISIÓN DEL PROCESO (FALTA DE UN PROCESO, DE MATERIAL O TUERCA)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '19', 'name' => 'ERROR EN PROCESO DE MANUFACTURA O ENSAMBLE', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA DIMENSIÓN DEL PRODUCTO')->pluck('id')->first()]);

        Scrap::create(['code' => '20', 'name' => 'SONIDO EXTRAÑO (VIBRACIÓN, FLOJO, SONIDO DURANTE DESEMPEÑO)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA FUNCIONALIDAD DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '21', 'name' => 'FALTA DE LA FUNCIONALIDAD', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA FUNCIONALIDAD DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '22', 'name' => 'FUGA', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA FUNCIONALIDAD DEL PRODUCTO')->pluck('id')->first()]);
        Scrap::create(['code' => '23', 'name' => 'RÍGIDO (SIN MOVIMIENTO)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA FUNCIONALIDAD DEL PRODUCTO')->pluck('id')->first()]);

        Scrap::create(['code' => '24', 'name' => 'OBJETO EXTRAÑO (MEZCLADO, ADHESIÓN, INSERTADO)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN EL ENSAMBLE')->pluck('id')->first()]);
        Scrap::create(['code' => '25', 'name' => 'OTORQUE INSUFICIENTE', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN EL ENSAMBLE')->pluck('id')->first()]);
        Scrap::create(['code' => '26', 'name' => 'DOBLE ENSAMBLE', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN EL ENSAMBLE')->pluck('id')->first()]);
        Scrap::create(['code' => '27', 'name' => 'OFALTANTE DE SELLO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN EL ENSAMBLE')->pluck('id')->first()]);
        Scrap::create(['code' => '28', 'name' => 'FALTA DE FUERZA DE INSERCIÓN DE BUJES', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN EL ENSAMBLE')->pluck('id')->first()]);
        Scrap::create(['code' => '29', 'name' => 'TUERCA ECLIPSADA', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN EL ENSAMBLE')->pluck('id')->first()]);
        Scrap::create(['code' => '30', 'name' => 'DEFECTO POR POKAYOKE', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN EL ENSAMBLE')->pluck('id')->first()]);
        Scrap::create(['code' => '31', 'name' => 'GOLPE DE PIEZA EN JIG', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN EL ENSAMBLE')->pluck('id')->first()]);

        Scrap::create(['code' => '32', 'name' => 'PINTURA NEGRA (BAJO ESPESOR,MALA ADHERENCIA)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA PINTURA')->pluck('id')->first()]);
        Scrap::create(['code' => '33', 'name' => 'CONTAMINACIÓN', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA PINTURA')->pluck('id')->first()]);
        Scrap::create(['code' => '34', 'name' => 'PINHOLE', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA PINTURA')->pluck('id')->first()]);
        Scrap::create(['code' => '35', 'name' => 'GRUMO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA PINTURA')->pluck('id')->first()]);
        Scrap::create(['code' => '36', 'name' => 'CRATER', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA PINTURA')->pluck('id')->first()]);
        Scrap::create(['code' => '37', 'name' => 'MAL RETRABAJO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA PINTURA')->pluck('id')->first()]);
        Scrap::create(['code' => '38', 'name' => 'MALA APARIENCIA (INCUSIÓN DE MATERIAL EN LA PINTURA, BLANQUEAMIENTO, MANCHAS)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA PINTURA')->pluck('id')->first()]);
        Scrap::create(['code' => '39', 'name' => 'FALSO CONTACTO EN PIEZA (FALTA DE PINTURA)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA PINTURA')->pluck('id')->first()]);

        Scrap::create(['code' => '40', 'name' => 'PUNTO DE SOLDADO FUERA DE POSICIÓN (SPOT WELD SW , PROJECTION WELD PW)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '41', 'name' => 'DEFECTOS EN PUNTO DE SOLDADURA (FISURA, POSICIÓN, CON POLVO)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '42', 'name' => 'SOLDADURA DEFECTUOSA (POSICIÓN, SALPICADO, FISURA, SOCAVADO,', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '43', 'name' => 'SIN SOLDADURA', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '44', 'name' => 'SOLDADURA DESPLAZADA ARC WELD AW', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '45', 'name' => 'SALPICADURA DE SOLDADURA EN TORNILLO TUERCA, PUNZONADOS', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '46', 'name' => 'BAJA RESISTENCIA EN PROYECCIÓN', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '47', 'name' => 'FALTA DE PENETRACIÓN ARC WELD', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '48', 'name' => 'CORTO EN PROYECCIÓN DE TUERCA, TORNILLO, SPACER', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '49', 'name' => 'MAL RETRABAJO DE SOLDADURA ARC WELD', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);
        Scrap::create(['code' => '50', 'name' => 'MAL RETRABAJO POR MACHUELO O TARRAJA EN CUERDAS DE TUERCA O TORNILLO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS EN LA SOLDADURA')->pluck('id')->first()]);

        Scrap::create(['code' => '51', 'name' => 'ÓXIDO (CORROSIÓN, FALLAS DEL RECUBRIMIENTO ANTICORROSIVO)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS VISUALES')->pluck('id')->first()]);
        Scrap::create(['code' => '52', 'name' => 'FISURA', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS VISUALES')->pluck('id')->first()]);

        Scrap::create(['code' => '53', 'name' => 'MATERIAL EQUIVOCADO (MAL ETIQUETADO)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DE MATERIA PRIMA')->pluck('id')->first()]);
        Scrap::create(['code' => '54', 'name' => 'COMPOSICIÓN DE LA MATERIA PRIMA', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DE MATERIA PRIMA')->pluck('id')->first()]);
        Scrap::create(['code' => '55', 'name' => 'DEFECTO DE DIMENSIÓN', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DE MATERIA PRIMA')->pluck('id')->first()]);
        Scrap::create(['code' => '56', 'name' => 'DEFORMACIÓN', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DE MATERIA PRIMA')->pluck('id')->first()]);
        Scrap::create(['code' => '57', 'name' => 'ÓXIDO', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DE MATERIA PRIMA')->pluck('id')->first()]);
        Scrap::create(['code' => '58', 'name' => 'PROBLEMA DE SOLDADURA', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DE MATERIA PRIMA')->pluck('id')->first()]);

        Scrap::create(['code' => '59', 'name' => 'MEZCLA CON OTRO MATERIAL U OTRA PARTE', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '60', 'name' => 'FALTA DE DOCUMENTOS (HISTORIAL, ESTÁNDAR, IDENTIFICACIÓN, TRAZABILIDAD)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '61', 'name' => 'MATERIAL CAÍDO (MANIOBRAS)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '62', 'name' => 'OTROS', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '63', 'name' => 'PRUEBAS POR PARTE DE CALIDAD: SOLDADURA, MACROSECCIÓN Y DOBLEZ (TESTING ROOM)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '64', 'name' => 'MATERIAL GOLPEADO (MANIOBRAS)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '65', 'name' => 'VALIDACIONES POR AJUSTES DE INGENIERÍA', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '66', 'name' => 'VALIDACIONES POR MANTENIMIENTOS (CORRECTIVOS, PREVENTIVOS)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '67', 'name' => 'MATERIAL OBSOLETO POR ECN (STOCK)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '68', 'name' => 'SOBREINVENTARIO DE MATERIAL (PRIMERAS ENTRADAS-PRIMERAS SALIDAS)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
        Scrap::create(['code' => '69', 'name' => 'PRUEBAS DE VALIDACIÓN DE PROCESO "DESTRUCTIVAS" (INICIO, INTERMEDIO, FINAL)', 'type_scrap_id' => TypeScrap::where('name', 'DEFECTOS DURANTE EL CONTROL DE MANEJO DE MATERIAL')->pluck('id')->first()]);
    }
}
