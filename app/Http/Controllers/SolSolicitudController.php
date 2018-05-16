<?php

namespace ABASTV2\Http\Controllers;

use ABASTV2\Models\PrsPersonaModel;
use ABASTV2\Models\SysAccesoUsuarioModel;
use ABASTV2\Models\CntContratoEmpleadoModel;
use ABASTV2\Models\DocTipoDocumentoModel;
use ABASTV2\Models\SysPerfilModel;
use ABASTV2\Models\SysUsuarioModel;
use ABASTV2\Models\ArtArticuloModel;
use ABASTV2\Models\ArtTipoArticuloModel;
use ABASTV2\Models\AreAreaModel;
use ABASTV2\Models\CecCentroCostoModel;
use ABASTV2\Models\SolSolicitudModel;

use ABASTV2\Mail\NuevaAcreditacion;
use ABASTV2\Mail\ApruebaAcreditacion;
use ABASTV2\Mail\RechazaAcreditacion;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use Mail;
use DB;

class SolSolicitudController extends Controller
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
            if($acceso_usuario->getAccesoValidoMenu(2,Auth::user()->fkid_perfil)) {
                $estado = new StaEstadoSolicitudAcreditacionModel();
                $estados = $estado->getEstadosSolicitudesAcreditaciones();
                
                $observacion = new AcrSolicitudObservacionModel();
                $observaciones = $observacion->getSolicitudesObservaciones();
                
                $acred = new AcrSolicitudAcreditacionModel();
                $acreditaciones = $acred->getAcreditaciones();
                return view('acreditaciones.index', compact('estados', 'observaciones', 'acreditaciones'));
            }
            else{
                return redirect('home');
            }


    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function aprobadas()
    {
        //  
            $acceso_usuario = new SysAccesoUsuarioModel();
            if($acceso_usuario->getAccesoValidoMenu(4,Auth::user()->fkid_perfil)) {
                $estado = new StaEstadoSolicitudAcreditacionModel();
                $estados = $estado->getEstadosSolicitudesAcreditaciones();
                
                $observacion = new AcrSolicitudObservacionModel();
                $observaciones = $observacion->getSolicitudesObservaciones();
                
                $acred = new AcrSolicitudAcreditacionModel();
                $acreditaciones = $acred->getAcreditacionesAprobadas();
                return view('acreditaciones.index', compact('estados', 'observaciones', 'acreditaciones'));
            }
            else{
                return redirect('home');
            }


    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rechazadas()
    {
        //  
            $acceso_usuario = new SysAccesoUsuarioModel();
            if($acceso_usuario->getAccesoValidoMenu(4,Auth::user()->fkid_perfil)) {
                $estado = new StaEstadoSolicitudAcreditacionModel();
                $estados = $estado->getEstadosSolicitudesAcreditaciones();
                
                $observacion = new AcrSolicitudObservacionModel();
                $observaciones = $observacion->getSolicitudesObservaciones();
                
                $acred = new AcrSolicitudAcreditacionModel();
                $acreditaciones = $acred->getAcreditacionesRechazadas();
                return view('acreditaciones.index', compact('estados', 'observaciones', 'acreditaciones'));
            }
            else{
                return redirect('home');
            }


    }

    public function listaarticulos($id)
    {
        //$articulo = new ArtArticuloModel();
        //$articulos = $articulo->getArticulosTipo($id); 
        $articulos = DB::table("art_articulos")->where("fkid_tipoarticulo",$id)->pluck("nombre_articulo","id_articulo");
        return json_encode($articulos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //$tipoarticulo = new ArtTipoArticuloModel();
        $area = new AreAreaModel();
        $centrocosto = new CecCentroCostoModel();
        $solicitud = new SolSolicitudModel();
        //$user = new SysUsuarioModel();
        $tipos_articulos = DB::table("art_tiposarticulos")->pluck("nombre_tipoarticulo","id_tipoarticulo");

        //$tipos_articulos = $tipoarticulo->getTiposArticulosSimple();

        $centros_costos = $centrocosto->getCentrosCostos();
        $areas = $area->getAreas();
        $prioridades = $solicitud->getPrioridades();

        return view('solicitudes.create', compact('tipos_articulos', 'centros_costos', 'areas', 'prioridades'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        //
        $inputs = $this->getInputs($request->all());
        $observacion = $inputs['observacion'];
        $sesion = Auth::user()->id_usuario;
        $fecha = Carbon::now();
        $acr_solicitudobservacion = new AcrSolicitudObservacionModel();
        $acr_acreditacion = new AcrSolicitudAcreditacionModel(); 
        $acreditacion = $acr_acreditacion->getAcreditacionesContrato($id);
        $resultado = $acr_solicitudobservacion->insertSolicitudesObservaciones($observacion, $acreditacion[0]->id_solicitudacreditacion, $sesion, $fecha);
        if($resultado){
            return redirect('acreditacion')->with(array('mensaje' => 'Comentario ingresado correctamente.'));
        }
        else{
            return redirect('acreditacion')->with(array('alert' => 'Hubo un problema con su solicitud.'));    
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
        $acceso_usuario = new SysAccesoUsuarioModel();
        if($acceso_usuario->getAccesoValidoMenu(4, Auth::user()->fkid_perfil)){
            $personas = PrsPersonaModel::withTrashed()->find($id);            
            if (is_null($id) || count($personas) != 1) {
                abort(404);
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
            
            $tipos_contratosempleados = new CntTipoContratoEmpleadoModel();
            $tiposcontratos = $tipos_contratosempleados->getTiposContratosEmpleados();
            $estados_contratosempleados = new CntEstadoContratoEmpleadoModel();
            $estadoscontratos = $estados_contratosempleados->getEstadosContratosEmpleados();
            $cnt_contratoempleado = new CntContratoEmpleadoModel();
            $contratosempleados = $cnt_contratoempleado->getContratosEmpleados();
            $cnt_contratoempresa = new CntContratoEmpresaModel();
            $contratosempresas = $cnt_contratoempresa->getContratosEmpresas();

            $estado = new StaEstadoSolicitudAcreditacionModel();
            $estados = $estado->getEstadosSolicitudesAcreditaciones();
                
            $observacion = new AcrSolicitudObservacionModel();
            $observaciones = $observacion->getSolicitudesObservaciones();
            $id_persona = $id;
            $acred = new AcrSolicitudAcreditacionModel();
            $acreditaciones = $acred->getAcreditacionesPropias($id);
            $fecha = Carbon::now();
            $sesion = Auth::user()->id_usuario;
            $fecha_actual = $fecha->format('d/m/y');

            $fecha_nacimiento = $personas->fnacimiento_persona;
            $f_nacimiento = explode('-', $fecha_nacimiento);
            $fin = $f_nacimiento[2].'/'.$f_nacimiento[1].'/'.$f_nacimiento[0];
            return view('acreditaciones.show', compact('acreditaciones','personas', 'contratosempresas', 'tiposcontratos', 'estadoscontratos', 'contratosempleados', 'extensiones', 'documentos', 'empleados_documentos', 'observaciones', 'estados', 'id_persona', 'id_contrato', 'fin'));
        }
        else{
            return redirect('home');
        }  
    }

    /**
    *
    */
    public function observaciones($id){

        $acr_solicitudobservacion = new AcrSolicitudObservacionModel();
        $observaciones = $acr_solicitudobservacion->getSolicitudesObservacionesAcreditaciones($id);
        $perfil = Auth::user()->fkid_perfil;
        return view('acreditaciones.observaciones', compact('observaciones', 'perfil'));
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function aprobar($id)
    {
        //
            $acreditacion = AcrSolicitudAcreditacionModel::withTrashed()->find($id);
            $msj_success = ($acreditacion->detalle_estadosolicitudacreditacion="APROBADA") ? 'aprobada' : 'rechazada';
            $msj_error = ($acreditacion->detalle_estadosolicitudacreditacion="APROBADA") ? 'aprobar' : 'rechazar' ;
            $acr_acreditacion = new AcrSolicitudAcreditacionModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $acr_acreditacion->procesar($id, $sesion, $fecha, 'APROBAR');
           if ($resultado) {
                $acr_solicitudobservacion = new AcrSolicitudObservacionModel();
                $observacion = $acr_solicitudobservacion->insertSolicitudesObservaciones('SOLICITUD APROBADA', $id, $sesion, $fecha);
                $contrato =CntContratoEmpleadoModel::find($acreditacion->fkid_contratoempleado);
                $persona = PrsPersonaModel::find($contrato->fkid_persona);
                //$usuario = SysUsuarioModel::where('fkid_persona', $persona->id_persona)->first();
                $this->aprobarAcreditacion('andresbarrera@workmate.cl', $persona->nombres_persona, $persona->paterno_persona);
                return redirect('acreditacion')->with(array('mensaje' => 'Acreditación '.$msj_success.' correctamente.'));
            } else {
                return redirect('acreditacion')->with(array('alert' => 'Error al '.$msj_error.' la acreditación.'));
            }    

        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rechazar($id)
    {
        //
            $acreditacion = AcrSolicitudAcreditacionModel::withTrashed()->find($id);
            $msj_success = ($acreditacion->detalle_estadosolicitudacreditacion="RECHAZADA") ? 'rechazada' : 'aprobada'  ;
            $msj_error = ($acreditacion->detalle_estadosolicitudacreditacion="RECHAZADA") ? 'rechazar' : 'aprobar' ;
            $acr_acreditacion = new AcrSolicitudAcreditacionModel();
            $sesion = Auth::user()->id_usuario;
            $fecha = Carbon::now();
            $resultado = $acr_acreditacion->procesar($id, $sesion, $fecha, 'RECHAZAR');
           if ($resultado) {
                $acr_solicitudobservacion = new AcrSolicitudObservacionModel();
                $observacion = $acr_solicitudobservacion->insertSolicitudesObservaciones('SOLICITUD RECHAZADA', $id, $sesion, $fecha);
                $contrato =CntContratoEmpleadoModel::find($acreditacion->fkid_contratoempleado);
                $persona = PrsPersonaModel::find($contrato->fkid_persona);
                $this->rechazarAcreditacion('andresbarrera@workmate.cl', $persona->nombres_persona, $persona->paterno_persona);
                return redirect('acreditacion')->with(array('mensaje' => 'Acreditación '.$msj_success.' correctamente.'));
            } else {
                return redirect('acreditacion')->with(array('alert' => 'Error al '.$msj_error.' la acreditación.'));
            }    

        
        
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
     * Show the application sendMail.
     *
     * @return \Illuminate\Http\Response
     */
    private function enviaNuevaAcreditacion($receptor, $nombre, $apellido)
    {
        $content = [
            'title'=> 'Nueva Acreditación.', 
            'body'=> 'El usuario ',
            'resto_body' => ' ha solicitado acreditarse en el sistema.'
            ];
        Mail::to($receptor)->send(new NuevaAcreditacion($content, $nombre, $apellido));
        return true;
    }

    /**
     * Show the application sendMail.
     *
     * @return \Illuminate\Http\Response
     */
    private function aprobarAcreditacion($receptor, $nombre, $apellido)
    {
        $content = [
            'title'=> 'Acreditación Aprobada.', 
            'body'=> 'Se ha aprobado la acreditación para el usuario:  '
            ];
        Mail::to($receptor)->send(new ApruebaAcreditacion($content, $nombre, $apellido));
        return true;
    }


    /**
     * Show the application sendMail.
     *
     * @return \Illuminate\Http\Response
     */
    private function rechazarAcreditacion($receptor, $nombre, $apellido)
    {
        $content = [
            'title'=> 'Acreditación Rechazada.', 
            'body'=> 'Se ha rechazado la acreditación para el usuario:  '
            ];
        Mail::to($receptor)->send(new RechazaAcreditacion($content, $nombre, $apellido));
        return true;
    }

    
}
