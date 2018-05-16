<?php

namespace ABASTV2\Http\Controllers;

session_start();
use ABASTV2\Models\User;
use ABASTV2\Models\SysUsuarioModel;
use ABASTV2\Models\SysMenuModel;
use ABASTV2\Models\SysSubmenuModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use ABASTV2\Mail\CambioPass;
use ABASTV2\Mail\CuentaVerificada;
use ABASTV2\Mail\CuentaReestablecida;
use Illuminate\Support\Facades\Auth;
use Session;
use Carbon\Carbon;
use Validator;
use Mail;

class LoginController extends Controller
{
    
    /**
    *Constructor del controlador
    * @return void
    */
	public function __(Guard $auth) {
        parent::__construct();
        $this->auth = $auth;
		$this->middleware('guest', ['except' => 'logout']);
	}

    /**
    *Redirección a vista inicial del sistema
    * @return response
    */
    public function getIndex() {
        return (Auth::check()) ? redirect('home') : redirect('/');

    }

    /**
    *Recibe las credenciales de acceso
    * @param Request $request
    * @return response
    */
    public function postIndex(Request $request) {
        $username = strtolower(trim($request['username']));
        $password = strtolower($request['password']);
        $usuario = SysUsuarioModel::where('user_usuario', $username)->first();
        if($usuario && $this->VerificarAcceso(\Request::ip(), $usuario->id_usuario)==false){
            if (Auth::attempt(['user_usuario' => $username, 'password' => $password, 'deleted_at' => null])) {
                if (Session::has('usuario') && Session::get('usuario') == $username) {
                    $this::logout();
                    Session::flush();
                    return redirect('/')->with(array('alert' => 'El usuario ya se encuentra conectado'));
                }
                Session::put('usuario', $username);
                
                $sys_acceso = new SysAccesoUsuarioModel();
                $estado_acceso = $sys_acceso->getLastEstadoAcceso(Auth::user()->id_usuario);
                $fecha = Carbon::now();
                $sys_usuarios = new SysUsuarioModel();
                $sys_usuarios->change();
                $user = $sys_usuarios->getDataUser(Auth::user()->id_usuario);
                    if ($user->estadoregistro_usuario == 'Habilitado') {
                        Session::flush();
                        Session::put('id_habilitado', $user->id_usuario);
                        return redirect('verify-password');
                    } elseif ($user->estadoregistro_usuario == 'Verificado') {
                        $menu = new SysMenuModel();
                        $menu_array = array();
                        $menus = $menu->getMenuUsuario($user->id_usuario);

                        foreach ($menus as $m => $menu) {
                            $menu_array[$m]['nombre_menu'] = $menu->nombre_menu;
                            $menu_array[$m]['accion_menu'] = $menu->accion_menu;
                            $menu_array[$m]['icono_menu'] = $menu->icono_menu;

                            $submenu = new SysSubmenuModel();
                            $submenus = $submenu->getSubMenuUsuario($user->id_usuario, $menu->id_menu);

                            foreach ($submenus as $sub => $submenu) {
                                $menu_array[$m]['submenu'][$sub]['nombre_submenu'] = $submenu->nombre_submenu;
                                $menu_array[$m]['submenu'][$sub]['accion_submenu'] = $submenu->accion_submenu;
                                $menu_array[$m]['submenu'][$sub]['icono_submenu'] = $submenu->icono_submenu;
                            }
                        }

                        Session::put('auth', $user);
                        Session::put('usuario', $username);
                        Session::put('menus', $menu_array);
                        Session::put('created', time());
                        Session::put('perfil', $user->fkid_perfil);
                        Session::put('area', $user->fkid_area);
                        //Session::put('ultimo_acceso', $estado_acceso->fecha_accesousuario);
                        $this->insertAcceso('Entrada', $usuario->id_usuario);
                        return redirect('home');
                    }
                    elseif ($user->estadoregistro_usuario == 'Contraseña Caducada') {
                        Session::flush();
                        Session::put('id_caducado', $user->id_usuario);
                        return redirect('expired-password');
                    } elseif ($user->estadoregistro_usuario == 'Bloqueado') {
                        Auth::logout();
                        Session::flush();
                        return redirect('/')->with('message', 'USUARIO BLOQUEADO')->withInput();
                    }    
                }
            }
        elseif($usuario && $this->VerificarAcceso(\Request::ip(), $usuario->id_usuario)==1){
                    $this->insertAcceso('Salida', $usuario->id_usuario);
                    Auth::logout();
                    Session::flush();
                    return redirect('/')->with(array('alert' => 'Ya se encontraba logeado o no había salido correctamente, por lo que su sesión anterior cerró. Ingrese nuevamente.'));
                }
        elseif($usuario && $this->VerificarAcceso(\Request::ip(), $usuario->id_usuario)==2){
                Auth::logout();
                Session::flush();
                session_destroy();
                return redirect('/')->with(array('alert' => 'Ya se encuentra logeado en otro equipo, de no ser así comuniquese con soporte.'));
        }
        return redirect('/')->with('message', 'Usuario y/o contraseña incorrecto(s)')->withInput();
    }


