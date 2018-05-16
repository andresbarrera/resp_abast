<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SysMenuModel extends Model
{
    //
	use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'sys_menus';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
    	"nombre_menu",
    	"accion_menu",
    	"orden_menu",
    	"icono_menu",
    	"id_session",
    	"updated_at",
    	"created_at",
      "deleted_at"

   	];

   	
   	protected $dates = ['deleted_at'];

    /**
    * 
    * Relacion de permisos para cada perfil
    */
   	public function getSysPermisoPerfil(){
      return $this->hasMany('ABASTV2\Models\SysPermisoPerfil');
    }

    /**
    * 
    * Obtención de los menus para el respectivo usuario
    * @param int $id_usuario
    * @return object
    */
    public function getMenuUsuario($id_usuario) {
    $resultado = DB::select(
      'SELECT DISTINCT id_menu, nombre_menu, accion_menu, orden_menu, icono_menu  
                  FROM sys_menus
                  INNER JOIN sys_permisosperfiles
                  ON (sys_menus.id_menu = sys_permisosperfiles.fkid_menu)
                  INNER JOIN sys_perfiles
                  ON (sys_permisosperfiles.fkid_perfil = sys_perfiles.id_perfil)
                  INNER JOIN sys_usuarios
                  ON (sys_perfiles.id_perfil = sys_usuarios.fkid_perfil)
                  WHERE sys_usuarios.id_usuario = ?',
       array($id_usuario));
    if (isset($resultado)) {
      return $resultado;
    }
    return null;
  }

    

}
