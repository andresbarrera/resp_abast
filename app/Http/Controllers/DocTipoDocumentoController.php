<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\DocTipoDocumentoModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use ABASTV2\Models\DocExtensionModel;
use ABASTV2\Models\TipoObligatoriedadTipoDocumentoModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class DocTipoDocumentoController extends Controller
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
        if($acceso_usuario->getAccesoValido(16, Auth::user()->fkid_perfil)){
            $doc_tiposdocumentos = new docTipoDocumentoModel();
            $tiposdocumentos = $doc_tiposdocumentos->getTiposDocumentos();
            return view('tiposdocumentos.index', compact('tiposdocumentos'));
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
        if($acceso_usuario->getAccesoValido(16, Auth::user()->fkid_perfil)){
            $doc_extension = new DocExtensionModel();
            $extensiones = $doc_extension->getExtensionesDocumentos();
            return view('tiposdocumentos.create', compact('extensiones'));
        }
        else{
            return redirect('home');
        }
        
    }


    /**
     * Comprueba la existencia de un tipo de documento en el sistema.
     * @return json
     */
    public function existeTipoDocumento() {
        if (!isset($_POST['id_tipodocumento'])) {
            $existe = docTipoDocumentoModel::where('detalle_tipodocumento', $_POST['tiposdocumentos'])->count();
        } else {
            $existe = docTipoDocumentoModel::where('detalle_tipodocumento', $_POST['tiposdocumentos'])
                                 ->where('id_tipodocumento', '<>', $_POST['id_tipodocumento'])
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
            $tipodocumento = trim($inputs['tiposdocumentos']);
            $descripcion = trim($inputs['descripcion']);
            $vigencia = trim($inputs['vigencia']);
            $extensiones = $inputs['extensiones'];
            if($vigencia == 'no'){
                $duracion = NULL;    
            }
            else{
                $duracion = trim($inputs['duracion']);
            }
            $obligatoriedad = trim($inputs['obligatoriedad']);
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $doc_tipodocumento = new docTipoDocumentoModel();
            $resultado = $doc_tipodocumento->insertTipoDocumento($tipodocumento, $descripcion, $vigencia, $duracion, $obligatoriedad, $sesion, $fecha);
            if(!$resultado) {
                return redirect('tiposdocumentos')->with(array('mensaje' => 'Error al ingresar el tipo. Por favor, intente nuevamente.'));
            }
            $tipo_documento = docTipoDocumentoModel::where('detalle_tipodocumento', $tipodocumento)->first();
            $id_tipo= $tipo_documento->id_tipodocumento;
            foreach($extensiones as $extension){
                $resultado =  $doc_tipodocumento->insertExtensionTipoDocumento($id_tipo, $extension, $sesion, $fecha);
                if(!$resultado) {
                    return redirect('tiposdocumentos')->with(array('mensaje' => 'Error al ingresar el tipo. Por favor, intente nuevamente.'));
                }                   
            }
            if ($resultado) {
                    //Enviar mail con contraseña de usuario
                    return redirect('tiposdocumentos')->with(array('mensaje' => 'Tipo de documento ingresado correctamente.'));
            } 
        }
        else {
            return redirect('tiposdocumentos/create')->withErrors($this->validateForms($inputs))->withInput();
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
        if($acceso_usuario->getAccesoValido(16, Auth::user()->fkid_perfil)){
            $tiposdocumentos = docTipoDocumentoModel::find($id);
            if (is_null($id) || count($tiposdocumentos) != 1) {
                abort(404);
            }
            $doc_extension = new DocExtensionModel();
            $extensiones = $doc_extension->getExtensionesDocumentos();
            return view('tiposdocumentos.update', compact('tiposdocumentos', 'extensiones'));

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
        if($acceso_usuario->getAccesoValido(16, Auth::user()->fkid_perfil)){
        
            $tiposdocumentos = docTipoDocumentoModel::withTrashed()->find($id);
            if (is_null($id) || count($tiposdocumentos) != 1 || is_null($request)) {
                abort(404);
            } 
            else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs, $id) === TRUE) {
                    $tipodocumento = trim($inputs['tiposdocumentos']);
                    $descripcion = trim($inputs['descripcion']);
                    $vigencia = trim($inputs['vigencia']);
                    if($vigencia == 'no'){
                        $duracion = NULL;    
                    }
                    else{
                        $duracion = trim($inputs['duracion']);
                    }
                    $obligatoriedad = trim($inputs['obligatoriedad']);
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    
                    $resultado = $tiposdocumentos->updateTipoDocumento($id, $tipodocumento, $descripcion, $vigencia, $duracion, $obligatoriedad, $sesion, $fecha);
                    if ($resultado) {
                        return redirect('tiposdocumentos')->with(array('mensaje' => 'Tipo de documento modificado correctamente.'));
                    } 
                    else {
                        return redirect('tiposdocumentos')->with(array('mensaje' => 'Error al modificar el tipo. Por favor, intente nuevamente.'));
                    }
                } 
                else {
                    return redirect('tiposdocumentos/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
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
        if($acceso_usuario->getAccesoValido(16, Auth::user()->fkid_perfil)){
            $tipodocumento = docTipoDocumentoModel::withTrashed()->find($id);
            if (is_null($id) || count($tipodocumento) != 1) {
                abort(404);
            }
            $msj_success = (is_null($tipodocumento->deleted_at)) ? 'deshabilitado' : 'habilitado';
            $msj_error = (is_null($tipodocumento->deleted_at)) ? 'deshabilitar' : 'habilitar';
            $doc_tipodocumento = new docTipoDocumentoModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $doc_tipodocumento->deleteTipoDocumento($id, $sesion, $fecha);

            if ($resultado) {
                return redirect('tiposdocumentos')->with(array('mensaje' => 'Tipo de documento '.$msj_success.' correctamente.'));
            } else {
                return redirect('tiposdocumentos')->with(array('mensaje' => 'Error al '.$msj_error.' el tipo.'));
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
            'tiposdocumentos'  => 'required|min:3',
            'vigencia'  => 'required',  
            'obligatoriedad' => 'required'
        );

        if (!is_null($id)) {
            $rules['tiposdocumentos'] .= ','.$id.',id_tipodocumento';
        }

        $messages = array(
            'tiposdocumentos.required' => 'Por favor, ingrese el nombre del tipo', 
            'tiposdocumentos.min'      => 'El nombre del tipo debe tener al menos 3 caracteres',
            'vigencia.required' => 'Por favor, indique si el tipo de documento tiene vigencia',
            'obligatoriedad.required' => 'Por favor, ingrese el nombre del tipo',
            'extensiones.required' => 'Por favor, ingrese las extensiones de los documentos',
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