    /**
     * Formulario de verificación del usuario
     * @return Response
     */
    public function getVerifyPassword() {
        return (Session::has('id_habilitado')) ? view('login.verify-password') : redirect('/');
    }

    /**
     * Verificación de usuario con contraseña
     * @param Request $request
     * @return Response
     */
    public function postVerifyPassword(Request $request) {
        if (Session::has('id_habilitado')) {
            $usuario = SysUsuarioModel::find(Session::get('id_habilitado'));
            if (is_null($request) || count($usuario) < 1) {
                Session::flush();
                abort(404);
            }

            $inputs = $this->getInputs($request->all());
            if ($this->validateForms($inputs) === true) {
                $sys_usuario = new SysUsuarioModel();
                $id = $usuario->id_usuario;
                $email = $usuario->email_usuario;
                $pass = strtolower($inputs['password']);
                $password = bcrypt($pass);
                $estado_registro = 'Verificado';
                $fecha = Carbon::now();
                $resultado = $sys_usuario->changePassword($id, $password, $fecha, $estado_registro);
                if ($resultado) {
                    //Enviar correo con aviso de nueva contraseña y estado verificado
                    $nombre_usuario = $usuario->user_usuario;
                    $this->enviaVerificaCuenta($email, $pass, $nombre_usuario);
                    Session::flush();
                    return redirect('/')->with(array('mensaje' => 'Su cuenta ha sido verificada correctamente.'));
                }
            } else {
                return redirect('verify-password')->withErrors($this->validateForms($inputs))->withInput();
            }
        }
        abort(404);
    }

    /**
     * Formulario de contraseña caducada
     * @return Response
     */
    public function getExpiredPassword() {
        return (Session::has('id_caducado')) ? view('login.expired-password') : redirect('/');
    }

    /**
     * Pone al usuario vigente luego de tener una contraseña caducada.
     * @param Request $request
     * @return Response
     */
    public function postExpiredPassword(Request $request) {
        if (Session::has('id_caducado')) {
            $usuario = SysUsuarioModel::find(Session::get('id_caducado'));
            if (is_null($request) || count($usuario) < 1) {
                Session::flush();
                abort(404);
            }

            $inputs = $this->getInputs($request->all());
            if ($this->validateForms($inputs) === true) {
                $sys_usuario = new SysUsuarioModel();
                $id = $usuario->id_usuario;
                $pass = strtolower($inputs['password']);
                $password = bcrypt($pass);
                $estado_registro = 'Verificado';
                $fecha = Carbon::now();
                $resultado = $sys_usuario->changePassword($id, $password, $fecha, $estado_registro);
                $email = $usuario->email_usuario;
                $nombre_usuario = $usuario->user_usuario;
                if ($resultado) {
                    //Enviar correo con aviso de nueva contraseña y estado verificado
                    $this->enviaRestableceCuenta($email, $pass, $nombre_usuario);
                    Session::flush();
                    return redirect('/')->with(array('mensaje' => 'Su cuenta ha sido renovada correctamente.'));
                }
            } else {
                return redirect('expired-password')->withErrors($this->validateForms($inputs))->withInput();
            }
        }
        abort(404);
    }

    /**
     * Ingresa los datos del historial de acceso de usuarios.
     * @param enum $tipo_acceso
     * @return void
     */
    protected function insertAcceso($tipo_acceso, $user) {
        $acceso = SysAccesoUsuarioModel::where('fkid_usuario', $user)->first();
        if(!$acceso)
        {
          DB::table('sys_accesosusuarios')->insertGetId( 
          ['tipo_accesousuario' => 'Entrada', 
          'fecha_accesousuario' => Carbon::now(), 
          'ip_accesousuario' => \Request::ip(), 
          'navegador_accesousuario' => \Request::header('User-Agent'),
          'fkid_usuario' => Auth::user()->id_usuario 
            ]);
                     
        }
        else
        {
            $fecha = Carbon::now();
            $ip = \Request::ip();
            $browser = \Request::server('HTTP_USER_AGENT');
            $sys_usuarios = new SysUsuarioModel();
            $sys_usuarios->updateAcceso($fecha, $ip, $browser, $tipo_acceso, $user);
        }
    }


