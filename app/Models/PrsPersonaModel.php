<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PrsPersonaModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'prs_personas'; //tabla de la BD
    protected $primaryKey = 'id_persona';

    protected $fillable = ["rutd_persona", 
    	"verifd_persona",
    	"nombres_persona",
    	"paterno_persona",
    	"materno_persona",
    	"fnacimiento_persona",
    	"id_session",
    	"updates_at",
    	"created_at"];

    protected $dates = ['deleted_at'];

    /**
    * Obtención de los datos personales de los personas   
    * @return object
    */
    public function getPersonas() {
        $sql = "SELECT * 
                FROM prs_personas";
        return DB::select($sql);
    }

     /**
    * Obtención de personas por nombre y apellido   
    * @return object
    */
    public function getNombreApellido($nombre, $apellido) {
        $condicion = DB::select('SELECT * FROM prs_personas WHERE nombres_persona=? AND paterno_persona=?' , array($nombre, $apellido));
        return $condicion;
    }


    /**
    * Verificación de si un rut es único  
    * @return object
    */
    public function getRutUnico($rut) {
        $condicion = DB::select('SELECT * FROM prs_personas WHERE rutd_persona=?' , array($rut));
        return $condicion;
    }



    /**
    *
    * Crea una persona
    * @param string $rut
    * @param string $digito
    * @param date $nacimiento
    * @param string $nombres
    * @param string $patapel 
    * @param string $matapel
    * @param int $sesion
    * @param date fecha
    * @return bool
    */
    public function insertPersona($rut, $digito, $nombres, $patapel, $matapel, $nacimiento, $sesion, $fecha)

    {
        try {
                DB::insert('INSERT INTO prs_personas(rutd_persona, verifd_persona, nombres_persona, paterno_persona, materno_persona, fnacimiento_persona,  id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ' , array($rut, $digito, $nombres, $patapel, $matapel, $nacimiento, $sesion, $fecha, $fecha));
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
    
    public function updatePersona($id, $rut, $digito, $nombres, $patapel, $matapel, $nacimiento, $sesion, $fecha) {
            try {
                DB::update('UPDATE prs_personas SET id_session=?, updated_at=?, rutd_persona=?, verifd_persona=?, nombres_persona=?, paterno_persona=?, materno_persona=?, fnacimiento_persona=? WHERE id_persona=?' , array($sesion, $fecha, $rut, $digito, $nombres, $patapel, $matapel, $nacimiento, $id));
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
    
    public function updatePersonaUsuario($id, $nombres, $patapel, $matapel, $sesion, $fecha) {
            try {
                DB::update('UPDATE prs_personas SET id_session=?, updated_at=?, nombres_persona=?, paterno_persona=?, materno_persona=? WHERE id_persona=?' , array($sesion, $fecha, $nombres, $patapel, $matapel, $id));
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
    public function getIdPersona($nombres, $paterno, $materno)
    {
        $condicion = DB::select('SELECT id_persona FROM prs_personas WHERE nombres_persona=? AND paterno_persona=? AND materno_persona=?' , array($nombres, $paterno, $materno));
        if($condicion){
            return $condicion[0];
        }
        else{
            return NULL;
        }

    }

}
