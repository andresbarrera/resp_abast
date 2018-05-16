<?php
namespace ABASTV2\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class StaTipoObligatoriedadModel extends Model {
	use SoftDeletes;

	/**
	 * The database table and table id used by the model.
	 * @var string
	 */
	protected $table = 'sta_tiposobligatoriedades';
	protected $primaryKey = 'id_tipoobligatoriedad';

	/**
	 * Atributos asignables.
	 * @var array
	 */
	protected $fillable = [
								'detalle_tipoobligatoriedad',
                                'id_session', 
								'updated_at', 
								'created_at', 
								'deleted_at'
								];

	protected $dates = ['deleted_at'];
		
    /**
    *
    * Obtener todos los tipos de obligatoriedades
    * @return object
    */
	public function getTipoObligatoriedad() {
		$sql = "SELECT * 
                FROM sta_tiposobligatoriedades";
		return DB::select($sql);
	}


    /**
     * Crea un tipo de obligatoriedad
     * @param string $tipo de obligatoriedad
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function insertTipoObligatoriedad($tipoobligatoriedad, $sesion, $fecha) 
    {
            try {
                DB::insert('INSERT INTO sta_tiposobligatoriedades(detalle_tipoobligatoriedad, id_session, created_at, updated_at) VALUES (?, ?, ?, ?) ' , array($tipoobligatoriedad, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
    
    /**
     * Modifica un tipo de obligatoriedad
     * @param int $id
     * @param string $tipo de obligatoriedad
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function updateTipoObligatoriedad($id, $tipoobligatoriedad, $sesion, $fecha) {
            try {
                DB::update('UPDATE sta_tiposobligatoriedades SET id_session=?, updated_at=?, detalle_tipoobligatoriedad=? WHERE id_tipoobligatoriedad=?' , array($sesion, $fecha, $tipoobligatoriedad, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
	

    /**
     * Habilita o bloquea un tipo de obligatoriedad específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deleteTipoObligatoriedad($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM sta_tiposobligatoriedades WHERE id_tipoobligatoriedad=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE sta_tiposobligatoriedades SET id_session=?, updated_at=?, deleted_at=null WHERE id_tipoobligatoriedad=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE sta_tiposobligatoriedades SET id_session=?, updated_at=?, deleted_at=? WHERE id_tipoobligatoriedad=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }
    

}
	
