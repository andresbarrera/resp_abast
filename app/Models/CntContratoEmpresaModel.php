<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CntContratoEmpresaModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'cnt_contratosempresas'; //tabla de la BD
    protected $primaryKey = 'id_contratoempresa';

    protected $fillable = ["codigo_contratoempresa",
        "detalle_contratoempresa",
        "descripcion_contratoempresa",
        "inicio_contratoempresa",
        "termino_contratoempresa",
        "fkid_empresa",
        "fkid_contratoempresa",
        "id_session", 
    	"updated_at",
    	"created_at"];

    
    protected $dates = ['deleted_at'];

	
    /**
    *
    * Obtención de los contraos de empresas del sistema
    * @return object
    */
	public function getContratosEmpresas() {
		$sql = "SELECT * 
				FROM cnt_contratosempresas";
		return DB::select($sql);
	}
}
