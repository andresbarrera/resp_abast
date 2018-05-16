<?php

namespace ABASTV2\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class SysUsuarioModel extends Model
{
    //
    use SoftDeletes; //para evitar la eliminación de registros y solo deshabilitarlos
    protected $table = 'sys_usuarios'; //tabla de la BD
    protected $primaryKey = 'id_usuario';

    protected $fillable = ["user_usuario", 
    	"password_usuario",
    	"email_usuario",
    	"estadoregistro_usuario",
    	"estadoaccesoactual_usuario",
    	"fechamodificacionpassword_usuario",
        "nombres_usuario",
        "patapel_usuario",
        "matapel_usuario",
    	"fkid_perfil",
    	"fkid_persona",
    	"fkid_area",
    	"id_session",
    	"updates_at",
    	"created_at",
        "deleted_at" ];

    protected $hidden = ['password_usuario', 'remember_token'];
    protected $dates = ['deleted_at'];

    /**
    *
    * Cambio del valor por defecto del campo password, al campo de la tabla sys_usuarios
    *
    */
    public function getAuthPassword(){
        return $this->password_usuario;
    }


    /*
    *
    * Relación de la tabla sys_usuarios con la tabla sys_perfiles
    */
    public function getSysPerfil() {
        return $this->belongsTo('ABASTV2\Models\SysPerfilModel');
    }

    /*
    *
    * Relación de la tabla sys_usuarios con la tabla prs_personas
    */
    public function getPrsPersona() {
        return $this->hasOne('ABASTV2\Models\PrsPersonaModel');
    }
    
    /*
    *
    * Relación de la tabla sys_usuarios con la tabla are_area
    */
    public function getAreArea() {
        return $this->hasOne('ABASTV2\Models\AreAreaModel');
    }

