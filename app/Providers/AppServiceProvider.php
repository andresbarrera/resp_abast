<?php

namespace ABASTV2\Providers;
use Illuminate\Support\ServiceProvider;
use ABASTV2\Models\SysUsuarioModel;
use Validator;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('same_password', function($attribute, $value, $parameters, $validator) {
            $usuario = new SysUsuarioModel();
            $id_usuario = $parameters[0];
            $expired = SysUsuarioModel::find($id_usuario);
            $password = bcrypt(strtolower($value));
            $resultado = password_verify(strtolower($value), $expired->password_usuario);
            return ($resultado) ? false : true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
