<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class DocExtensionModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'doc_extensiones';
    protected $primaryKey = 'id_extension';

    protected $fillable = [
    	"detalle_extension",
    	"id_session",
    	"updated_at",
    	"created_at"

   	];

   	
   	protected $dates = ['deleted_at'];

     /**
    *
    * Obtención de las extensiones de documentos del sistema
    * @return object
    */
    public function getExtensionesDocumentos() {
      $sql = "SELECT * 
          FROM doc_extensiones";
      return DB::select($sql);
    }
}