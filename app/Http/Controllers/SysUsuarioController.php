<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\SysUsuarioModel;
use ABASTV2\Models\PrsPersonaModel;
use ABASTV2\Models\CntContratoEmpleadoModel;
use ABASTV2\Models\AreAreaModel;
use ABASTV2\Models\SysPerfilModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use ABASTV2\Models\CntConductorModel;
use ABASTV2\Models\DocTipoDocumentoModel;
use ABASTV2\Models\EmpleadoTipodocumentoModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use ABASTV2\Mail\UsuarioCreado;
use Carbon\Carbon;
use Validator;
use Mail;



class SysUsuarioController extends Controller
{   

    /**
     * Constructor de la clase.
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $acceso_usuario = new SysAccesoUsuarioModel();
        if($acceso_usuario->getAccesoValido(1, Auth::user()->fkid_perfil)){
            $sys_usuarios = new SysUsuarioModel();
            $usuarios = $sys_usuarios->getUsuarios();
            return view('usuarios.index', compact('usuarios'));    

        }
        else{
            return redirect('home');
        }

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $acceso_usuario = new SysAccesoUsuarioModel();
        if($acceso_usuario->getAccesoValido(1, Auth::user()->fkid_perfil)){
            $sys_usuario = new SysUsuarioModel();
            $usuarios = $sys_usuario->getUsuarios();
            $sys_perfil = new SysPerfilModel();
            $perfiles = $sys_perfil::all();
            $are_area = new AreAreaModel();
            $areas = $are_area::all();
            $prs_persona = new PrsPersonaModel();
            $personas = $prs_persona->getPersonas();
            return view('usuarios.create', compact('usuarios', 'perfiles', 'areas', 'personas'));    
        }
        else{
            return redirect('home');
        }
    }

    /**
     * Comprueba la existencia del nombre de usuario en el sistema.
     * @return json
     */
    public function existeUsuario() {
        if (!isset($_POST['id_usuario'])) {
            $existe = SysUsuarioModel::whereNull('deleted_at')
                                ->where('nombres_usuario', $_POST['nombres'])
                                ->count();
        } else {
            $existe = SysUsuarioModel::whereNull('deleted_at')
                                ->where('nombres_usuario', $_POST['nombres'])
                                ->where('id_usuario', '<>', $_POST['id_usuario'])
                                ->count();
        }
        header('Content-Type: application/json');
        echo json_encode($existe);
    }