    /**
     * Ingresa los datos del historial de acceso de usuarios.
     * @param enum $tipo_acceso
     * @return void
     */
    protected function VerificarAcceso($ip, $user) {
        $acceso = SysAccesoUsuarioModel::where('fkid_usuario', $user)->first();
        if($acceso){
            if($acceso->ip_accesousuario == $ip && $acceso->tipo_accesousuario == 'Entrada' ){
                return 1;
            }
            if($acceso->tipo_accesousuario == 'Entrada' && $acceso->ip_accesousuario != $ip ){        
                return 2;   
            }
        }
        else{
                return false;
            }
    }
    /**
     * Método de salida del sistema
     * @return Vista de atenticación de usuario
     */
    protected function logout() {
        if (!isset(Auth::user()->id_usuario)) {
            return redirect('/');
        }
        $this->insertAcceso('Salida', Auth::user()->id_usuario);
        Auth::logout();
        Session::flush();
        session_destroy();
        return redirect('/');
    }

    /**
     * Método que valida las entradas en el formulariio
     * @param $inputs Array. Entradas del formulario
     * @return $validation Array. Errores de validación
     */
    private function validateForms($inputs = array()) {
        $rules = array(
            'password'      => 'required|min:6', 
            'repassword'    => 'required|same:password'
        );

        if (Session::has('id_caducado')) {
            $rules['password'] .= '|same_password:'.Session::get('id_caducado');
        }

        $messages = array(
            'password.required'     => 'Por favor, ingrese su nueva contraseña', 
            'password.min'          => 'Su contraseña debe poseer mínimo 6 caracteres', 
            'repassword.required'   => 'Por favor, reingrese su nueva contraseña', 
            'repassword.same'       => 'Las contraseñas no coinciden', 
            'same_password'         => 'No puede volver a usar la contraseña anterior'
        );
        $validation = Validator::make($inputs, $rules, $messages);
        return ($validation->fails()) ? $validation : TRUE;
    }


    /**
     * Método privado que obtiene los inputs del formulario
     * @param $inputs Array. Entradas del formulario
     * @return $inputs Array. Valores del formulario
     */
    private function getInputs($inputs = array()) {
        foreach ($inputs as $key => $val) {
            $inputs[$key] = $val;
        }
        return $inputs;
    }


    /**
     * Show the application sendMail.
     *
     * @return \Illuminate\Http\Response
     */
    private function enviaCambioPassword($receptor, $password)
    {
        $content = [
            'title'=> 'Cambio de contraseña.', 
            'body'=> 'Ud. ha pedido un cambio de contraseña, su nueva contraseña es ',
            'resto_body' => 'Si ud. no ha pedido un cambio de contraseña pongase en contacto con soporte soporte@prueba.cl'
            ];
        Mail::to($receptor)->send(new CambioPass($content, $password));
        return true;
    }


    /**
     * Show the application sendMail.
     *
     * @return \Illuminate\Http\Response
     */
    private function enviaVerificaCuenta($receptor, $password, $nombre_usuario)
    {
        $content = [
            'title'=> 'Cuenta Verificada.', 
            'body'=> 'Ud. ha verificado su cuenta exitosamente, su usuario es: ',
            'body2' => ' y su contraseña es: ',
            'resto_body' => 'Si ud. no ha realizado este proceso póngase en contacto con soporte soporte@prueba.cl'
            ];
        Mail::to($receptor)->send(new CuentaVerificada($content, $password, $nombre_usuario));
        return true;
    }


    /**
     * Show the application sendMail.
     *
     * @return \Illuminate\Http\Response
     */
    private function enviaRestableceCuenta($receptor, $password, $nombre_usuario)
    {
        $content = [
            'title'=> 'Cuenta Reestablecida.', 
            'body'=> 'Ud. ha reestablecido su cuenta exitosamente, su usuario es: ',
            'body2' => ' y su contraseña es: ',
            'resto_body' => 'Recuerde que este proceso se debe realizar cada 6 meses por motivos de seguridad. Si ud. no ha realizado este proceso póngase en contacto con soporte soporte@prueba.cl'
            ];
        Mail::to($receptor)->send(new CuentaReestablecida($content, $password, $nombre_usuario));
        return true;
    }

}

