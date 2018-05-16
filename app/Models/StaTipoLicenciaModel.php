<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StaTipoLicenciaModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'sta_tiposlicencias'; //tabla de la BD
    protected $primaryKey = 'id_tipolicencia';

    protected $fillable = ["detalle_tipolicencia",
    	"id_session", 
    	"updated_at",
    	"created_at",
    	"deleted_at"];

    
    protected $dates = ['deleted_at'];

	
    /**
    *
    * Obtención de los perfiles del sistema, sin contar el de desarrollador
    * @return object
    */
	public function getTiposLicencias() {
		$sql = "SELECT * 
				FROM sta_tiposlicencias";
		return DB::select($sql);
	}


    /**
     * Crea un tipo de licencia
     * @param string $tipolicencia
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function insertTipoLicencia($tipolicencia, $sesion, $fecha) {
            try {
                DB::insert('INSERT INTO sta_tiposlicencias(detalle_tipolicencia, id_session, created_at, updated_at) VALUES (?, ?, ?, ?) ' , array($tipolicencia, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

    /**
     * Modifica un tipo de licencia
     * @param int $id
     * @param string $tipolicencia
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function updateTipoLicencia($id, $tipolicencia, $sesion, $fecha) {
            try {
                DB::update('UPDATE sta_tiposlicencias SET id_session=?, updated_at=?, detalle_tipolicencia=? WHERE id_tipolicencia=?' , array($sesion, $fecha, $tipolicencia, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

    /**
     * Habilita o bloquea un tipo de licencia específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deleteTipoLicencia($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM sta_tiposlicencias WHERE id_tipolicencia=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE sta_tiposlicencias SET id_session=?, updated_at=?, deleted_at=null WHERE id_tipolicencia=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE sta_tiposlicencias SET id_session=?, updated_at=?, deleted_at=? WHERE id_tipolicencia=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }

}
