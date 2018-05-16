<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class TipoObligatoriedadTipoDocumentoModel extends Model
{
    //
	use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'tiposobligatoriedades_tiposdocumentos';
    protected $primaryKey = 'id_tipoobligatoriedad_tipodocumento';

    protected $fillable = [
    	"fkid_tipoobligatoriedad",
    	"fkid_tipodocumento",
    	"id_session",
    	"updated_at",
    	"created_at",
        "deleted_at"

   	];

   	
   	protected $dates = ['deleted_at'];


    /**
    * 
    * Obtención de los menus para el respectivo usuario
    * @param int $id_usuario
    * @return object
    */
    public function getTipoObligatoriedad() {
    $resultado = DB::select(
      'SELECT * FROM sta_tiposobligatoriedades');
    return $resultado;  
    }


    /**
    * 
    * Obtención de los menus para el respectivo usuario
    * @param int $id_usuario
    * @return object
    */
    public function getTiposDocumentos() {
    $resultado = DB::select(
      'SELECT * FROM doc_tiposdocumentos');
    return $resultado;  
    }
 

}
