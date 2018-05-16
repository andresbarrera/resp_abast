<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class DocTipoDocumentoModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'doc_tiposdocumentos'; //tabla de la BD
    protected $primaryKey = 'id_tipodocumento';

    protected $fillable = ["detalle_tipodocumento",
        "descripcion_tipodocumento",
        "vigencia_tipodocumento",
        "duracion_tipodocumento",
        "obligatoriedad_tipodocumento",
    	"id_session", 
    	"updated_at",
    	"created_at",
    	"deleted_at"];

    
    protected $dates = ['deleted_at'];

	
    /**
    *
    * Obtención de los tipos de documentos del sistema
    * @return object
    */
	public function getTiposDocumentos() {
		$sql = "SELECT * 
				FROM doc_tiposdocumentos";
		return DB::select($sql);
	}

    /**
    *
    * Obtención de los tipos de documentos del sistema
    * @return object
    */
    public function getDocumentos() {
        $sql = "SELECT id_tipodocumento, detalle_tipodocumento, vigencia_tipodocumento, duracion_tipodocumento, obligatoriedad_tipodocumento, detalle_extension  
                FROM doc_tiposdocumentos
                INNER join (extensiones_tiposdocumentos)
                ON  (id_tipodocumento = extensiones_tiposdocumentos.fkid_tipodocumento)
                INNER join (doc_extensiones)
                ON (extensiones_tiposdocumentos.fkid_extension = doc_extensiones.id_extension)";
        return DB::select($sql);
    }

    /**
     * Crea un tipo de documento
     * @param string $tipodocumento
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function insertTipoDocumento($tipodocumento, $descripcion, $vigencia, $duracion, $obligatoriedad, $sesion, $fecha) {
            try {
                DB::insert('INSERT INTO doc_tiposdocumentos(detalle_tipodocumento, descripcion_tipodocumento, vigencia_tipodocumento,
        duracion_tipodocumento, obligatoriedad_tipodocumento, id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ' , array($tipodocumento, $descripcion, $vigencia, $duracion, $obligatoriedad ,$sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }


    /**
    * Crea las conexiones entre tipos de documentos y extensiones
    * @param int $tipodocumento 
    * @param int $extension
    * @param int $sesion
    * @param timestamp $fecha
    * @return bool
    */
    public function insertExtensionTipoDocumento($tipodocumento, $extension, $sesion, $fecha) {
            try {
                DB::insert('INSERT INTO extensiones_tiposdocumentos(fkid_tipodocumento, fkid_extension, id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?) ' , array($tipodocumento, $extension, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }    


    /**
     * Modifica un tipo de documento
     * @param int $id
     * @param string $tipodocumento
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function updateTipoDocumento($id, $tipodocumento, $descripcion, $vigencia, $duracion, $obligatoriedad, $sesion, $fecha) {
            try {
                DB::update('UPDATE doc_tiposdocumentos SET id_session=?, updated_at=?, detalle_tipodocumento=?, descripcion_tipodocumento=?, vigencia_tipodocumento=?,
        duracion_tipodocumento=?, obligatoriedad_tipodocumento=? WHERE id_tipodocumento=?' , array($sesion, $fecha, $tipodocumento, $descripcion, $vigencia, $duracion, $obligatoriedad, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

    /**
     * Habilita o bloquea un tipo de documento específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deleteTipoDocumento($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM doc_tiposdocumentos WHERE id_tipodocumento=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE doc_tiposdocumentos SET id_session=?, updated_at=?, deleted_at=null WHERE id_tipodocumento=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE doc_tiposdocumentos SET id_session=?, updated_at=?, deleted_at=? WHERE id_tipodocumento=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }

    /**
     * Contar los tipos de documentos
     * @return int
     */
    public function countTipoDocumento() {
        $condicion = DB::select('SELECT count(*) FROM doc_tiposdocumentos WHERE deleted_at IS NULL');
        return $condicion;
    }


}
