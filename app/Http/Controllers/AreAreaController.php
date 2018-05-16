<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\AreAreaModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class AreAreaController extends Controller
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
        if($acceso_usuario->getAccesoValido(13, Auth::user()->fkid_perfil)){
            $are_areas = new AreAreaModel();
            $areas = $are_areas->getAreas();
            return view('areas.index', compact('areas'));
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
        if($acceso_usuario->getAccesoValido(13, Auth::user()->fkid_perfil)){
            $are_areas = new AreAreaModel();
            $areas = $are_areas->getAreas();
            return view('areas.create', compact('areas'));
        }
        else{
            return redirect('home');
        }
        
    }


    /**
     * Comprueba la existencia de un tipo de articulo en el sistema.
     * @return json
     */
    public function existeArea() {
        if (!isset($_POST['id_area'])) {
            $existe = AreAreaModel::where('nombre_area', $_POST['areas'])->count();
        } else {
            $existe = AreAreaModel::where('nombre_area', $_POST['areas'])
                                 ->where('id_area', '<>', $_POST['id_area'])
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
            $area = trim($inputs['areas']);
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $are_area = new AreAreaModel();

            $resultado = $are_area->insertArea($area, $sesion, $fecha);
            if ($resultado) {
                //Enviar mail con contraseña de usuario
                return redirect('areas')->with(array('mensaje' => 'Area ingresada correctamente.'));
            } else {
                return redirect('areas')->with(array('mensaje' => 'Error al ingresar el area. Por favor, intente nuevamente.'));
            }
        } else {
            return redirect('areas/create')->withErrors($this->validateForms($inputs))->withInput();
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
        if($acceso_usuario->getAccesoValido(13, Auth::user()->fkid_perfil)){
            $areas = AreAreaModel::find($id);
            if (is_null($id) || count($areas) != 1) {
                abort(404);
            }
            return view('areas.update', compact('areas'));

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
        if($acceso_usuario->getAccesoValido(13, Auth::user()->fkid_perfil)){
        
            $areas = AreAreaModel::withTrashed()->find($id);
            if (is_null($id) || count($areas) != 1 || is_null($request)) {
                abort(404);
            } 
            else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs, $id) === TRUE) {
                    $area = trim($inputs['areas']);
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    
                    $resultado = $areas->updateArea($id, $area, $sesion, $fecha);
                    if ($resultado) {
                        return redirect('areas')->with(array('mensaje' => 'Area modificada correctamente.'));
                    } 
                    else {
                        return redirect('areas')->with(array('mensaje' => 'Error al modificar el area. Por favor, intente nuevamente.'));
                    }
                } 
                else {
                    return redirect('areas/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
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
        if($acceso_usuario->getAccesoValido(13, Auth::user()->fkid_perfil)){
            $area = AreAreaModel::withTrashed()->find($id);
            if (is_null($id) || count($area) != 1) {
                abort(404);
            }
            $msj_success = (is_null($area->deleted_at)) ? 'deshabilitada' : 'habilitada';
            $msj_error = (is_null($area->deleted_at)) ? 'deshabilitar' : 'habilitar';
            $are_area = new AreAreaModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $are_area->deleteArea($id, $sesion, $fecha);

            if ($resultado) {
                return redirect('areas')->with(array('mensaje' => 'Area '.$msj_success.' correctamente.'));
            } else {
                return redirect('areas')->with(array('mensaje' => 'Error al '.$msj_error.' el area.'));
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
            'areas'  => 'required|min:3|unique:are_areas,nombre_area'
        );

        if (!is_null($id)) {
            $rules['areas'] .= ','.$id.',id_area';
        }

        $messages = array(
            'areas.required' => 'Por favor, ingrese el nombre de el area', 
            'areas.unique'   => 'el area ingresada ya se encuentra en los registros', 
            'areas.min'      => 'El nombre de el area debe tener al menos 3 caracteres'
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
