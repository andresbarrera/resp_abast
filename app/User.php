<?php

namespace ABASTV2;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    //

    use Notifiable; //para evitar la eliminación de registros y solo deshabilitarlos

    protected $table = 'sys_usuarios'; //tabla de la BD
    protected $primaryKey = 'id_usuario';

    protected $fillable = ["user_usuario", 
        "password_usuario",
        "email_usuario",
        "estadoregistro_usuario",
        "estadoaccesoactual_usuario",
        "fechamodificacionpassword_usuario",
        "ultimoacceso_usuario",
        "fkid_perfil",
        "fkid_persona",
        "fkid_area",
        "id_session",
        "updates_at",
        "created_at",
        "deleted_at" ];

    protected $hidden = ['password_usuario'];

    public function getAuthPassword(){
        return $this->password_usuario;
    }
    
}
