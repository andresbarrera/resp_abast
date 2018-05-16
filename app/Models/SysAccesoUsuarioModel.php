<?php
namespace ABASTV2\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SysAccesoUsuarioModel extends Model {
	use SoftDeletes;

	/**
	 * The database table and table id used by the model.
	 * @var string
	 */
	protected $table = 'sys_accesosusuarios';
	protected $primaryKey = 'id_accesousuario';

	/**
	 * Atributos asignables.
	 * @var array
	 */
	protected $fillable = [
								'tipo_accesousuario',
								'fecha_accesousuario',
								'ip_accesousuario',
								'navegador_usuario',  
								'fkid_usuario', 
								'id_session', 
								'updated_at', 
								'created_at', 
								'deleted_at'
								];

	protected $dates = ['deleted_at'];
	/**
	 * Obtiene la fecha de último acceso y el último estado de acceso del usuario.
	 */

	public function getLastEstadoAcceso($id_usuario) {
		$resultado = DB::select('SELECT *  
						FROM sys_accesosusuarios 
						WHERE sys_accesosusuarios.fkid_usuario = ?', array($id_usuario));
		if($resultado){
			return $resultado[0];	
		}
		else{
			return null;

		}
		
	}

	public function getAccesoValido($id_submenu, $id_perfil) {
		$consulta = DB::select('Select * from sys_permisosperfiles where fkid_submenu=? AND fkid_perfil=?', array($id_submenu, $id_perfil));
		if($consulta){
			return true;
		}
		else{
			return false;
		}		
	}

	public function getAccesoValidoMenu($id_menu, $id_perfil) {
		$consulta = DB::select('Select * from sys_permisosperfiles where fkid_menu=? AND fkid_perfil=?', array($id_menu, $id_perfil));
		if($consulta){
			return true;
		}
		else{
			return false;
		}		
	}
	
}