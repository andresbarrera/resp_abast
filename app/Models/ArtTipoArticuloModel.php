<?php
namespace ABASTV2\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ArtTipoArticuloModel extends Model {
	use SoftDeletes;

	/**
	 * The database table and table id used by the model.
	 * @var string
	 */
	protected $table = 'art_tiposarticulos';
	protected $primaryKey = 'id_tipoarticulo';

	/**
	 * Atributos asignables.
	 * @var array
	 */
	protected $fillable = [
								'nombre_tipoarticulo',
                                'fkid_aprobacion',
								'id_session', 
								'updated_at', 
								'created_at', 
								'deleted_at'
								];

	protected $dates = ['deleted_at'];
		
    /**
    *
    * Obtener todas las familias con sus respectivas aprobaciones
    * @return object
    */
	public function getTiposArticulos() {
		$sql = "SELECT art_tiposarticulos.id_tipoarticulo, art_tiposarticulos.nombre_tipoarticulo, art_tiposarticulos.fkid_aprobacion
            , art_tiposarticulos.id_session, art_tiposarticulos.updated_at, art_tiposarticulos.created_at, art_tiposarticulos.deleted_at, art_aprobaciones.nombre_aprobacion FROM art_tiposarticulos 
                INNER JOIN (art_aprobaciones) 
                ON (art_aprobaciones.id_aprobacion = art_tiposarticulos.fkid_aprobacion)";
		return DB::select($sql);
	}

    /**
    *
    * Obtener todas las familias con sus respectivas aprobaciones
    * @return object
    */
    public function getTiposArticulosSimple() {
        return DB::table("art_tiposarticulos")->select("nombre_tipoarticulo","id_tipoarticulo")->get();
    }


    /**
     * Crea un tipoarticulo
     * @param string $tipoarticulo
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function insertTipoArticulo($tipoarticulo, $aprobacion, $sesion, $fecha) 
    {
            try {
                DB::insert('INSERT INTO art_tiposarticulos(nombre_tipoarticulo, fkid_aprobacion, id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?) ' , array($tipoarticulo, $aprobacion, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
    
    /**
     * Modifica un tipoarticulo
     * @param int $id
     * @param string $tipoarticulo
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function updateTipoArticulo($id, $tipoarticulo, $aprobacion, $sesion, $fecha) {
            try {
                DB::update('UPDATE art_tiposarticulos SET id_session=?, updated_at=?, nombre_tipoarticulo=?, fkid_aprobacion=? WHERE id_tipoarticulo=?' , array($sesion, $fecha, $tipoarticulo, $aprobacion, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
	

    /**
     * Habilita o bloquea un tipoarticulo específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deleteTipoArticulo($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM art_tiposarticulos WHERE id_tipoarticulo=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE art_tiposarticulos SET id_session=?, updated_at=?, deleted_at=null WHERE id_tipoarticulo=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE art_tiposarticulos SET id_session=?, updated_at=?, deleted_at=? WHERE id_tipoarticulo=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }
    

}
	
