<?php

namespace ABASTV2\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use ABASTV2\Models\LogError;
use ABASTV2\Models\SysUsuarioModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Validator;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {   
        $mensaje = $exception;
        if($this->isHttpException($exception))
            $codigo = $exception->getStatusCode();    
        else{
            $codigo = $exception->getCode();    
        }
        if(Auth::user()){
            $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $fecha = Carbon::now();
            $navegador = \Request::header('User-Agent');
            $sesion = Auth::user()->id_usuario;
            $log_error = new LogError;
            $log_error->insert($codigo, $url, $navegador, $mensaje, $sesion, $fecha);
            parent::report($exception);
        }
        else{
            $url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $fecha = Carbon::now();
            $navegador = \Request::header('User-Agent');
            $log_error = new LogError;
            $log_error->insert($codigo, $url, $navegador, $mensaje, '65535',  $fecha);
            parent::report($exception);
        }
        

    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {   
        if ($exception instanceof TokenMismatchException) {
            $this->insertAcceso('Salida', Auth::user()->id_usuario);
            Session::flush();
            redirect('/')->with(array('alert' => 'Sesión cerrada por inactividad.'));
        }
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
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
}