    /**
     * Comprueba la existencia del nombre de usuario en el sistema.
     * @return json
     */
    public function existeMail() {
        if (!isset($_POST['id_usuario'])) {
            $existe = SysUsuarioModel::whereNull('deleted_at')
                                ->where('email_usuario', $_POST['email'])
                                ->count();
        } else {
            $existe = SysUsuarioModel::whereNull('deleted_at')
                                ->where('email_usuario', $_POST['email'])
                                ->where('id_usuario', '<>', $_POST['id_usuario'])
                                ->count();
        }
        header('Content-Type: application/json');
        echo json_encode($existe);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if (is_null($request)) {
            abort(404);
        }
        $inputs = $this->getInputs($request->all());
        if ($this->validateForms($inputs) == TRUE) {
            $sys_usuario = new SysUsuarioModel();
            $pass = rand(100000 , 999999);
            $password = bcrypt($pass);
            $nombres = ucfirst(strtolower($inputs['nombres']));
            $patapel = ucfirst(strtolower(trim($inputs['patapel'])));
            $matapel = ucfirst(strtolower(trim($inputs['matapel'])));
            $username = $sys_usuario->username($nombres, $patapel, 'crear');
            $estadoregistro = 'Habilitado';
            $perfil = trim($inputs['perfil']);
            $area = trim($inputs['area']);
            $email = trim($inputs['email']);
            $compara_email= $sys_usuario->where('email_usuario', $email)->first();
            //$proyecto = trim($inputs['proyectos']);
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            if($compara_email && $compara_email->email_usuario == $email){
                return redirect('usuarios/create')->with('message', 'Error al ingresar usuario. El mail ya se encuentra en los registros.')->withInput();
            }
            $prs_persona = new PrsPersonaModel();
            $resultado1 = $prs_persona->insertPersona(null, null, $nombres, $patapel, $matapel, null, $sesion, $fecha);
            $persona = $prs_persona->getIdPersona($nombres, $patapel, $matapel);
            

            $cnt_contratoempleado = new CntContratoEmpleadoModel();
            $resultado2 = $cnt_contratoempleado->insertContratoEmpleado(null, null, 'activo', null, null, 3, null, $persona->id_persona, $sesion, $fecha);
            $id_contrato = $cnt_contratoempleado->getIdContrato($persona->id_persona);
            
            $cnt_conductor = new CntConductorModel();
            $resultado3 = $cnt_conductor->insertConductor($id_contrato->id_contratoempleado, $sesion, $fecha); 
            
            if ($resultado1 && $resultado2 && $resultado3) {
                $resultado = $sys_usuario->insertUsuario($username, $password, $email, $estadoregistro, $nombres, $patapel, $matapel, $persona->id_persona, $perfil, $area, $sesion, $fecha);
                if ($resultado) {
                    //Enviar mail con contraseña de usuario
                    $doc_tipos = new DocTipoDocumentoModel();
                    $tipos = $doc_tipos->getTiposDocumentos();
                    $emp_tipos = new EmpleadoTipodocumentoModel();
                    foreach ($tipos as $tipo) {
                        # code...
                        $emp_tipos->insertEmpleadoTipodocumento(null, null, $id_contrato->id_contratoempleado, $tipo->id_tipodocumento, null, null, $fecha );
                    }
                    $this->enviaUsuarioCreado($email, $pass, $username);
                    return redirect('usuarios')->with(array('mensaje' => 'Usuario ingresado correctamente.'));
                }
                else {
                    return redirect('usuarios')->with(array('mensaje' => 'Error al ingresar usuario. Por favor, intente nuevamente.'));
                }    
            }
            else {
                return redirect('usuarios')->with(array('mensaje' => 'Error al ingresar usuario. Por favor, intente nuevamente.'));
            }


        } else {
            return redirect('usuarios/create')->withErrors($this->validateForms($inputs))->withInput();
        }

    }


    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $acceso_usuario = new SysAccesoUsuarioModel();
        if($acceso_usuario->getAccesoValido(1, Auth::user()->fkid_perfil)){
            $usuarios = SysUsuarioModel::withTrashed()->find($id);

            if (is_null($id) || count($usuarios) != 1) {
                abort(404);
            }
            $sys_perfiles = new SysPerfilModel();
            $perfiles = $sys_perfiles::where('id_perfil', '!=', 2)->get();
            $are_areas = new AreAreaModel();
            $areas = AreAreaModel::all();
            $prs_persona = new PrsPersonaModel();
            $personas = $prs_persona->getPersonas();
            return view('usuarios.update', compact('perfiles', 'areas', 'personas', 'usuarios'));    
        }
        else{
            return redirect('home');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $acceso_usuario = new SysAccesoUsuarioModel();
        if($acceso_usuario->getAccesoValido(1, Auth::user()->fkid_perfil)){
            $usuarios = SysUsuarioModel::withTrashed()->find($id);
            if (is_null($id) || count($usuarios) != 1 || is_null($request)) {
                abort(404);
            } else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs, $id) === TRUE) {
                    $sys_usuario = new SysUsuarioModel();
                    $nombres = ucfirst(strtolower($inputs['nombres']));
                    $patapel = ucfirst(strtolower(trim($inputs['patapel'])));
                    if(!$inputs['matapel']){
                        $matapel = null;
                    }
                    else{
                        $matapel = $inputs['matapel'];
                    }
                    $email = trim($inputs['email']);
                    $username = $sys_usuario->username($nombres, $patapel, 'update');
                    $perfil = trim($inputs['perfil']);
                    $area = trim($inputs['area']);
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    $prs_persona = new PrsPersonaModel();
                    $id_persona = $usuarios->fkid_persona;
                    $resultado1 = $prs_persona->updatePersonaUsuario($id_persona, $nombres, $patapel, $matapel, $sesion, $fecha);
                    if($resultado1){
                        $resultado = $sys_usuario->updateUsuario($id, $username, $email, $nombres, $patapel,  $matapel, $perfil, $area, $sesion, $fecha);
                        if ($resultado) {
                            return redirect('usuarios')->with(array('mensaje' => 'Usuario modificado correctamente.'));
                        } else {
                            return redirect('usuarios')->with(array('mensaje' => 'Error al modificar el usuario. Por favor, intente nuevamente.'));
                        }
                    }
                    else 
                    {
                        return redirect('usuarios')->with(array('mensaje' => 'Error al modificar el usuario. Por favor, intente nuevamente.'));
                    }
                } else {
                    return redirect('usuarios/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
                }
            }    

        }
        else{
            return redirect('home');
        }


        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //

        $acceso_usuario = new SysAccesoUsuarioModel();
        if($acceso_usuario->getAccesoValido(1, Auth::user()->fkid_perfil)){
            $usuario = SysUsuarioModel::withTrashed()->find($id);
            if (is_null($id) || count($usuario) != 1) {
                abort(404);
            }
            $msj_success = (is_null($usuario->deleted_at)) ? 'deshabilitado' : 'habilitado';
            $msj_error = (is_null($usuario->deleted_at)) ? 'deshabilitar' : 'habilitar';
            $sys_usuario = new SysUsuarioModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $prs_persona = new PrsPersonaModel();
            //$id_persona = $prs_persona->getIdUsuario($id);
            $persona_busca = SysUsuarioModel::where('id_usuario', $id)->withTrashed()->first();
            $id_persona = $persona_busca->fkid_persona;
            $resultado1 = $prs_persona->deletePersona($id_persona, $sesion, $fecha);
            if($resultado1){
                $resultado = $sys_usuario->deleteUsuario($id, $sesion, $fecha);

                if ($resultado) {
                    return redirect('usuarios')->with(array('mensaje' => 'usuario'.$msj_success.' correctamente.'));
                } else {
                    return redirect('usuarios')->with(array('mensaje' => 'Error al '.$msj_error.' el usuario.'));
                }
            }
            else 
            {
                return redirect('usuarios')->with(array('mensaje' => 'Error al '.$msj_error.' el usuario.'));
            }    

        }
        else{
            return redirect('home');
        }
   }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function block($id)
    {
        //
        $acceso_usuario = new SysAccesoUsuarioModel();
        if($acceso_usuario->getAccesoValido(1, Auth::user()->fkid_perfil)){
            $usuario = SysUsuarioModel::withTrashed()->find($id);
            if (is_null($id) || count($usuario) != 1) {
                abort(404);
            }
            $msj_success = ($usuario->estadoregistro_usuario="Verificado") ? 'deshabilitado' : 'habilitado';
            $msj_error = ($usuario->estadoregistro_usuario="Verificado") ? 'deshabilitar' : 'habilitar';
            $sys_usuario = new SysUsuarioModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $sys_usuario->blockUsuario($id, $sesion, $fecha);

           if ($resultado) {
                return redirect('usuarios')->with(array('mensaje' => 'usuario'.$msj_success.' correctamente.'));
            } else {
                return redirect('usuarios')->with(array('mensaje' => 'Error al '.$msj_error.' el usuario.'));
            }    

        }
        else{
            return redirect('home');
        }
    }


    /**
     * Método que valida las entradas en el formulariio
     * @param $inputs Array. Entradas del formulario
     * @return $validation Array. Errores de validación
     */
    private function validateForms($inputs = array(), $id = null) {
        $rules = array(
            'nombres'      => 'required|min:3', 
            'perfil'        => 'required',
            'email'     => 'required',
            'area'          => 'required',
            'patapel'       => 'required|min:3'
        );

        if (!is_null($id)) {
            $rules['nombres'] .= ','.$id.',id_usuario';
        }

        $messages = array(
            'nombres.required'     => 'Por favor, ingrese los nombre del usuario', 
            'nombres.min'          => 'El nombre de usuario debe tener al menos 3 caracteres', 
            'area.required'      => 'Por favor, seleccione el area', 
            'perfil.required'       => 'Por favor, seleccione el perfil de usuario',
            'email.required'    => 'Por favor indique el mail de usuario',
            'email.unique'      => 'El email ya se encuentra en los registros',
            'patapel.required'  => 'Por favor, indique apellido paterno'
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
    private function enviaUsuarioCreado($receptor, $password, $nombre_usuario)
    {
        $content = [
            'title'=> 'Usuario Creado.', 
            'body'=> 'Su cuenta ha sido creada exitosamente, su usuario es: ',
            'body2' => ' y su contraseña es: ',
            'resto_body' => 'Recuerde que en el primer ingreso al sistema deberá cambiar su contraseña, por motivos de seguridad. Si ud. no ha pedido esta cuenta póngase en contacto con soporte soporte@prueba.cl'
            ];
        Mail::to($receptor)->send(new UsuarioCreado($content, $password, $nombre_usuario));
        return true;
    }

        
}
