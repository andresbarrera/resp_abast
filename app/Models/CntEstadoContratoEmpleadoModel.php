<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CntEstadoContratoEmpleadoModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'cnt_estadoscontratosempleados'; //tabla de la BD
    protected $primaryKey = 'id_estadocontratoempleado';

    protected $fillable = ["detalle_estadocontratoempleado",
        "id_session", 
    	"updated_at",
    	"created_at"];

    
    protected $dates = ['deleted_at'];

	
    /**
    *
    * Obtención de los tipos de documentos del sistema
    * @return object
    */
	public function getEstadosContratosEmpleados() {
		$sql = "SELECT * 
				FROM cnt_estadoscontratosempleados";
		return DB::select($sql);
	}

}
