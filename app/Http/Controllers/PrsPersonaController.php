<?php

namespace ABASTV2\Http\Controllers;

use ABASTV2\Models\PrsPersonaModel;
use ABASTV2\Models\AreAreaModel;
use ABASTV2\Models\SysPerfilModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use ABASTV2\Models\CntConductorModel;
use ABASTV2\Models\DocTipoDocumentoModel;
use ABASTV2\Models\DocExtensionModel;
use ABASTV2\Models\EmpleadoTipodocumentoModel;

use ABASTV2\Models\SysUsuarioModel;
use ABASTV2\Models\CntContratoEmpresaModel;
use ABASTV2\Models\CntContratoEmpleadoModel;
use ABASTV2\Models\CntTipoContratoEmpleadoModel;
use ABASTV2\Models\CntEstadoContratoEmpleadoModel;
use ABASTV2\Models\AcrSolicitudAcreditacionModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class PrsPersonaController extends Controller
{   


    /**
     * Constructor de la clase.
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
    }



    public function index($id)
    {
        
        $doc_documento = new DocTipoDocumentoModel();
        $documentos = $doc_documento->getTiposDocumentos();
        $cnt_contratoempleado = new CntContratoEmpleadoModel();
        $id_contrato = $cnt_contratoempleado->getIdContrato($id);
        $empleado_tipodocumento = new EmpleadoTipodocumentoModel();
        $empleados_documentos = $empleado_tipodocumento->getTiposDocumentos($id_contrato->id_contratoempleado);
        $doc_extension = new DocExtensionModel();
        $extensiones = $doc_extension->getExtensionesDocumentos();
        return view('storage.archivo', compact('extensiones', 'documentos', 'empleados_documentos', 'id'));
    }


    /**
    * guarda un archivo en nuestro directorio local.
    *
    * @return Response
    */
    public function save(Request $request)
    {
             
        $input = $request->all();
        $i=1;
        $id_editar = session('id_editar');
        $cnt_contratoempleado = new CntContratoEmpleadoModel();
        $id_contrato = $cnt_contratoempleado->getIdContrato($id_editar);
        $doc_documento = new DocTipoDocumentoModel();
        $documentos = $doc_documento->getTiposDocumentos();
        $emp_documento = new EmpleadoTipodocumentoModel();
        $empleados = $emp_documento->getTiposDocumentos($id_contrato->id_contratoempleado);
        foreach($empleados as $documento){
            //obtenemos el campo file definido en el formulario
            $file = $request->file('file'.$i);
            if($file){
                if($documento->vigencia_tipodocumento == 'si'){    
                    if(!$input['fecha'.$i]){
                        return back()->with(array('alert' => "Por favor indique fecha de inicio para el documento ".$documento_tipo = $documento->detalle_tipodocumento));
                    }
                }
                $rules = array(
                'file'.$i  => 'mimes:pdf,jpeg|max:30000'
                );

                $messages = array(
                    'file'.$i.'max'      => 'Los archivos pueden pesar máximo 3MB',
                    'file'.$i.'mimes' => 'Por favor, ingrese un archivo del tipo correcto'
                );
                 //mimes:jpeg,bmp,png and for max size max:10000
                // doing the validation, passing post data, rules and the messages
                $validator = Validator::make($input, $rules, $messages);
                if ($validator->fails()) {
                  // send back to the page with the input data and errors
                  return back()->with(array('alert' => "Error al ingresar los archivos, intente nuevamente."));
                }
                //obtenemos el nombre del archivo
                    
                    $extension = $file->getClientOriginalExtension();
                    $codigo = "PRS";
                    $fecha = Carbon::now()->format('Ymdhis');
                    $sesion = Auth::user()->id_usuario;
                    $documento_tipo = $documento->detalle_tipodocumento;
                    $duracion = $documento->duracion_tipodocumento;
                    $documento_tipo = preg_replace('[\s+]', "", $documento_tipo);
                    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
                    $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
                    $documento_tipo = str_replace($no_permitidas, $permitidas ,$documento_tipo);
                    $doc = strtoupper(substr($documento_tipo, 0, 10)); 
                    $nombre = $codigo.$fecha.$doc.$sesion.$id_editar.'.'.$extension;
                    $ruta = 'storage/files/prs/'.$nombre;
                    $id_tipodocumento = $documento->id_tipodocumento;
                    $fecha_crea = Carbon::now(); 
                    if($documento->vigencia_tipodocumento == 'no'){
                        $fecha = Carbon::now();
                        $fecha_termino = null;
                    }
                    else{
                        $fecha = $input['fecha'.$i];
                        $fecha = Carbon::createFromFormat('d/m/y', $fecha)->toDateTimeString();
                        if(!$input['termino'.$i]){
                            //$fecha2 = date('Y-d-m', strtotime($fecha));
                            $fecha_termino = date('Y-m-d', strtotime($fecha));
                            $fecha_termino = date('Y-m-d', strtotime('+'.$duracion.' years', strtotime($fecha_termino)));
                        }
                        else{
                            $fecha_termino = $input['termino'.$i];
                            $fecha_termino = Carbon::createFromFormat('d/m/y', $fecha_termino)->toDateTimeString();
                        }
                    }
                    $empleado_tipodocumento = new EmpleadoTipodocumentoModel();
                    $empleadoTipodocumento = $empleado_tipodocumento->insertEmpleadoTipodocumento($nombre, $ruta, $id_contrato->id_contratoempleado, $id_tipodocumento, $fecha, $fecha_termino, $fecha_crea);
                    if($empleadoTipodocumento){
                        \Storage::disk('prs')->put($nombre,  \File::get($file));
                        $i++;   
                    }
                    else{
                        return redirect('personas/'.$id_editar.'/edit'); 
                    }
            }
            else{
                $i++;
            }
        }
        if(!session('mensaje')){
            return redirect('personas/'.$id_editar.'/edit')->with(array('mensaje' => "Ingreso de archivos correcto.")) ;
        }
        else{
            return redirect('personas/'.$id_editar.'/edit');
        }

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
        $area = SysUsuarioModel::where('fkid_persona', $id)->first();
        session()->forget('key');
        session(['id_editar' => $id]);
        if(((Auth::user()->fkid_perfil == 1 or Auth::user()->fkid_perfil == 2 or Auth::user()->fkid_perfil == 3 or Auth::user()->fkid_perfil == 8) and (Auth::user()->fkid_area == $area->fkid_area)) or $id == Auth::user()->fkid_persona){
            $personas = PrsPersonaModel::withTrashed()->find($id);            
            if (is_null($id) || count($personas) != 1) {
                abort(404);
            }

            $acr_acred = new AcrSolicitudAcreditacionModel();
            if($personas->fnacimiento_persona){
                
                $fecha_nac = date('d/m/Y', strtotime($personas->fnacimiento_persona));

            }
            else{
                $fecha_nac = $personas->fnacimiento_persona;
            }

            $doc_documento = new DocTipoDocumentoModel();
            $documentos = $doc_documento->getTiposDocumentos();
            $cnt_contratoempleado = new CntContratoEmpleadoModel();
            $id_contrato = $cnt_contratoempleado->getIdContrato($id);
            $empleado_tipodocumento = new EmpleadoTipodocumentoModel();
            $empleados_documentos = $empleado_tipodocumento->getTiposDocumentos($id_contrato->id_contratoempleado);
            $doc_extension = new DocExtensionModel();
            $extensiones = $doc_extension->getExtensionesDocumentos();
            $acred = new AcrSolicitudAcreditacionModel();
            $datosacreditacion = $acred::where('fkid_contratoempleado', $id_contrato->id_contratoempleado)->first();
            $cant_docs = DocTipoDocumentoModel::where('deleted_at', null)->count();
            $tipos_contratosempleados = new CntTipoContratoEmpleadoModel();
            $tiposcontratos = $tipos_contratosempleados->getTiposContratosEmpleados();
            $estados_contratosempleados = new CntEstadoContratoEmpleadoModel();
            $estadoscontratos = $estados_contratosempleados->getEstadosContratosEmpleados();
            //$cnt_contratoempleado = new CntContratoEmpleadoModel();
            $contratoempleado = CntContratoEmpleadoModel::where('id_contratoempleado', $id_contrato->id_contratoempleado)->first();
            if($contratoempleado->inicio_contratoempleado){
                $inicio_con = date('d/m/Y', strtotime($contratoempleado->inicio_contratoempleado));
            }
            else{
                $inicio_con = null;
                $contratoempleado->inicio_contratoempleado ;
            }
            if($contratoempleado->termino_contratoempleado){
                $termino_con = date('d/m/Y', strtotime($contratoempleado->termino_contratoempleado));
            }
            else{
                $termino_con = null;
                $contratoempleado->termino_contratoempleado ;
            }

            $cnt_contratoempresa = new CntContratoEmpresaModel();
            $contratosempresas = $cnt_contratoempresa->getContratosEmpresas();
            return view('personas.update', compact('personas', 'contratosempresas', 'tiposcontratos', 'estadoscontratos', 'contratoempleado', 'extensiones', 'documentos', 'empleados_documentos', 'id', 'datosacreditacion', 'fecha_nac', 'cant_docs', 'inicio_con', 'termino_con'));    
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
        session()->forget('key');
        session(['id_editar' => $id]);
        $acceso_usuario = new SysAccesoUsuarioModel();
        $area = SysUsuarioModel::where('fkid_persona', $id)->first();
            $prs_persona = PrsPersonaModel::withTrashed()->find($id);
            $modelo_persona = new PrsPersonaModel();
            if (is_null($id) || count($prs_persona) != 1 || is_null($request)) {
                abort(404);
            } else {
                $inputs = $this->getInputs($request->all());
                if ($this->validateForms($inputs, $id) === TRUE) {
                    $nombres = ucfirst(strtolower($inputs['nombres']));
                    $patapel = ucfirst(strtolower(trim($inputs['patapel'])));
                    $matapel = ucfirst(strtolower(trim($inputs['matapel'])));
                    $rut = trim($inputs['rut']);
                    if($modelo_persona->getRutUnico($rut) && !$modelo_persona->getNombreApellido($nombres, $patapel)){
                        return redirect('personas/'.$id.'/edit')->with(array('alert' => 'El rut ya se encuentra en el sistema.'));
                    }
                    $digito = $inputs['digito'];
                    if($this->dv($rut) != $digito){
                        return redirect('personas/'.$id.'/edit')->with(array('alert' => 'El rut que ha ingresado no es correcto.'));
                    }
                    $nacimiento = $inputs['nacimiento'];
                    $nac = explode('/', $nacimiento);
                    //$nac = $nac[2].'-'.$nac[1].'-'.$nac[0];
                    $nacimiento_formato = $nac[2].'-'.$nac[1].'-'.$nac[0];
                    $tiposcontratos = $inputs['tiposcontratos'];
                    $contratoempresa = $inputs['contratoempresa'];
                    if($tiposcontratos == '1'){
                        $fin = null;    
                    }
                    else{
                        $fincontrato = $inputs['fincontrato'];
                        $fincon = explode('/', $fincontrato);
                        $fin = $fincon[2].'-'.$fincon[1].'-'.$fincon[0];

                    }
                    $iniciocontrato = $inputs['iniciocontrato'];
                    $iniciocon = explode('/', $iniciocontrato);
                    $inicio = $iniciocon[2].'-'.$iniciocon[1].'-'.$iniciocon[0];

                    $observacion = $inputs['observacion'];
                    $sesion = Auth::user()->id_usuario;
                    $fecha = Carbon::now();
                    $resultado1 = $prs_persona->updatePersona($id, $rut, $digito, $nombres, $patapel, $matapel, $nacimiento_formato, $sesion, $fecha);
                    $cnt_contratoempleado = new CntContratoEmpleadoModel();
                    $id_persona = $cnt_contratoempleado->getIdContrato($id);
                    $resultado2 = $cnt_contratoempleado->updateContratoEmpleado($id_persona->id_contratoempleado, $inicio, $fin, 'activo', $observacion, $tiposcontratos, '3', $contratoempresa, $id, $sesion, $fecha);
                    
                    if($resultado1 && $resultado2){
                        SysUsuarioModel::where('fkid_persona', $id)->update(['nombres_usuario' => $nombres, 'patapel_usuario' => $patapel, 'matapel_usuario' => $matapel]);
                        return redirect('personas/'.$id.'/edit')->with(array('mensaje' => 'Usuario modificado correctamente.'));
                    }
                    else 
                    {
                        return redirect('personas/'.$id.'/edit')->with(array('alert' => 'Error al modificar el usuario. Por favor, intente nuevamente.'));
                    }
                } else {
                    return redirect('personas/'.$id.'/edit')->withErrors($this->validateForms($inputs, $id))->withInput();
                }
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
    }


    /**
     * Método que valida las entradas en el formulariio
     * @param $inputs Array. Entradas del formulario
     * @return $validation Array. Errores de validación
     */
    private function validateForms($inputs = array(), $id = null) {
        $rules = array(
            'nombres'  => 'required|min:3',
            'patapel'  => 'required|min:3',
            'rut' => 'required|digits_between:7,8',
            'digito' => 'required|regex:/(^([0-9kK])(\d+)?$)/u',
            'iniciocontrato' => 'required'

        );

        $messages = array(
            'nombres.required' => 'Por favor, ingrese un nombre', 
            'nombres.min'      => 'El nombre debe tener al menos 3 caracteres',
            'patapel.required' => 'Por favor, ingrese un apellido paterno',
            'patapel.min' => 'El apellido paterno debe tener al menos 3 caracteres',
            'rut.required' => 'Por favor, ingrese su rut',
            'rut.digits_between' => 'El rut debe estar compuesto de números y tener entre 7 a 8 dígitos',
            'rut.unique' => 'El rut ingresado ya se encuentra en el sistema',
            'digito.required' => 'Por favor, ingrese el dígito verificador',
            'digito.regex' => 'El dígito debe ser un número de 0 a 9 ó k',
            'iniciocontrato.required' => 'Por favor, ingrese la fecha de inicio',
            'fincontrato.required' => 'Por favor, ingrese la fecha de termino'


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
    * Método para dterminar la validez del rut
    * @param int rut
    * @return int dv
    */
    public function dv($r){
        $s=1;
        for($m=0;$r!=0;$r/=10)
            $s=($s+$r%10*(9-$m++%6))%11;
        return chr($s?$s+47:75);
    }
}
