<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\StaTipoObligatoriedadModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class StaTipoObligatoriedadController extends Controller
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
            $sta_tiposobligatoriedades = new StaTipoObligatoriedadModel();
            $tiposobligatoriedades = $sta_tiposobligatoriedades->getTiposObligatoriedades();
            return view('tiposobligatoriedades.index', compact('tiposobligatoriedades'));
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
            $sta_tiposobligatoriedades = new StaTipoObligatoriedadModel();
            $tiposobligatoriedades = $sta_tiposobligatoriedades->getTiposObligatoriedades();
            return view('tiposobligatoriedades.create', compact('tiposobligatoriedades'));
        }
        else{
            return redirect('home');
        }
        
    }


    /**
     * Comprueba la existencia de un tipo de obligatoriedad en el sistema.
     * @return json
     */
    public function existeTipoObligatoriedad() {
        if (!isset($_POST['id_tipoobligatoriedad'])) {
            $existe = StaTipoObligatoriedadModel::where('detalle_tipoobligatoriedad', $_POST['tiposobligatoriedades'])->count();
        } else {
            $existe = StaTipoObligatoriedadModel::where('detalle_tipoobligatoriedad', $_POST['tiposobligatoriedades'])
                                 ->where('id_tipoobligatoriedad', '<>', $_POST['id_tipoobligatoriedad'])
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
            $tipoobligatoriedad = trim($inputs['tiposobligatoriedades']);
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $sta_tipoobligatoriedad = new StaTipoObligatoriedadModel();

            $resultado = $sta_tipoobligatoriedad->insertTipoObligatoriedad($tipoobligatoriedad, $sesion, $fecha);
            if ($resultado) {
                //Enviar mail con contraseña de usuario
                return redirect('tiposobligatoriedades')->with(array('mensaje' => 'Tipo de licencia ingresado correctamente.'));
            } else {
                return redirect('tiposobligatoriedades')->with(array('mensaje' => 'Error al ingresar el tipo. Por favor, intente nuevamente.'));
            }
        } else {
            return redirect('tiposobligatoriedades/create')->withErrors($this->validateForms($inputs))->withInput();
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
            $tiposobligatoriedades = StaTipoObligatoriedadModel::find($id);
            if (is_null($id) || count($tiposobligatoriedades) != 1) {
                abort(404);
            }
            return view('tiposobligatoriedades.update', compact('tiposobligatoriedades'));

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
        
            $tiposobligatoriedades = StaTipoObligatoriedadModel::withTrashed()->find($id);
            if (is_null($id) || count($tiposobligatoriedades) != 1 || is_null($request)) {
                abort(404);
            } 
            else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs, $id) === TRUE) {
                    $tipoobligatoriedad = trim($inputs['tiposobligatoriedades']);
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    
                    $resultado = $tiposobligatoriedades->updateTipoObligatoriedad($id, $tipoobligatoriedad, $sesion, $fecha);
                    if ($resultado) {
                        return redirect('tiposobligatoriedades')->with(array('mensaje' => 'Tipo de licencia modificada correctamente.'));
                    } 
                    else {
                        return redirect('tiposobligatoriedades')->with(array('mensaje' => 'Error al modificar el tipo. Por favor, intente nuevamente.'));
                    }
                } 
                else {
                    return redirect('tiposobligatoriedades/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
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
            $tipoobligatoriedad = StaTipoObligatoriedadModel::withTrashed()->find($id);
            if (is_null($id) || count($tipoobligatoriedad) != 1) {
                abort(404);
            }
            $msj_success = (is_null($tipoobligatoriedad->deleted_at)) ? 'deshabilitada' : 'habilitada';
            $msj_error = (is_null($tipoobligatoriedad->deleted_at)) ? 'deshabilitar' : 'habilitar';
            $sta_tipoobligatoriedad = new StaTipoObligatoriedadModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $sta_tipoobligatoriedad->deleteTipoObligatoriedad($id, $sesion, $fecha);

            if ($resultado) {
                return redirect('tiposobligatoriedades')->with(array('mensaje' => 'Tipo de licencia '.$msj_success.' correctamente.'));
            } else {
                return redirect('tiposobligatoriedades')->with(array('mensaje' => 'Error al '.$msj_error.' el tipo.'));
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
            'tiposobligatoriedades'  => 'required|min:3'
        );

        if (!is_null($id)) {
            $rules['tiposobligatoriedades'] .= ','.$id.',id_tipoobligatoriedad';
        }

        $messages = array(
            'tiposobligatoriedades.required' => 'Por favor, ingrese el nombre del tipo', 
            'tiposobligatoriedades.min'      => 'El nombre del tipo debe tener al menos 3 caracteres'
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
