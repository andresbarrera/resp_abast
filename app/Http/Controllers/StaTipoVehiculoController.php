<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\StaTipoVehiculoModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class StaTipoVehiculoController extends Controller
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
        if($acceso_usuario->getAccesoValido(15, Auth::user()->fkid_perfil)){
            $sta_tiposvehiculos = new StaTipoVehiculoModel();
            $tiposvehiculos = $sta_tiposvehiculos->getTiposVehiculos();
            return view('tiposvehiculos.index', compact('tiposvehiculos'));
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
        if($acceso_usuario->getAccesoValido(15, Auth::user()->fkid_perfil)){
            $sta_tiposvehiculos = new StaTipoVehiculoModel();
            $tiposvehiculos = $sta_tiposvehiculos->getTiposVehiculos();
            return view('tiposvehiculos.create', compact('tiposvehiculos'));
        }
        else{
            return redirect('home');
        }
        
    }


    /**
     * Comprueba la existencia de un tipo de vehiculo en el sistema.
     * @return json
     */
    public function existeTipoVehiculo() {
        if (!isset($_POST['id_tipovehiculo'])) {
            $existe = StaTipoVehiculoModel::where('detalle_tipovehiculo', $_POST['tiposvehiculos'])->count();
        } else {
            $existe = StaTipoVehiculoModel::where('detalle_tipovehiculo', $_POST['tiposvehiculos'])
                                 ->where('id_tipovehiculo', '<>', $_POST['id_tipovehiculo'])
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
            $tipovehiculo = trim($inputs['tiposvehiculos']);
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $sta_tipovehiculo = new StaTipoVehiculoModel();

            $resultado = $sta_tipovehiculo->insertTipoVehiculo($tipovehiculo, $sesion, $fecha);
            if ($resultado) {
                //Enviar mail con contraseña de usuario
                return redirect('tiposvehiculos')->with(array('mensaje' => 'Tipo de licencia ingresado correctamente.'));
            } else {
                return redirect('tiposvehiculos')->with(array('mensaje' => 'Error al ingresar el tipo. Por favor, intente nuevamente.'));
            }
        } else {
            return redirect('tiposvehiculos/create')->withErrors($this->validateForms($inputs))->withInput();
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
        if($acceso_usuario->getAccesoValido(15, Auth::user()->fkid_perfil)){
            $tiposvehiculos = StaTipoVehiculoModel::find($id);
            if (is_null($id) || count($tiposvehiculos) != 1) {
                abort(404);
            }
            return view('tiposvehiculos.update', compact('tiposvehiculos'));

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
        if($acceso_usuario->getAccesoValido(15, Auth::user()->fkid_perfil)){
        
            $tiposvehiculos = StaTipoVehiculoModel::withTrashed()->find($id);
            if (is_null($id) || count($tiposvehiculos) != 1 || is_null($request)) {
                abort(404);
            } 
            else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs, $id) === TRUE) {
                    $tipovehiculo = trim($inputs['tiposvehiculos']);
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    
                    $resultado = $tiposvehiculos->updateTipoVehiculo($id, $tipovehiculo, $sesion, $fecha);
                    if ($resultado) {
                        return redirect('tiposvehiculos')->with(array('mensaje' => 'Tipo de licencia modificada correctamente.'));
                    } 
                    else {
                        return redirect('tiposvehiculos')->with(array('mensaje' => 'Error al modificar el tipo. Por favor, intente nuevamente.'));
                    }
                } 
                else {
                    return redirect('tiposvehiculos/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
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
        if($acceso_usuario->getAccesoValido(15, Auth::user()->fkid_perfil)){
            $tipovehiculo = StaTipoVehiculoModel::withTrashed()->find($id);
            if (is_null($id) || count($tipovehiculo) != 1) {
                abort(404);
            }
            $msj_success = (is_null($tipovehiculo->deleted_at)) ? 'deshabilitada' : 'habilitada';
            $msj_error = (is_null($tipovehiculo->deleted_at)) ? 'deshabilitar' : 'habilitar';
            $sta_tipovehiculo = new StaTipoVehiculoModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $sta_tipovehiculo->deleteTipoVehiculo($id, $sesion, $fecha);

            if ($resultado) {
                return redirect('tiposvehiculos')->with(array('mensaje' => 'Tipo de licencia '.$msj_success.' correctamente.'));
            } else {
                return redirect('tiposvehiculos')->with(array('mensaje' => 'Error al '.$msj_error.' el tipo.'));
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
            'tiposvehiculos'  => 'required|min:3'
        );

        if (!is_null($id)) {
            $rules['tiposvehiculos'] .= ','.$id.',id_tipovehiculo';
        }

        $messages = array(
            'tiposvehiculos.required' => 'Por favor, ingrese el nombre del tipo', 
            'tiposvehiculos.unique'   => 'el tipo ingresado ya se encuentra en los registros', 
            'tiposvehiculos.min'      => 'El nombre del tipo debe tener al menos 3 caracteres'
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
