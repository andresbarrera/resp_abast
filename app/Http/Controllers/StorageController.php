<?php

namespace ABASTV2\Http\Controllers;
use ABASTV2\Models\SysUsuarioModel;
use ABASTV2\Models\PrsPersonaModel;
use ABASTV2\Models\CntContratoEmpleadoModel;
use ABASTV2\Models\AreAreaModel;
use ABASTV2\Models\SysPerfilModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use ABASTV2\Models\CntConductorModel;
use ABASTV2\Models\DocTipoDocumentoModel;
use ABASTV2\Models\DocExtensionModel;
use ABASTV2\Models\EmpleadoTipodocumentoModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;

class StorageController extends Controller
{	


	/**
     * Constructor de la clase.
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
    * muestra el formulario para guardar archivos
	*
	* @return Response
	*/
	public function index($id)
	{
		
		$doc_documento = new DocTipoDocumentoModel();
		$documentos = $doc_documento->getTiposDocumentos();
		$cnt_contratoempleado = new CntContratoEmpleadoModel();
        $id_contrato = $cnt_contratoempleado->getIdContrato($id);
        $empleado_tipodocumento = new EmpleadoTipodocumentoModel();
        $empleados_documentos = $empleado_tipodocumento->getTiposDocumentos($id_contrato->id_contratoempleado);
        session(['id_editar' => $id]);
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
		$doc_documento = new DocTipoDocumentoModel();
		$documentos = $doc_documento->getTiposDocumentos();
		$id_edicion = session('id_editar');
		foreach($documentos as $documento){
		    //obtenemos el campo file definido en el formulario
		    $file = $request->file('file'.$i);
		    if($file){
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
				  return back()->with(array('alert' => 'Error al ingresar los archivos, intente nuevamente.'));
				}
			    //obtenemos el nombre del archivo
			       	
			       	$extension = $file->getClientOriginalExtension();
			       	$codigo = "PRS";
			       	$fecha = Carbon::now()->format('Ymdhis');
			       	$sesion = Auth::user()->id_usuario;
			       	$documento_tipo = $documento->detalle_tipodocumento;
			       	$documento_tipo = preg_replace('[\s+]', "", $documento_tipo);
			       	$doc = strtoupper(substr($documento_tipo, 0, 10)); 
			       	$nombre = $codigo.$fecha.$doc.$sesion.$id_edicion.'.'.$extension;
			       	$ruta = 'storage/files/prs/'.$nombre;
			       	$cnt_contratoempleado = new CntContratoEmpleadoModel();
 	           		$id_contrato = $cnt_contratoempleado->getIdContrato($id_edicion);
 	           		$id_tipodocumento = $documento->id_tipodocumento;
 	           		$fecha = Carbon::now();
 	           		$empleado_tipodocumento = new EmpleadoTipodocumentoModel();
 	           		$empleadoTipodocumento = $empleado_tipodocumento->insertEmpleadoTipodocumento($nombre, $ruta, $id_contrato->id_contratoempleado, $id_tipodocumento, $fecha);
 	           		if($empleadoTipodocumento){
 	           			\Storage::disk('prs')->put($nombre,  \File::get($file));
			       		$i++;	
 	           		}
 	           		else{
 	           			return redirect('/formulario/'.$id_edicion); 
 	           		}
			}
			else{
				$i++;
			}
		}
		return redirect('/formulario/'.$id_edicion)->with(array('mensaje' => 'Ingreso de archivos correcto.')) ;

	}

}
