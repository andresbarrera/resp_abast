<?php
namespace ABASTV2\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ArtArticuloModel extends Model {
	use SoftDeletes;

	/**
	 * The database table and table id used by the model.
	 * @var string
	 */
	protected $table = 'art_articulos';
	protected $primaryKey = 'id_articulo';

	/**
	 * Atributos asignables.
	 * @var array
	 */
	protected $fillable = [
								'nombre_articulo',
                                'descripcion_articulo',
                                'estado_articulo',
                                'fkid_tipoarticulo',
								'id_session', 
								'updated_at', 
								'created_at', 
								'deleted_at'
								];

	protected $dates = ['deleted_at'];
		
    /**
    *
    * Obtener todos los artículos con su respectiva familia
    * @return object
    */
	public function getArticulos() {
		$sql = "SELECT art_articulos.id_articulo, art_articulos.nombre_articulo, art_articulos.descripcion_articulo, art_articulos.estado_articulo, 
            art_articulos.fkid_tipoarticulo, art_articulos.id_session, art_articulos.updated_at, art_articulos.created_at, art_articulos.deleted_at, art_tiposarticulos.nombre_tipoarticulo 
                FROM art_articulos 
                INNER JOIN (art_tiposarticulos) 
                ON (art_tiposarticulos.id_tipoarticulo = art_articulos.fkid_tipoarticulo)";
		return DB::select($sql);
	}

    /**
    *
    * Obtener todos los artículos con su respectiva familia
    * @return object
    */
    public function getArticulosTipo($id) {
        return DB::table("art_articulos")->where("fkid_tipoarticulo",$id)->select("nombre_articulo","id_articulo")->get();
    }


    /**
     * Crea un articulo
     * @param string $articulo
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function insertArticulo($articulo, $descripcion, $estado, $tipoarticulo, $sesion, $fecha) 
    {
            try {
                DB::insert('INSERT INTO art_articulos(nombre_articulo, descripcion_articulo, estado_articulo, fkid_tipoarticulo, id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?) ' , array($articulo, $descripcion, $estado, $tipoarticulo, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
    
    /**
     * Modifica un articulo
     * @param int $id
     * @param string $articulo
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    
    public function updateArticulo($articulo, $descripcion, $tipoarticulo, $sesion, $fecha, $id) {
            try {
                DB::update('UPDATE art_articulos SET id_session=?, updated_at=?, nombre_articulo=?, descripcion_articulo=?, fkid_tipoarticulo=? WHERE id_articulo=?' , array($sesion, $fecha, $articulo, $descripcion, $tipoarticulo, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }
	

    /**
     * Habilita o bloquea un articulo específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deleteArticulo($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM art_articulos WHERE id_articulo=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE art_articulos SET id_session=?, updated_at=?, deleted_at=null WHERE id_articulo=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE art_articulos SET id_session=?, updated_at=?, deleted_at=? WHERE id_articulo=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }
    

}
	
