<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\SysPerfilModel;
use ABASTV2\Models\SysSubmenuModel;
use ABASTV2\Models\SysPermisoPerfilModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;


class SysPerfilController extends Controller
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
        if($acceso_usuario->getAccesoValido(12, Auth::user()->fkid_perfil)){
            $sys_perfiles = new SysPerfilModel();
            $perfiles = $sys_perfiles->getPerfiles();
            return view('perfil.index', compact('perfiles'));   

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
        if($acceso_usuario->getAccesoValido(12, Auth::user()->fkid_perfil)){
            $sys_submenus = new SysPermisoPerfilModel();
            $submenus = $sys_submenus->getSubMenus();
            return view('perfil.create', compact('submenus'));    

        }
        else{
            return redirect('home');
        }

        
    }

    /**
     * Comprueba la existencia de un perfil en el sistema.
     * @return json
     */
    public function existePerfil() {
        if (!isset($_POST['id_perfil'])) {
            $existe = SysPerfilModel::where('nombre_perfil', $_POST['perfil'])->count();
        } else {
            $existe = SysPerfilModel::where('nombre_perfil', $_POST['perfil'])
                                 ->where('id_perfil', '<>', $_POST['id_perfil'])
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

        if ($this->validateForms($inputs) === TRUE) {
            $perfil = trim($inputs['perfil']);
            $submenus = $inputs['submenu'];
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $sys_perfil = new SysPerfilModel();
            $resultado = $sys_perfil->insertPerfil($perfil, $sesion, $fecha);
            if (!$resultado) {
                return redirect('perfil')->with(array('mensaje' => 'Error al ingresar perfil. Por favor, intente nuevamente.'));
            }
            $perfil = SysPerfilModel::where('nombre_perfil', $perfil)->first();
            $id_perfil = $perfil->id_perfil;
            foreach ($submenus as $submenu) {
                # code...
                $resultado = $sys_perfil->insertPermisosPerfiles($id_perfil, (int)$submenu, $sesion, $fecha);
                if (!$resultado) {
                    return redirect('perfil')->with(array('mensaje' => 'Error al ingresar perfil. Por favor, intente nuevamente.'));
                }
            }
            if($resultado){
                return redirect('perfil')->with(array('mensaje' => 'Perfil ingresado correctamente.'));
            }
        } else {
            return redirect('perfil/create')->withErrors($this->validateForms($inputs))->withInput();
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
        if($acceso_usuario->getAccesoValido(12, Auth::user()->fkid_perfil)){
            $perfil = SysPerfilModel::withTrashed()->find($id);
            if (is_null($id) || count($perfil) != 1) {
                abort(404);
            }
            $submenus_perfiles = $perfil->selectPerfilesSubmenus($id);
            $submenus = SysSubmenuModel::all(); 
            return view('perfil.update', compact('perfil', 'submenus', 'submenus_perfiles'));
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
        if($acceso_usuario->getAccesoValido(12, Auth::user()->fkid_perfil)){
            if (is_null($request)) {
                abort(404);
            }
            $inputs = $this->getInputs($request->all());

            if ($this->validateForms($inputs) === TRUE) {
                $perfil = trim($inputs['perfil']);
                $submenus = $inputs['submenu'];
                $sesion = Auth::user()->id_usuario;
                $fecha = Carbon::now();
                $sys_perfil = new SysPerfilModel();
                $resultado = $sys_perfil->updatePerfil($id, $perfil, $sesion, $fecha);
                if (!$resultado) {
                    return redirect('perfil')->with(array('mensaje' => 'Error al modificar perfil. Por favor, intente nuevamente.'));
                }
                $perfil = SysPerfilModel::where('nombre_perfil', $perfil)->first();
                $id_perfil = $perfil->id_perfil;
                $delete_submenus = SysPermisoPerfilModel::where('fkid_perfil', $id_perfil)->forceDelete();
                foreach ($submenus as $submenu) {
                    # code...
                    $resultado = $sys_perfil->insertPermisosPerfiles($id_perfil, (int)$submenu, $sesion, $fecha);
                    if (!$resultado) {
                        return redirect('perfil')->with(array('mensaje' => 'Error al modificar perfil. Por favor, intente nuevamente.'));
                    }
                }
                if($resultado){
                    return redirect('perfil')->with(array('mensaje' => 'Perfil modificado correctamente.'));
                }
            } else {
                return redirect('perfil/'.$id.'/edit')->withErrors($this->validateForms($inputs))->withInput();
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
        if($acceso_usuario->getAccesoValido(12, Auth::user()->fkid_perfil)){
            $perfil = SysPerfilModel::withTrashed()->find($id);
            if (is_null($id) || count($perfil) != 1) {
                abort(404);
            }
            $msj_success = (is_null($perfil->deleted_at)) ? 'deshabilitado' : 'habilitado';
            $msj_error = (is_null($perfil->deleted_at)) ? 'deshabilitar' : 'habilitar';

            $sys_perfil = new SysPerfilModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $sys_perfil->deletePerfil($id, $sesion, $fecha);
            if ($resultado) {
                return redirect('perfil')->with(array('mensaje' => 'perfil '.$msj_success.' correctamente.'));
            } else {
                return redirect('perfil')->with(array('mensaje' => 'Error al '.$msj_error.' el perfil.'));
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
            'perfil'  => 'required|min:3'
        );

        if (!is_null($id)) {
            $rules['perfil'] .= ','.$id.',id_perfil';
        }

        $messages = array(
            'perfil.required' => 'Por favor, ingrese el nombre del perfil', 
            'perfil.min'      => 'El nombre del perfil debe tener al menos 3 caracteres'
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


}
