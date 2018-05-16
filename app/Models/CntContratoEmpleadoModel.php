<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CntContratoEmpleadoModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'cnt_contratosempleados'; //tabla de la BD
    protected $primaryKey = 'id_contratoempleado';

    protected $fillable = ["inicio_contratoempleado",
        "termino_contratoempleado",
        "estado_contratoempleado",
        "observacion_contratoempleado",
        "fkid_tipocontratoempleado",
        "fkid_estadocontratoempleado",
        "fkid_contratoempresa",
        "fkid_persona",
    	"id_session", 
    	"updated_at",
    	"created_at"];

    
    protected $dates = ['deleted_at'];

	
    /**
    *
    * Obtención de los tipos de documentos del sistema
    * @return object
    */
	public function getContratosEmpleados() {
		$sql = "SELECT * 
				FROM cnt_contratosempleados";
		return DB::select($sql);
	}

    /**
    *
    * Crea un contrato de empleado
    * @param string $rut
    * @param string $digito
    * @param date $nacimiento
    * @param string $nombres
    * @param string $patapel 
    * @param string $matapel
    * @param int $sesion
    * @param date fecha
    * @return bool
    */
    public function insertContratoEmpleado($inicio, $termino, $estado, $observacion, $tipocontratoempleado, $estadocontratoempleado, $contratoempresa, $persona, $sesion, $fecha)

    {
        try {
                DB::insert('INSERT INTO cnt_contratosempleados(inicio_contratoempleado, termino_contratoempleado, estado_contratoempleado, observacion_contratoempleado, fkid_tipocontratoempleado, fkid_estadocontratoempleado, fkid_contratoempresa, fkid_persona,  id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ' , array($inicio, $termino, $estado, $observacion, $tipocontratoempleado, $estadocontratoempleado, $contratoempresa, $persona, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }   
    }

    /**
     * Modifica un contrato de empleado
     * @param int $id
     * @param date inicio
     * @param date termino
     * @param string $estado
     * @param string $observacion
     * @param int $tipocontratoempleado
     * @param int $estadocontratoempleado
     * @param int $contratoempresa
     * @param int $persona
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function updateContratoEmpleado($id, $inicio, $termino, $estado, $observacion, $tipocontratoempleado, $estadocontratoempleado, $contratoempresa, $persona, $sesion, $fecha) {
            try {
                DB::update('UPDATE cnt_contratosempleados SET id_session=?, updated_at=?, inicio_contratoempleado=?, termino_contratoempleado=?, estado_contratoempleado=?, observacion_contratoempleado=?,  fkid_tipocontratoempleado=?, fkid_estadocontratoempleado=?, fkid_contratoempresa=?, fkid_persona=?  WHERE id_contratoempleado=?' , array($sesion, $fecha, $inicio, $termino, $estado, $observacion, $tipocontratoempleado, $estadocontratoempleado, $contratoempresa, $persona, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

    /**
     * Obtener id persona de cada usuario.
     *
     * @param  string  $value
     * @return id
     */
    public function getIdContrato($persona)
    {
        $condicion = DB::select('SELECT id_contratoempleado FROM cnt_contratosempleados WHERE fkid_persona=?' , array($persona));
        if($condicion){
            return $condicion[0];
        }
        else{
            return NULL;
        }

    }


}