<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class cntConductorModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'cnt_conductores'; //tabla de la BD
    protected $primaryKey = 'id_conductor';

    protected $fillable = ["fkid_contratoempleado", 
    	"id_session",
    	"updates_at",
    	"created_at",
        "deleted_at" ];

    protected $dates = ['deleted_at'];

    /**
    * Obtención de los datos personales de los personas   
    * @return object
    */
    public function getConductores() {
        $sql = "SELECT * 
                FROM cnt_conductores";
        return DB::select($sql);
    }

    /**
    *
    * Crea un conductor
    * @param int $contratoempleado
    * @param int $sesion
    * @param date fecha
    * @return bool
    */
    public function insertConductor($contratoempleado, $sesion, $fecha)

    {
        try {
                DB::insert('INSERT INTO cnt_conductores(fkid_contratoempleado,  id_session, created_at, updated_at) VALUES (?, ?, ?, ?) ' , array($contratoempleado, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }   
    }

    /**
    * Modifica un persona
    * @param int $id
    * @param string $nombres
    * @param string $patapel 
    * @param string $matapel
    * @param int $sesion
    * @param date fecha
    * @return bool
     */
    
    public function updateConductor($id, $contratoempleado, $sesion, $fecha) {
            try {
                DB::update('UPDATE cnt_conductores SET id_session=?, updated_at=?, fkid_contratoempleado=? WHERE id_conductor=?' , array($sesion, $fecha, $contratoempleado, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }                                                                                                                        
    }

    /**
     * Habilita o bloquea un persona específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deletePersona($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM prs_personas WHERE id_persona=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE prs_personas SET id_session=?, updated_at=?, deleted_at=null WHERE id_persona=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE prs_personas SET id_session=?, updated_at=?, deleted_at=? WHERE id_persona=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }

    /**
     * Obtener id persona de cada usuario.
     *
     * @param  string  $value
     * @return id
     */
    public function getIdConductor($contratoempleado)
    {
        $condicion = DB::select('SELECT id_conductor FROM cnt_conductores WHERE fkid_contratoempleado=?' , array($contratoempleado));
        if($condicion){
            return $condicion[0];
        }
        else{
            return NULL;
        }

    }


}
