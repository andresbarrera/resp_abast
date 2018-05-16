<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StaEstadoSolicitudAcreditacionModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'sta_estadossolicitudesacreditaciones'; //tabla de la BD
    protected $primaryKey = 'id_estadosolicitudacreditacion';

    protected $fillable = ["detalle_estadosolicitudobservacion", 
    	"id_session",
    	"updated_at",
    	"created_at"];

    protected $dates = ['deleted_at'];

    /**
    * Obtención de las observaciones de las solicitudes   
    * @return object
    */
    public function getEstadosSolicitudesAcreditaciones() {
        $sql = "SELECT * 
                FROM sta_estadossolicitudesacreditaciones";
        return DB::select($sql);
    }

}