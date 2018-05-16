<?php
namespace ABASTV2\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ArtDetalleModel extends Model {
	use SoftDeletes;

	/**
	 * The database table and table id used by the model.
	 * @var string
	 */
	protected $table = 'art_detalles';
	protected $primaryKey = 'id_detalle';

	/**
	 * Atributos asignables.
	 * @var array
	 */
	protected $fillable = [
								'peso_detalle',
                                'contneto_detalle',
                                'observacion_detalle',
                                'id_session', 
								'updated_at', 
								'created_at', 
								'deleted_at'
								];

	protected $dates = ['deleted_at'];
		

	public function getDetalles() {
		$sql = "SELECT * 
                FROM art_detalles";
		return DB::select($sql);
	}


    /**
     * Crea un detalle
     * @param string $peso
     * @param string $contneto
     * @param string $observacion
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function insertDetalle($peso, $contneto, $observacion, $sesion, $fecha) 
    {
            try {
                DB::insert('INSERT INTO art_detalles(peso_detalle, contneto_detalle,  observacion_detalle, id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?) ' , array($peso, $contneto, $observacion $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
    
    /**
     * Modifica un detalle
     * @param int $id
     * @param string $peso
     * @param string $contneto
     * @param string $observacion
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function updateDetalle($peso, $contneto, $observacion, $sesion, $fecha, $id) {
            try {
                DB::update('UPDATE art_detalles SET peso_detalle=?, contneto_detalle=?, observacion_detalle=?, id_session=?, updated_at=? WHERE id_detalle=?' , array($peso, $contneto, $observacion, $sesion, $fecha, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
	

    /**
     * Habilita o bloquea un detalle específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deleteDetalle($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM art_detalles WHERE id_detalle=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE art_detalles SET id_session=?, updated_at=?, deleted_at=null WHERE id_detalle=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE art_detalles SET id_session=?, updated_at=?, deleted_at=? WHERE id_detalle=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }
    

}
	
