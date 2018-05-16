<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AcrSolicitudAcreditacionModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'acr_solicitudesacreditaciones'; //tabla de la BD
    protected $primaryKey = 'id_solicitudacreditacion';

    protected $fillable = ["fkid_contratoempleado", 
    	"fkid_estadosolicitud",
    	"id_session",
    	"updated_at",
    	"created_at"];

    protected $dates = ['deleted_at'];

    /**
    * Obtención de las solicitudes de acreditacion   
    * @return object
    */
    public function getSolicitudesAcreditaciones() {
        $sql = "SELECT * 
                FROM acr_solicitudesacreditaciones";
        return DB::select($sql);
    }


    /**
    *
    *   Insercion de los registros de archivos
    *
    */
    public function insertSolicitudesAcreditaciones($contratoempleado, $estado)

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
    *   Insercion de los registros de archivos
    *
    */
    public function getAcreditaciones(){
        return DB::select('CALL sp_getAcreditaciones()');   
    }

    /**
    *
    *   Insercion de los registros de archivos
    *
    */
    public function getAcreditacionesAprobadas(){
        return DB::select('CALL sp_getAcreditacionesAprobadas()');   
    }

    /**
    *
    *   Insercion de los registros de archivos
    *
    */
    public function getAcreditacionesRechazadas(){
        return DB::select('CALL sp_getAcreditacionesRechazadas()');   
    }

    /**
    *
    *   Obtencion de la informacion de una acreditacion por persona
    *
    */
    public function getAcreditacionesPropias($persona){
        return DB::select("CALL sp_getAcreditacionesPropias(?)", array($persona));   
    }

    /**
    *
    */
    public function getAcreditacionesContrato($contrato){
        return DB::select("SELECT id_solicitudacreditacion
                FROM acr_solicitudesacreditaciones
                WHERE fkid_contratoempleado=?", array($contrato));   
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