<?php
namespace ABASTV2\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AreAreaModel extends Model {
	use SoftDeletes;

	/**
	 * The database table and table id used by the model.
	 * @var string
	 */
	protected $table = 'are_areas';
	protected $primaryKey = 'id_area';

	/**
	 * Atributos asignables.
	 * @var array
	 */
	protected $fillable = [
								'nombre_area',
                                'id_session', 
								'updated_at', 
								'created_at', 
								'deleted_at'
								];

	protected $dates = ['deleted_at'];
		
    /**
    *
    * Obtener todas las areas
    * @return object
    */
	public function getAreas() {
		$sql = "SELECT * 
                FROM are_areas";
		return DB::select($sql);
	}


    /**
     * Crea un area
     * @param string $area
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function insertArea($area, $sesion, $fecha) 
    {
            try {
                DB::insert('INSERT INTO are_areas(nombre_area, id_session, created_at, updated_at) VALUES (?, ?, ?, ?) ' , array($area, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
    
    /**
     * Modifica un area
     * @param int $id
     * @param string $area
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function updateArea($id, $area, $sesion, $fecha) {
            try {
                DB::update('UPDATE are_areas SET id_session=?, updated_at=?, nombre_area=? WHERE id_area=?' , array($sesion, $fecha, $area, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
	

    /**
     * Habilita o bloquea un area específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deleteArea($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM are_areas WHERE id_area=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE are_areas SET id_session=?, updated_at=?, deleted_at=null WHERE id_area=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE are_areas SET id_session=?, updated_at=?, deleted_at=? WHERE id_area=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }
    

}
	
