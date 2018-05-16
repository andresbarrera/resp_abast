<?php
namespace ABASTV2\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class LogError extends Model {
	use SoftDeletes;

	/**
	 * The database table and table id used by the model.
	 * @var string
	 */
	protected $table = 'log_errores';
	protected $primaryKey = 'id_error';

	/**
	 * Atributos asignables.
	 * @var array
	 */
	protected $fillable = [
								'codigo_error',
                                'url_error', 
								'browser_error', 
								'descripcion_error',
                                'id_session',
                                'updated_at',
                                'created_at',
                                'deleted_at'
								];

	protected $dates = ['deleted_at'];

    public static function insert($codigo, $url, $browser, $descripcion, $sesion, $fecha) {
        try {
                DB::insert('INSERT INTO log_errores(codigo_error, url_error, browser_error, descripcion_error, id_session, updated_at, created_at) VALUES (?, ?, ?, ?, ?, ?, ?) ' , array($codigo, $url, $browser, $descripcion, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

}
	
