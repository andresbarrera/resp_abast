@extends('layouts.app')
@section('title')
ABAST | ACREDITACIONES
@stop

@section('header')
<section class="row">
<h3 class="col-xs-12">Observaciones de la Acreditación</h3>
<div align="right" class="col-xs-12">
    <a href="javascript:history.back()" class="btn btn-danger">Volver</a>
</div>
</section>
<style type="text/css" media="screen">
	.form-horizontal ul {
	    width:750px;
	    list-style-type:none;
	    list-style-position:outside;
	    margin:0px;
	    padding:0px;
	}
	.form-horizontal div{
	    padding:12px; 
	    border-bottom:1px solid #eee;
	    position:relative;
	}

</style>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <ul class="timeline">
            @foreach($observaciones as $observacion)
            <!-- timeline time label -->
            <li class="time-label">
                <span class="bg-orange">
                    {{$observacion->fecha}}
                </span>
            </li>
            <!-- /.timeline-label -->

            <!-- timeline item -->
            <li>
                <!-- timeline icon -->
                @if($observacion->observacion_solicitudobservacion == 'SOLICITUD APROBADA')
                    <i class="fa fa-check bg-green"></i>
                @endif
                @if($observacion->observacion_solicitudobservacion == 'SOLICITUD RECHAZADA')
                    <i class="fa fa-close bg-red"></i>
                @endif
                @if($observacion->observacion_solicitudobservacion != 'SOLICITUD APROBADA' && $observacion->observacion_solicitudobservacion != 'SOLICITUD RECHAZADA')
                    <i class="fa fa-envelope bg-blue"></i>
                @endif
                <div class="timeline-item">
                    @if($observacion->nombres_usuario == null)
                        <h3 class="timeline-header"><a href="#">ABAST</a> dice</h3>
                    @else
                        <h3 class="timeline-header"><a href="#">{{$observacion->nombres_usuario }} {{$observacion->patapel_usuario}}</a> dice</h3>
                    @endif
                    <div class="timeline-body">
                        {{$observacion->observacion_solicitudobservacion}}
                    </div>

                </div>
            </li>
            @endforeach
        </ul>
        <div align="right">
                <div class="form-group">
                    <div class="col-xs-12">
                        <a href="javascript:history.back()" class="btn btn-danger">Volver</a>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</section>
@endsection