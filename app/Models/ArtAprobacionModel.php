<?php
namespace ABASTV2\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ArtAprobacionModel extends Model {
	use SoftDeletes;

	/**
	 * The database table and table id used by the model.
	 * @var string
	 */
	protected $table = 'art_aprobaciones';
	protected $primaryKey = 'id_aprobacion';

	/**
	 * Atributos asignables.
	 * @var array
	 */
	protected $fillable = [
								'nombre_aprobacion',
								'id_session', 
								'updated_at', 
								'created_at', 
								'deleted_at'
								];

	protected $dates = ['deleted_at'];
		
	/**
	* Método para obtener las aprobaciones disponibles
	* @return object
	*/
	public function getAprobaciones() {
		$sql = "SELECT * 
				FROM art_aprobaciones";
		return DB::select($sql);
	}
    

}
	
