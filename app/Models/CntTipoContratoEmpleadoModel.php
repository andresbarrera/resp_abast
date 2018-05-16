<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CntTipoContratoEmpleadoModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'cnt_tiposcontratosempleados'; //tabla de la BD
    protected $primaryKey = 'id_tipocontratoempleado';

    protected $fillable = ["detalle_tipocontratoempleado",
        "id_session", 
    	"updated_at",
    	"created_at"];

    
    protected $dates = ['deleted_at'];

	
    /**
    *
    * Obtención de los tipos de contratos de empleados del sistema
    * @return object
    */
	public function getTiposContratosEmpleados() {
		$sql = "SELECT * 
				FROM cnt_tiposcontratosempleados";
		return DB::select($sql);
	}
}
