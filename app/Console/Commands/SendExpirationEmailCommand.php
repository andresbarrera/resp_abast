<?php

namespace ABASTV2\Console\Commands;

use Illuminate\Console\Command;
use ABASTV2\Models\PrsPersonaModel;
use ABASTV2\Models\CntContratoEmpleadoModel;
use ABASTV2\Models\EmpleadoTipodocumentoModel;
use ABASTV2\Models\AcrSolicitudObservacionModel;
use ABASTV2\Models\StaEstadoSolicitudAcreditacionModel;
use ABASTV2\Models\AcrSolicitudAcreditacionModel;
use ABASTV2\Models\DocTipoDocumentoModel;
use Illuminate\Http\Request;
use ABASTV2\Mail\DocumentoVencido;
use ABASTV2\Mail\AdvertenciaVencimientoDocumento;
use ABASTV2\Mail\AvisoVencimientoDocumento;
use Carbon\Carbon;
use Validator;
use Mail;

class SendExpirationEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envío de aviso por documentos próximos a expirar/expirados.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $contratos = CntContratoEmpleadoModel::all();
        $actual = Carbon::today();
        $content = [
            'title'=> 'Documento Vencido.', 
            'body'=> 'Estimado ',
            'body2'=> ' su documento ',
            'body3'=> ' ha vencido, por lo tanto su acreditación se encuentra rechazada lo que le impedirá realizar pedidos de vehículos. Se recomienda realizar la carga de una nueva versión de este documento lo más pronto posible para reacreditarse.'
        ];
        $content15 = [
            'title'=> 'Aviso expiración de documento.', 
            'body'=> 'Estimado ',
            'body2'=> ' su documento ',
            'body3'=> ' vencerá en 15 días. Se recomienda realizar la carga de una nueva versión de este documento lo más pronto posible para evitar la cancelación de su acreditación.'
        ];
        $content30 = [
            'title'=> 'Aviso expiración de documento.', 
            'body'=> 'Estimado ',
            'body2'=> ' su documento ',
            'body3'=> ' vencerá en 30 días. Se recomienda realizar la carga de una nueva versión de este documento lo más pronto posible para evitar la cancelación de su acreditación.'
        ];
        foreach ($contratos as $contrato) {
            # code...
            $empleadosdocumentos = new EmpleadoTipodocumentoModel();
            $empleados_documentos= $empleadosdocumentos->getTiposDocumentos2($contrato->id_contratoempleado);
            $persona = PrsPersonaModel::find($contrato->fkid_persona);
            foreach ($empleados_documentos as $documento) {
                # code...
                if($documento->termino_empleado_tipodocumento != null && $documento->vigencia_tipodocumento=='si'){
                    $termino = Carbon::createFromFormat('Y-m-d' , $documento->termino_empleado_tipodocumento);
                    $diff = $actual->diffInDays($termino);
                    echo $documento->detalle_tipodocumento." ".$termino."\n";
                    echo $diff."\n";
                    if ($diff == 30) {
                        # code...
                        Mail::to('andresbarrera@workmate.cl')->send(new AvisoVencimientoDocumento($content30, $persona->nombres_persona, $persona->paterno_persona, $documento->detalle_tipodocumento));
                    }
                    if ($diff == 15) {
                        # code...
                        Mail::to('andresbarrera@workmate.cl')->send(new AdvertenciaVencimientoDocumento($content15, $persona->nombres_persona, $persona->paterno_persona, $documento->detalle_tipodocumento));
                    }
                    if ($diff == 0) {
                        # code...
                        $acreditacion = AcrSolicitudAcreditacionModel::where('fkid_contratoempleado', $contrato->id_contratoempleado)->first();
                        echo $acreditacion;
                        if($acreditacion){
                            $acr_acreditacion = new AcrSolicitudAcreditacionModel();
                            $sesion = '65535';
                            $fecha = Carbon::now();
                            $resultado = $acr_acreditacion->procesar($acreditacion->id_solicitudacreditacion, $sesion, $fecha, 'RECHAZAR');
                           if ($resultado) {
                                $acr_solicitudobservacion = new AcrSolicitudObservacionModel();
                                $observacion = $acr_solicitudobservacion->insertSolicitudesObservaciones('El documento '.$documento->detalle_tipodocumento.' ha vencido.', $acreditacion->id_solicitudacreditacion, $sesion, $fecha);
                                $observacion = $acr_solicitudobservacion->insertSolicitudesObservaciones('SOLICITUD RECHAZADA', $acreditacion->id_solicitudacreditacion, $sesion, $fecha);
                            }
                            Mail::to('andresbarrera@workmate.cl')->send(new DocumentoVencido($content, $persona->nombres_persona, $persona->paterno_persona, $documento->detalle_tipodocumento));
                        }
                    }
                }
            }
            
        }
    }

}
