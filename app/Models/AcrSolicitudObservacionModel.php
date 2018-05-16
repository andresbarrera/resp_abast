<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AcrSolicitudObservacionModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'acr_solicitudesobservaciones'; //tabla de la BD
    protected $primaryKey = 'id_solicitudobservacion';

    protected $fillable = ["observacion_solicitudobservacion", 
    	"fkid_solicitudacreditacion",
    	"id_session",
    	"updated_at",
    	"created_at"];

    protected $dates = ['deleted_at'];

    /**
    * Obtención de las observaciones de las solicitudes   
    * @return object
    */
    public function getSolicitudesObservaciones() {
        $sql = "SELECT * 
                FROM acr_solicitudesobservaciones";
        return DB::select($sql);
    }


    /**
    * Obtención de las observaciones de las solicitudes   
    * @return object
    */
    public function getSolicitudesObservacionesAcreditaciones($id) {
        return DB::select("SELECT id_solicitudobservacion, observacion_solicitudobservacion, fkid_solicitudacreditacion, acr_solicitudesobservaciones.id_session, 
            DATE_FORMAT(acr_solicitudesobservaciones.created_at, '%d/%m/%y %H:%i') AS fecha, nombres_usuario, patapel_usuario 
            FROM acr_solicitudesobservaciones
            LEFT JOIN (sys_usuarios)
            ON id_usuario = acr_solicitudesobservaciones.id_session
            WHERE fkid_solicitudacreditacion=?
            ORDER BY fecha DESC
            LIMIT 10", array($id));
    }    


    /**
    *
    *   Insercion de los registros de archivos
    *
    */
    public function insertSolicitudesObservaciones($observacion, $solicitudacreditacion, $sesion, $fecha)

    {
        try {
                DB::insert('INSERT INTO acr_solicitudesobservaciones( observacion_solicitudobservacion, fkid_solicitudacreditacion, id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?) ' , array($observacion, $solicitudacreditacion, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }   
    }

}