    /**
    * Obtención de los datos del usuario logueado al sistema
    * @param int $id   
    * @return object
    */
    public function getDataUser($id) {
        $usuario = DB::select('
            SELECT * FROM sys_usuarios
            WHERE sys_usuarios.id_usuario = ?', array($id));
        if (isset($usuario[0])) {
            return $usuario[0];
        }
        return null;
    }


    /**
    * Obtención de los datos de los usuarios   
    * @return object
    */
    public function getUsuarios() {
                $sql = "SELECT sys_usuarios.id_usuario, sys_usuarios.user_usuario, sys_usuarios.email_usuario, sys_usuarios.estadoregistro_usuario, sys_usuarios.fechamodificacionpassword_usuario, sys_usuarios.nombres_usuario, sys_usuarios.patapel_usuario, sys_usuarios.matapel_usuario,  
               sys_usuarios.fkid_perfil, sys_usuarios.fkid_persona, sys_usuarios.id_session, sys_usuarios.updated_at, sys_usuarios.created_at, sys_usuarios.deleted_at, sys_perfiles.nombre_perfil, are_areas.id_area,  
               are_areas.nombre_area
                    FROM sys_usuarios 
                    INNER JOIN (sys_perfiles) 
                    ON (sys_perfiles.id_perfil = sys_usuarios.fkid_perfil)
                    INNER JOIN (are_areas)
                    ON (are_areas.id_area = sys_usuarios.fkid_area)";
        return DB::select($sql);
    }



    /**
    *
    * Verificación de el tiempo en que se hizo el último ingreso, a los 6 meses hay un
    * cambio de contraseña. Se realiza mediante procedimiento en la base de datos
    *
    */
    public function change() {
        try {

            DB::statement('call renovacion');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function username($nombres, $patapel, $operacion){
        $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $primera_letra = strtoupper(substr($nombres, 0, 1));
        $resto = strtoupper($patapel);
        $primera_letra = str_replace($no_permitidas, $permitidas ,$primera_letra);
        $resto = str_replace($no_permitidas, $permitidas ,$resto);
        $texto = strtoupper($primera_letra.$resto);
        $username = str_replace($no_permitidas, $permitidas ,$texto);
        $existe = SysUsuarioModel::where('user_usuario', $username)->first();
        if($existe && $operacion=='crear'){
            $primera_letra = strtoupper(substr($nombres, 0, 2));
            $texto = strtoupper($primera_letra.$resto);
            $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
            $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
            $username = str_replace($no_permitidas, $permitidas ,$texto);
            return $username;
        }
        else{
            return $username;
        }

    }


    /**
     * Cambia la contraseña del usuario y si corresponde, su estado.
     * @param int $id
     * @param string $password
     * @param datetime $fecha
     * @param enum $estado_registro
     * @return bool
     */
    public function changePassword($id, $password, $fecha, $estado_registro = null) {
        try {

            DB::update('update sys_usuarios set password_usuario=?, estadoregistro_usuario=?, fechamodificacionpassword_usuario=?, updated_at=? where  id_usuario=?', array($password, $estado_registro, $fecha, $fecha, $id));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Actualiza el acceso del usuario al sistema.
     * @param datetime $fecha
     * @param string $ip
     * @param string $browser
     * @param enum $tipo_acceso
     * @param int $id_usuario
     * @return bool
     */
    public function updateAcceso($fecha, $ip, $browser, $tipo_acceso, $id_usuario) {
        try {
            DB::update('update sys_accesosusuarios set fecha_accesousuario=?, updated_at=?,  ip_accesousuario=?, navegador_accesousuario=?, tipo_accesousuario=? where  fkid_usuario=?', array($fecha, $fecha, $ip, $browser, $tipo_acceso, $id_usuario));
            return true;
        } catch (Exception $e) {
            return false;
        }
    } 

    /**
     * Obtiene la lista de usuarios del sistema
     * @return object
     *
    public function getUsuarios() {
        $sql = "SELECT 
                    * 
                FROM sys_usuarios";
        return DB::select($sql);
    }


    /**
    *
    * Crea un usuario
    * @param string $usuario
    * @param string $password 
    * @param string $email
    * @param string $estadoregistro
    * @param int $perfil
    * @param int $persona
    * @param int $area
    * @param int $session
    * @param date fecha
    * @return bool
    */
    public function insertUsuario($usuario, $password, $email, $estadoregistro, $nombres, $patapel, $matapel, $fkid_persona, $perfil, $area, $sesion, $fecha)

    {
        try {
                DB::insert('INSERT INTO sys_usuarios(user_usuario, password_usuario, email_usuario, estadoregistro_usuario, nombres_usuario, patapel_usuario, matapel_usuario, fkid_persona, fkid_perfil, fkid_area, id_session, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ' , array($usuario, $password, $email, $estadoregistro, $nombres, $patapel, $matapel, $fkid_persona, $perfil, $area, $sesion, $fecha, $fecha));
                return true;
            }catch (Exception $e) {
                return false;
            }   
    }

    /**
    * Modifica un usuario
    * @param int $id
    * @param string $usuario
    * @param string $email
    * @param int $perfil
    * @param int $persona
    * @param int $area
    * @param int $session
    * @param date fecha
    * @return bool
     */
    
    public function updateUsuario($id, $usuario, $email, $nombres, $patapel, $matapel, $perfil, $area, $sesion, $fecha) {
            try {
                DB::update('UPDATE sys_usuarios SET id_session=?, updated_at=?, user_usuario=?, email_usuario=?, nombres_usuario=?, patapel_usuario=?, matapel_usuario=?, fkid_perfil=?, fkid_area=?  WHERE id_usuario=?' , array($sesion, $fecha, $usuario, $email, $nombres, $patapel, $matapel, $perfil, $area, $id));
                return true;
            }catch (Exception $e) {
                return false;
            }    
    }

    /**
     * Habilita o bloquea un usuario específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @param string $estado_registro
     * @return bool
     */
    public function deleteUsuario($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT deleted_at FROM sys_usuarios WHERE id_usuario=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->deleted_at){
            try {
                DB::update('UPDATE sys_usuarios SET id_session=?, updated_at=?, deleted_at=null WHERE id_usuario=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE sys_usuarios SET id_session=?, updated_at=?, deleted_at=? WHERE id_usuario=?' , array($sesion, $fecha, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }

    /**
     * Habilita o bloquea un usuario específico en base de datos
     * @param int $id
     * @param int $sesion
     * @param timestamp $fecha
     * @param string $estado_registro
     * @return bool
     */
    public function blockUsuario($id, $sesion, $fecha) {
        $condicion = DB::select('SELECT estadoregistro_usuario FROM sys_usuarios WHERE id_usuario=?' , array($id));
        $validacion = $condicion[0];
        if($validacion->estadoregistro_usuario != 'Verificado' ){
            try {
                DB::update('UPDATE sys_usuarios SET id_session=?, updated_at=?, estadoregistro_usuario="Verificado" WHERE id_usuario=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }
        }
        else{
            try {
                DB::update('UPDATE sys_usuarios SET id_session=?, updated_at=?, estadoregistro_usuario="Bloqueado" WHERE id_usuario=?' , array($sesion, $fecha, $id));
                return true;
            } catch (Exception $e) {
                return false;
            }    
        }
    }

     
}
