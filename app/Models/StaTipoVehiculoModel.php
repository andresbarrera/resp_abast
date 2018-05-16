<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StaTipoVehiculoModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'sta_tiposvehiculos'; //tabla de la BD
    protected $primaryKey = 'id_tipovehiculo';

    protected $fillable = ["detalle_tipovehiculo",
    	"id_session", 
    	"updated_at",
    	"created_at",
    	"deleted_at"];

    
    protected $dates = ['deleted_at'];

	
    /**
    *
    *  Obtención tipos de vehículos
    * @return object
    */
	public function getTiposVehiculos() {
		$sql = "SELECT * 
				FROM sta_tiposvehiculos";
		return DB::select($sql);
	}


    /**
     * Crea un tipo de vehiculo
     * @param string $tipovehiculo
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function insertTipoVehiculo($tipovehiculo, $sesion, $fecha) {
            try {
                DB::insert('INSERT INTO sta_tiposvehiculos(detalle_tipovehiculo, id_session, created_at, updated_at) VALUES (?, ?, ?, ?) ' , array($tipovehiculo, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

    /**
     * Modifica un tipo de vehiculo
     * @param int $id
     * @param string $tipovehiculo
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function updateTipoVehiculo($id, $tipovehiculo, $sesion, $fecha) {
            try {
                DB::update('UPDATE sta_tiposvehiculos SET id_session=?, updated_at=?, detalle_tipovehiculo=? WHERE id_tipovehiculo=?' , array($sesion, $fecha, $tipovehiculo, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

    /**
     * Habilita o bloquea un tipo de vehiculo específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deleteTipoVehiculo($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM sta_tiposvehiculos WHERE id_tipovehiculo=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE sta_tiposvehiculos SET id_session=?, updated_at=?, deleted_at=null WHERE id_tipovehiculo=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE sta_tiposvehiculos SET id_session=?, updated_at=?, deleted_at=? WHERE id_tipovehiculo=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }

}
