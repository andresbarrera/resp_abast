<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SysSubmenuModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'sys_submenus';
    protected $primaryKey = 'id_submenu';

    protected $fillable = [
    	"nombre_submenu",
    	"accion_submenu",
    	"orden_submenu",
    	"icono_submenu",
      "fkid_menu",
    	"id_session",
    	"updated_at",
    	"created_at"

   	];

   	
   	protected $dates = ['deleted_at'];

  
    /**
    * 
    * Relacion de permisos para cada perfil
    */
    public function getSysPermisoPerfil(){
      return $this->hasMany('ABASTV2\Models\SysPermisoPerfilModel');
    }
    
  /**
  *
  * Obtencion de los submenus para cada menu del respectivo usuario
  * @param int $id_usuario
  * @param int $id_menu
  * @return object 
  */ 
  public function getSubMenuUsuario($id_usuario, $id_menu) {
    $resultado = DB::select('SELECT id_submenu, nombre_submenu, accion_submenu, orden_submenu, icono_submenu  
      FROM sys_submenus
      INNER JOIN sys_permisosperfiles
      ON (sys_submenus.id_submenu = sys_permisosperfiles.fkid_submenu)
      INNER JOIN sys_menus
      ON (sys_permisosperfiles.fkid_menu = sys_menus.id_menu)
      INNER JOIN sys_perfiles
      ON (sys_permisosperfiles.fkid_perfil = sys_perfiles.id_perfil)
      INNER JOIN sys_usuarios
      ON (sys_perfiles.id_perfil = sys_usuarios.fkid_perfil)
      WHERE sys_usuarios.id_usuario = ?
            AND sys_menus.id_menu = ?
            AND sys_submenus.id_submenu = sys_permisosperfiles.fkid_submenu', array($id_usuario, $id_menu));
    if (isset($resultado)) {
      return $resultado;
    }
    return null;
  }

}