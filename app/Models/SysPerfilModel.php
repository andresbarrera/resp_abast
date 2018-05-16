<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SysPerfilModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'sys_perfiles'; //tabla de la BD
    protected $primaryKey = 'id_perfil';

    protected $fillable = ["nombre_perfil",
    	"id_session", 
    	"updated_at",
    	"created_at",
    	"deleted_at"];

    
    protected $dates = ['deleted_at'];

    
    /**
    * 
    * Relacion de perfiles con usuarios
    */
    public function getSysUsuario() {
		return $this->hasMany('ABASTV2\Models\SysUsuarioModel');
	}

	
    /**
    *
    * Obtención de los perfiles del sistema, sin contar el de desarrollador
    * @return object
    */
	public function getPerfiles() {
		$sql = "SELECT * 
				FROM sys_perfiles
                WHERE nombre_perfil != 'DESARROLLADOR'";
		return DB::select($sql);
	}


    /**
     * Crea un perfil
     * @param string $perfil
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function insertPerfil($perfil, $sesion, $fecha) {
            try {
                DB::insert('INSERT INTO sys_perfiles(nombre_perfil, id_session, created_at, updated_at) VALUES (?, ?, ?, ?) ' , array($perfil, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

    /**
     * Crea las conexiones entre perfiles, menus y submenus
     * @param string $perfil
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function insertPermisosPerfiles($perfil, $submenu, $sesion, $fecha) {
            try {
                $fkid_menu = DB::select('SELECT fkid_menu 
                FROM sys_submenus
                WHERE id_submenu=?', array($submenu));
                $menu_array = json_decode(json_encode($fkid_menu), true);
                $menu = $menu_array[0]['fkid_menu'];
                DB::insert('INSERT INTO sys_permisosperfiles(fkid_perfil,  fkid_menu, fkid_submenu, id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?) ' , array($perfil, $menu, $submenu, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }    

    
    /**
     * Modifica un perfil
     * @param int $id
     * @param string $perfil
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function updatePerfil($id, $perfil, $sesion, $fecha) {
            try {
                DB::update('UPDATE sys_perfiles SET id_session=?, updated_at=?, nombre_perfil=? WHERE id_perfil=?' , array($sesion, $fecha, $perfil, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

    /**
     * Habilita o bloquea un perfil específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @return bool
     */
    public function deletePerfil($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM sys_perfiles WHERE id_perfil=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE sys_perfiles SET id_session=?, updated_at=?, deleted_at=null WHERE id_perfil=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE sys_perfiles SET id_session=?, updated_at=?, deleted_at=? WHERE id_perfil=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }

    /**
     * Selecciona los submenus especificos para cada perfil
     * @param int $id
     * @return object
     */
    public function selectPerfilesSubmenus($id){
        $contenido = DB::select('SELECT id_perfil, nombre_perfil, fkid_submenu, nombre_submenu 
            FROM sys_perfiles
            INNER JOIN (sys_permisosperfiles)
            ON (id_perfil = fkid_perfil)
            INNER JOIN (sys_submenus)
            ON (id_submenu = fkid_submenu)
            WHERE id_perfil = ?' , array($id));
        return $contenido;
    }

}
