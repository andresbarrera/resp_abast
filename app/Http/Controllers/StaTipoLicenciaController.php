<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\StaTipoLicenciaModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class StaTipoLicenciaController extends Controller
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
        if($acceso_usuario->getAccesoValido(14, Auth::user()->fkid_perfil)){
            $sta_tiposlicencias = new StaTipoLicenciaModel();
            $tiposlicencias = $sta_tiposlicencias->getTiposLicencias();
            return view('tiposlicencias.index', compact('tiposlicencias'));
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
        if($acceso_usuario->getAccesoValido(14, Auth::user()->fkid_perfil)){
            $sta_tiposlicencias = new StaTipoLicenciaModel();
            $tiposlicencias = $sta_tiposlicencias->getTiposLicencias();
            return view('tiposlicencias.create', compact('tiposlicencias'));
        }
        else{
            return redirect('home');
        }
        
    }


    /**
     * Comprueba la existencia de un tipo de licencia en el sistema.
     * @return json
     */
    public function existeTipoLicencia() {
        if (!isset($_POST['id_tipolicencia'])) {
            $existe = StaTipoLicenciaModel::where('detalle_tipolicencia', $_POST['tiposlicencias'])->count();
        } else {
            $existe = StaTipoLicenciaModel::where('detalle_tipolicencia', $_POST['tiposlicencias'])
                                 ->where('id_tipolicencia', '<>', $_POST['id_tipolicencia'])
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
            $tipolicencia = trim($inputs['tiposlicencias']);
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $sta_tipolicencia = new StaTipoLicenciaModel();

            $resultado = $sta_tipolicencia->insertTipoLicencia($tipolicencia, $sesion, $fecha);
            if ($resultado) {
                //Enviar mail con contraseña de usuario
                return redirect('tiposlicencias')->with(array('mensaje' => 'Tipo de licencia ingresado correctamente.'));
            } else {
                return redirect('tiposlicencias')->with(array('mensaje' => 'Error al ingresar el tipo. Por favor, intente nuevamente.'));
            }
        } else {
            return redirect('tiposlicencias/create')->withErrors($this->validateForms($inputs))->withInput();
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
        if($acceso_usuario->getAccesoValido(14, Auth::user()->fkid_perfil)){
            $tiposlicencias = StaTipoLicenciaModel::withTrashed()->find($id);
            if (is_null($id) || count($tiposlicencias) != 1) {
                abort(404);
            }
            return view('tiposlicencias.update', compact('tiposlicencias'));

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
        if($acceso_usuario->getAccesoValido(14, Auth::user()->fkid_perfil)){
        
            $tiposlicencias = StaTipoLicenciaModel::withTrashed()->find($id);
            if (is_null($id) || count($tiposlicencias) != 1 || is_null($request)) {
                abort(404);
            } 
            else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs, $id) === TRUE) {
                    $tipolicencia = trim($inputs['tiposlicencias']);
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    
                    $resultado = $tiposlicencias->updateTipoLicencia($id, $tipolicencia, $sesion, $fecha);
                    if ($resultado) {
                        return redirect('tiposlicencias')->with(array('mensaje' => 'Tipo de licencia modificada correctamente.'));
                    } 
                    else {
                        return redirect('tiposlicencias')->with(array('mensaje' => 'Error al modificar el tipo. Por favor, intente nuevamente.'));
                    }
                } 
                else {
                    return redirect('tiposlicencias/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
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
        if($acceso_usuario->getAccesoValido(14, Auth::user()->fkid_perfil)){
            $tipolicencia = StaTipoLicenciaModel::withTrashed()->find($id);
            if (is_null($id) || count($tipolicencia) != 1) {
                abort(404);
            }
            $msj_success = (is_null($tipolicencia->deleted_at)) ? 'deshabilitada' : 'habilitada';
            $msj_error = (is_null($tipolicencia->deleted_at)) ? 'deshabilitar' : 'habilitar';
            $sta_tipolicencia = new StaTipoLicenciaModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $sta_tipolicencia->deleteTipoLicencia($id, $sesion, $fecha);

            if ($resultado) {
                return redirect('tiposlicencias')->with(array('mensaje' => 'Tipo de licencia '.$msj_success.' correctamente.'));
            } else {
                return redirect('tiposlicencias')->with(array('mensaje' => 'Error al '.$msj_error.' el tipo.'));
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
            'tiposlicencias'  => 'required|min:1'
        );

        if (!is_null($id)) {
            $rules['tiposlicencias'] .= ','.$id.',id_tipolicencia';
        }

        $messages = array(
            'tiposlicencias.required' => 'Por favor, ingrese el nombre del tipo', 
            'tiposlicencias.unique'   => 'el tipo ingresado ya se encuentra en los registros', 
            'tiposlicencias.min'      => 'El nombre del tipo debe tener al menos 2 caracteres'
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
