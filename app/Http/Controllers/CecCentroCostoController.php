<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\CecCentroCostoModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class CecCentroCostoController extends Controller
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
        if($acceso_usuario->getAccesoValido(4, Auth::user()->fkid_perfil)){
            $cec_centroscostos = new CecCentroCostoModel();
            $centroscostos = $cec_centroscostos->getCentrosCostos();
            $centros = $cec_centroscostos->getCentrosCostos();
            return view('centroscostos.index', compact('centroscostos', 'centros'));
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
        if($acceso_usuario->getAccesoValido(4, Auth::user()->fkid_perfil)){
            $cec_centroscostos = new CecCentroCostoModel();
            $centroscostos = $cec_centroscostos->getCentrosCostos(); 
            return view('centroscostos.create', compact('centroscostos'));
        }
        else{
            return redirect('home');
        }
        
    }


    /**
     * Comprueba la existencia de un tipo de obligatoriedad en el sistema.
     * @return json
     */
    public function existeCentroCosto() {
        if (!isset($_POST['id_centrocosto'])) {
            $existe = CecCentroCostoModel::where('cod_centrocosto', $_POST['centroscostos'])->count();
        } else {
            $existe = CecCentroCostoModel::where('cod_centrocosto', $_POST['centroscostos'])
                                 ->where('id_centrocosto', '<>', $_POST['id_centrocosto'])
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
            $centrocosto = trim($inputs['centroscostos']);
            $descripcion = $inputs['descripcion'];
            
            $fechainicio = $inputs['fechainicio'];
            $ini = explode('/', $fechainicio);
            //$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
            $fechainicio = $ini[2].'-'.$ini[1].'-'.$ini[0];

            $fechafinal = $inputs['fechafinal'];
            $fin = explode('/', $fechafinal);
            //$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
            $fechafinal = $fin[2].'-'.$fin[1].'-'.$fin[0];


            if(!isset($inputs['codcentro'])){
                $codcentro = NULL;
            }
            else{
                $codcentro = trim($inputs['codcentro']);    
            } 
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $cec_centrocosto = new CecCentroCostoModel();

            $resultado = $cec_centrocosto->insertCentroCosto($centrocosto, $descripcion, $fechainicio, $fechafinal, $codcentro, $sesion, $fecha);
            if ($resultado) {
                //Enviar mail con contraseña de usuario
                return redirect('centroscostos')->with(array('mensaje' => 'Centro de costo ingresado correctamente.'));
            } else {
                return redirect('centroscostos')->with(array('mensaje' => 'Error al ingresar el centro. Por favor, intente nuevamente.'));
            }
        } else {
            return redirect('centroscostos/create')->withErrors($this->validateForms($inputs))->withInput();
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
        if($acceso_usuario->getAccesoValido(4, Auth::user()->fkid_perfil)){
            $centroscostos = CecCentroCostoModel::find($id);
            if (is_null($id) || count($centroscostos) != 1) {
                abort(404);
            }
            $cec_centros = new CecCentroCostoModel();
            $centros = $cec_centros->getCentrosCostos();

            $fechainicio = $centroscostos->fechainicio_centrocosto;
            $ini = explode('-', $fechainicio);
            //$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
            $fechainicio = $ini[2].'/'.$ini[1].'/'.$ini[0];

            $fechafinal = $centroscostos->fechafinal_centrocosto;
            $fin = explode('-', $fechafinal);
            //$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
            $fechafinal = $fin[2].'/'.$fin[1].'/'.$fin[0];
            return view('centroscostos.update', compact('centroscostos', 'centros', 'fechainicio', 'fechafinal'));

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
        if($acceso_usuario->getAccesoValido(4, Auth::user()->fkid_perfil)){
        
            $centroscostos = CecCentroCostoModel::withTrashed()->find($id);

            if (is_null($id) || count($centroscostos) != 1 || is_null($request)) {
                abort(404);
            } 
            else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs) === TRUE) {
                    $centrocosto = trim($inputs['centroscostos']);
                    $descripcion = $inputs['descripcion'];
                    $fechainicio = $inputs['fechainicio'];
                    $ini = explode('/', $fechainicio);
                    //$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
                    $fechainicio = $ini[2].'-'.$ini[1].'-'.$ini[0];

                    $fechafinal = $inputs['fechafinal'];
                    $fin = explode('/', $fechafinal);
                    //$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
                    $fechafinal = $fin[2].'-'.$fin[1].'-'.$fin[0];

                    if(!isset($inputs['codcentro'])){
                        $codcentro = NULL;
                    }
                    else{
                        $codcentro = trim($inputs['codcentro']);    
                    } 
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    $cec_centrocosto = new CecCentroCostoModel();
                    $resultado = $cec_centrocosto->updateCentroCosto($centrocosto, $descripcion, $fechainicio, $fechafinal, $codcentro, $sesion, $fecha, $id);
                if ($resultado) {
                    //Enviar mail con contraseña de usuario
                    return redirect('centroscostos')->with(array('mensaje' => 'Centro de costo modificado correctamente.'));
                } else {
                    return redirect('centroscostos')->with(array('mensaje' => 'Error al modificar el centro. Por favor, intente nuevamente.'));
                }
            }   else {
                    return redirect('centroscostos/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
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
        if($acceso_usuario->getAccesoValido(4, Auth::user()->fkid_perfil)){
            $centrocosto = CecCentroCostoModel::withTrashed()->find($id);
            if (is_null($id) || count($centrocosto) != 1) {
                abort(404);
            }
            $msj_success = (is_null($centrocosto->deleted_at)) ? 'deshabilitada' : 'habilitada';
            $msj_error = (is_null($centrocosto->deleted_at)) ? 'deshabilitar' : 'habilitar';
            $cec_centrocosto = new CecCentroCostoModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $cec_centrocosto->deleteCentroCosto($id, $sesion, $fecha);

            if ($resultado) {
                return redirect('centroscostos')->with(array('mensaje' => 'Tipo de licencia '.$msj_success.' correctamente.'));
            } else {
                return redirect('centroscostos')->with(array('mensaje' => 'Error al '.$msj_error.' el tipo.'));
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
            'centroscostos'  => 'required|min:3',
            'fechainicio' => 'required',
            'fechafinal' => 'required'
        );

        if (!is_null($id)) {
            $rules['centroscostos'] .= ','.$id.',id_centrocosto';
        }

        $messages = array(
            'centroscostos.required' => 'Por favor, ingrese el nombre del centro', 
            'centroscostos.min'      => 'El nombre del centro debe tener al menos 3 caracteres',
            'fechainicio.required' => 'Por favor, ingrese la fecha inicial del servicio',
            'fechafinal.required' => 'Por favor, ingrese la fecha final del servicio'
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
