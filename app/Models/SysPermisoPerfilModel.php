<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SysPermisoPerfilModel extends Model
{
    //
	use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'sys_permisosperfiles';
    protected $primaryKey = 'id_permisoperfil';

    protected $fillable = [
    	"fkid_perfil",
    	"fkid_menu",
    	"fkid_submenu",
        "id_session",
    	"updated_at",
    	"created_at",
        "deleted_at"

   	];

   	
   	protected $dates = ['deleted_at'];


    /**
    * 
    * Obtención de los menus para el respectivo usuario
    * @param int $id_usuario
    * @return object
    */
    public function getMenus() {
    $resultado = DB::select(
      'SELECT * FROM sys_menus');
    return $resultado;  
    }


    /**
    * 
    * Obtención de los menus para el respectivo usuario
    * @param int $id_usuario
    * @return object
    */
    public function getSubMenus() {
    $resultado = DB::select(
      'SELECT * FROM sys_submenus');
    return $resultado;  
    }

    /**
    * 
    * Obtención de los menus para el respectivo usuario
    * @param int $id_usuario
    * @return object
    */
    public function getPerfiles() {
    $resultado = DB::select(
      'SELECT * FROM sys_perfiles');
    return $resultado;  
    }
    

}
