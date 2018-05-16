<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SolSolicitudModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'sol_solicitudes'; //tabla de la BD
    protected $primaryKey = 'id_solicitud';

    protected $fillable = ["fecha_solicitud", 
    	"estado_solicitud",
        "greembolsable_solicitud",
        "descripcion_solicitud",
        "fkid_prioridad",
        "fkid_user",
    	"id_session",
    	"updated_at",
    	"created_at"];

    protected $dates = ['deleted_at'];

    /**
    *
    *   Insercion de los registros de archivos
    *
    */
    public function insertSolicitudes($contratoempleado, $estado)
    {
        try {
                DB::insert('INSERT INTO acr_solicitudesacreditaciones( fkid_contratoempleado, fkid_estadosolicitud) VALUES (?, ?) ' , array($contratoempleado, $estado));
                return true;
            }catch (Exception $e) {
                return false;
            }   
    }

    /**
    *
    *   Selección de prioridades de solicitudes
    *
    */
    public function getPrioridades()
    {
        $sql = "SELECT * 
                FROM sol_prioridades";
        return DB::select($sql); 
    }



    
    /**
     * Habilita o bloquea un usuario específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @param string $estado_registro
     * @return bool
     */
    public function procesar($id, $sesion, $fecha, $operacion) {
        if($operacion == 'APROBAR' ){
            try {
                DB::update('UPDATE acr_solicitudesacreditaciones SET id_session=?, updated_at=?, fkid_estadosolicitud=2 WHERE id_solicitudacreditacion=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        if($operacion == 'RECHAZAR' ){
            try {
                DB::update('UPDATE acr_solicitudesacreditaciones SET id_session=?, updated_at=?, fkid_estadosolicitud=3 WHERE id_solicitudacreditacion=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
    }


}