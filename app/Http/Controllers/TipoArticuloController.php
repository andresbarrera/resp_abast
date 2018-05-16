<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\ArtTipoArticuloModel;
use ABASTV2\Models\ArtAprobacionModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class TipoArticuloController extends Controller
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
        if($acceso_usuario->getAccesoValido(6, Auth::user()->fkid_perfil)){
            $art_tiposarticulos = new ArtTipoArticuloModel();
            $tiposarticulos = $art_tiposarticulos->getTiposArticulos();
            return view('tiposarticulos.index', compact('tiposarticulos'));    
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
        if($acceso_usuario->getAccesoValido(6, Auth::user()->fkid_perfil)){
            $art_tiposarticulos = new ArtTipoArticuloModel();
            $tiposarticulos = $art_tiposarticulos->getTiposArticulos();
            $aprobaciones = ArtAprobacionModel::all();
            return view('tiposarticulos.create', compact('tiposarticulos', 'aprobaciones'));    
        }
        else{
            return redirect('home');
        }
    }


    /**
     * Comprueba la existencia de un tipo de articulo en el sistema.
     * @return json
     */
    public function existeTipoArticulo() {
        if (!isset($_POST['id_tipoarticulo'])) {
            $existe = ArtTipoArticuloModel::where('nombre_tipoarticulo', $_POST['tiposarticulos'])->count();
        } else {
            $existe = ArtTipoArticuloModel::where('nombre_tipoarticulo', $_POST['tiposarticulos'])
                                 ->where('id_tipoarticulo', '<>', $_POST['id_tipoarticulo'])
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
            $tipoarticulo = trim($inputs['tiposarticulos']);
            $aprobacion = trim($inputs['aprobacion']);
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $art_tipoarticulo = new ArtTipoArticuloModel();

            $resultado = $art_tipoarticulo->insertTipoArticulo($tipoarticulo, $aprobacion, $sesion, $fecha);
            if ($resultado) {
                //Enviar mail con contraseña de usuario
                return redirect('tiposarticulos')->with(array('mensaje' => 'Familia ingresada correctamente.'));
            } else {
                return redirect('tiposarticulos')->with(array('mensaje' => 'Error al ingresar la familia. Por favor, intente nuevamente.'));
            }
        } else {
            return redirect('tiposarticulos/create')->withErrors($this->validateForms($inputs))->withInput();
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
        if($acceso_usuario->getAccesoValido(6, Auth::user()->fkid_perfil)){
            $tiposarticulos = ArtTipoArticuloModel::find($id);
            if (is_null($id) || count($tiposarticulos) != 1) {
                abort(404);
            }
            $aprobaciones = ArtAprobacionModel::all();
            return view('tiposarticulos.update', compact('tiposarticulos', 'aprobaciones'));    
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
        if($acceso_usuario->getAccesoValido(6, Auth::user()->fkid_perfil)){
           $tiposarticulos = ArtTipoArticuloModel::withTrashed()->find($id);
            if (is_null($id) || count($tiposarticulos) != 1 || is_null($request)) {
                abort(404);
            } else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs, $id) === TRUE) {
                    $tipoarticulo = trim($inputs['tiposarticulos']);
                    $aprobacion = trim($inputs['aprobacion']);
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    
                    $resultado = $tiposarticulos->updateTipoArticulo($id, $tipoarticulo, $aprobacion, $sesion, $fecha);
                    if ($resultado) {
                        return redirect('tiposarticulos')->with(array('mensaje' => 'Familia modificada correctamente.'));
                    } else {
                        return redirect('tiposarticulos')->with(array('mensaje' => 'Error al modificar la familia. Por favor, intente nuevamente.'));
                    }
                } else {
                    return redirect('tiposarticulos/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
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
        if($acceso_usuario->getAccesoValido(6, Auth::user()->fkid_perfil)){
            $tipoarticulo = ArtTipoArticuloModel::withTrashed()->find($id);
            if (is_null($id) || count($tipoarticulo) != 1) {
                abort(404);
            }
            $msj_success = (is_null($tipoarticulo->deleted_at)) ? 'deshabilitada' : 'habilitada';
            $msj_error = (is_null($tipoarticulo->deleted_at)) ? 'deshabilitar' : 'habilitar';
            $art_tipoarticulo = new ArtTipoArticuloModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $art_tipoarticulo->deleteTipoArticulo($id, $sesion, $fecha);

            if ($resultado) {
                return redirect('tiposarticulos')->with(array('mensaje' => 'familia '.$msj_success.' correctamente.'));
            } else {
                return redirect('tiposarticulos')->with(array('mensaje' => 'Error al '.$msj_error.' la familia.'));
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
            'tiposarticulos'  => 'required|min:3|unique:art_tiposarticulos,nombre_tipoarticulo',
            'aprobacion' => 'required'
        );

        if (!is_null($id)) {
            $rules['tiposarticulos'] .= ','.$id.',id_tipoarticulo';
        }

        $messages = array(
            'tiposarticulos.required' => 'Por favor, ingrese el nombre de la familia', 
            'tiposarticulos.unique'   => 'La familia ingresada ya se encuentra en los registros', 
            'tiposarticulos.min'      => 'El nombre de la familia debe tener al menos 3 caracteres',
            'aprobacion.required' => 'Por favor, seleccione la aprobacion correspondiente'
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
