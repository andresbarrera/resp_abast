<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CecCentroCostoModel extends Model
{
    //
	use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'cec_centroscostos';
    protected $primaryKey = 'id_centrocosto';

    protected $fillable = [
    	"cod_centrocosto",
    	"descripcion_centrocosto",
    	"fechainicio_centrocosto",
    	"fechafinal_centrocosto",
        "fkid_centrocosto",
        "id_session",
    	"created_at",
        "updated_at",
        "deleted_at"

   	];

   	
   	protected $dates = ['deleted_at'];


    /**
    * 
    * Obtención de los menus para el respectivo usuario
    * @param int $id_usuario
    * @return object
    */
    public function getCentrosCostos() {
    $resultado = DB::select(
      'SELECT id_centrocosto, cod_centrocosto, descripcion_centrocosto, DATE_FORMAT(fechainicio_centrocosto, "%d/%m/%Y") as fechainicio_centrocosto, 
              DATE_FORMAT(fechafinal_centrocosto, "%d/%m/%Y") as fechafinal_centrocosto, fkid_centrocosto,  id_session, created_at, updated_at, deleted_at 
                FROM cec_centroscostos;');
    return $resultado;  
    }

    


    /**
     * Crea un centro de costo
     * @param string $centrocosto
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function insertCentroCosto($centrocosto, $descripcion, $fechainicio, $fechafinal, $codcentro, $sesion, $fecha) 
    {
            try {
                DB::insert('INSERT INTO cec_centroscostos(cod_centrocosto,
        descripcion_centrocosto, fechainicio_centrocosto, fechafinal_centrocosto, fkid_centrocosto, id_session,
        created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ' , array($centrocosto, $descripcion, $fechainicio, $fechafinal, $codcentro, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
    
    /**
     * Modifica un centro de costo
     * @param int $id
     * @param string $centro de costo
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function updateCentroCosto($centrocosto, $descripcion, $fechainicio, $fechafinal, $codcentro, $sesion, $fecha, $id) {
            try {
                DB::update('UPDATE cec_centroscostos SET id_session=?, updated_at=?, cod_centrocosto=?, descripcion_centrocosto=?, fechainicio_centrocosto=?, fechafinal_centrocosto=?, fkid_centrocosto=? WHERE id_centrocosto=?' , array($sesion, $fecha, $centrocosto, $descripcion, $fechainicio, $fechafinal, $codcentro, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
    

    /**
     * Habilita o bloquea un centrocosto específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deleteCentroCosto($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM cec_centroscostos WHERE id_centrocosto=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE cec_centroscostos SET id_session=?, updated_at=?, deleted_at=null WHERE id_centrocosto=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE cec_centroscostos SET id_session=?, updated_at=?, deleted_at=? WHERE id_centrocosto=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }
 

}
