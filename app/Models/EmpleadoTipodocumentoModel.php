<?php
namespace ABASTV2\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class EmpleadoTipodocumentoModel extends Model {
	use SoftDeletes;

	/**
	 * The database table and table id used by the model.
	 * @var string
	 */
	protected $table = 'empleados_tiposdocumentos';
	protected $primaryKey = 'id_empleado_tipodocumento';

	/**
	 * Atributos asignables.
	 * @var array
	 */
	protected $fillable = [
								'inicio_empleado_tipodocumento',
                                'termino_empleado_tipodocumento',
                                'nombre_empleado_tipodocumento',
                                'ruta_empleado_tipodocumento',
                                'fkid_contratoempleado',
                                'fkid_tipodocumento',
                                'id_session', 
								'updated_at', 
								'created_at', 
								'deleted_at'
								];

	protected $dates = ['deleted_at'];


	/**
    *
    * Obtención de los documentos, por contrato y con la ultima fecha
    * @param int $id
    * @return object
    */
	public function getTiposDocumentos($id) {
		$condicion = DB::select('CALL sp_getTiposDocumentos(?)' , array($id));
        if($condicion){
            return $condicion;
        }
        else{
            return NULL;
        }
	}

	/**
    *
    * Obtención de los documentos, por contrato y con la ultima fecha
    * @param int $id
    * @return object
    */
	public function getTiposDocumentos2($id) {
		$condicion = DB::select('CALL sp_getTiposDocumentos2(?)' , array($id));
        if($condicion){
            return $condicion;
        }
        else{
            return NULL;
        }
	}


	/**
	*
	*	Insercion de los registros de archivos
	*
	*/
    public function insertEmpleadoTipodocumento($nombre, $ruta, $contrato, $tipodocumento, $fecha, $fecha_termino, $fecha_crea)

    {
        try {
                DB::insert('INSERT INTO empleados_tiposdocumentos(nombre_empleado_tipodocumento, ruta_empleado_tipodocumento, fkid_contratoempleado, fkid_tipodocumento, created_at, updated_at, inicio_empleado_tipodocumento, termino_empleado_tipodocumento) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ' , array($nombre, $ruta, $contrato, $tipodocumento, $fecha_crea, $fecha_crea, $fecha, $fecha_termino));
                return true;
            }catch (Exception $e) {
                return false;
            }   
    }


    /**
    *
    * Obtencion de datos para crear acreditaciones
    *
    *
    *
    */
    public function getTiposDocumentosAcred($id) {
		$condicion = DB::select('CALL sp_getTiposDocumentosAcred(?)' , array($id));
			        if($condicion){
			            return $condicion;
			        }
			        else{
			            return NULL;
			        }
	}
		
    
}
	
