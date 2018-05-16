<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\ArtArticuloModel;
use ABASTV2\Models\ArtTipoArticuloModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class ArticuloController extends Controller
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
        if($acceso_usuario->getAccesoValido(2, Auth::user()->fkid_perfil)){
            $art_articulos = new ArtArticuloModel();
            $articulos = $art_articulos->getArticulos();
            return view('articulo.index', compact('articulos'));    
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
        if($acceso_usuario->getAccesoValido(2, Auth::user()->fkid_perfil)){
            $art_articulos = new ArtArticuloModel();
            $articulos = $art_articulos->getArticulos();
            $tiposarticulos = ArtTipoArticuloModel::all();
            return view('articulo.create', compact('articulos', 'tiposarticulos'));
        }
        else{
            return redirect('home');
        }
    }


    /**
     * Comprueba la existencia de un tipo de articulo en el sistema.
     * @return json
     */
    public function existeArticulo() {
        if (!isset($_POST['id_articulo'])) {
            $existe = ArtArticuloModel::where('nombre_articulo', $_POST['articulos'])->count();
        } else {
            $existe = ArtArticuloModel::where('nombre_articulo', $_POST['articulos'])
                                 ->where('id_articulo', '<>', $_POST['id_articulo'])
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
            $articulo = trim($inputs['articulos']);
            $descripcion = $_POST['descripcion'];
            $tipoarticulo = trim($inputs['tipoarticulo']);

            if (((Auth::user()->fkid_perfil) == 1) || ((Auth::user()->fkid_perfil) == 7) || ((Auth::user()->fkid_perfil) == 8)) {
                # code...
                $estado = 'APROBADO';
            }
            else{
                $estado = 'PENDIENTE';
            }
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $art_articulo = new ArtArticuloModel();

            $resultado = $art_articulo->insertArticulo($articulo, $descripcion, 
                $estado, $tipoarticulo, $sesion, $fecha);
            if ($resultado) {
                //Enviar mail con contraseña de usuario
                return redirect('articulo')->with(array('mensaje' => 'Articulo ingresado correctamente.'));
            } else {
                return redirect('articulo')->with(array('mensaje' => 'Error al ingresar el articulo. Por favor, intente nuevamente.'));
            }
        } else {
            return redirect('articulo/create')->withErrors($this->validateForms($inputs))->withInput();
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
        if($acceso_usuario->getAccesoValido(2, Auth::user()->fkid_perfil)){
            $articulos = ArtArticuloModel::withTrashed()->find($id);
            if (is_null($id) || count($articulos) != 1) {
                abort(404);
            }
            $tiposarticulos = ArtTipoArticuloModel::all();
            return view('articulo.update', compact('articulos', 'tiposarticulos'));        
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
        if($acceso_usuario->getAccesoValido(2, Auth::user()->fkid_perfil)){
            $articulos = ArtArticuloModel::withTrashed()->find($id);
            if (is_null($id) || count($articulos) != 1 || is_null($request)) {
                abort(404);
            } else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs, $id) === TRUE) {
                    $articulo = trim($inputs['articulos']);
                    $tipoarticulo = trim($inputs['tipoarticulo']);
                    $descripcion = $inputs['descripcion'];
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    
                    $resultado = $articulos->updateArticulo($articulo, $descripcion, $tipoarticulo, $sesion, $fecha, $id);
                    if ($resultado) {
                        return redirect('articulo')->with(array('mensaje' => 'Articulo modificado correctamente.'));
                    } else {
                        return redirect('articulo')->with(array('mensaje' => 'Error al modificar el artículo. Por favor, intente nuevamente.'));
                    }
                } else {
                    return redirect('articulo/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
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
        if($acceso_usuario->getAccesoValido(2, Auth::user()->fkid_perfil)){
            $articulo = ArtArticuloModel::withTrashed()->find($id);
            if (is_null($id) || count($articulo) != 1) {
                abort(404);
            }
            $msj_success = (is_null($articulo->deleted_at)) ? 'deshabilitado' : 'habilitado';
            $msj_error = (is_null($articulo->deleted_at)) ? 'deshabilitar' : 'habilitar';
            $art_articulo = new ArtArticuloModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $art_articulo->deleteArticulo($id, $sesion, $fecha);

            if ($resultado) {
                return redirect('articulo')->with(array('mensaje' => 'articulo '.$msj_success.' correctamente.'));
            } else {
                return redirect('articulo')->with(array('mensaje' => 'Error al '.$msj_error.' el articulo.'));
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
            'articulos'  => 'required|min:3|unique:art_articulos,nombre_articulo',
            'tipoarticulo' => 'required'
        );

        if (!is_null($id)) {
            $rules['articulos'] .= ','.$id.',id_articulo';
        }

        $messages = array(
            'articulos.required' => 'Por favor, ingrese el nombre del articulo', 
            'articulos.unique'   => 'El articulo ingresado ya se encuentra en los registros', 
            'articulos.min'      => 'El nombre del articulo debe tener al menos 3 caracteres',
            'tipoarticulo.required' => 'Por favor, seleccione el tipo correspondiente'
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
