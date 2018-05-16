@extends('layouts.app')
@section('title')
ABAST | EDITAR DATOS PERSONALES
@stop
@push('javascript')
<link rel="stylesheet" href={{asset('css/update-persona.css')}} >
<link rel="stylesheet" href={{asset('css/spinner.css')}} >
<link rel="stylesheet" href={{asset('css/archivos-tabla.css')}} >
@if (Session::has('mensaje'))
<script type="text/javascript">
	toastr.options = {
		"preventDuplicates": true,
		"preventOpenDuplicates": true
	};
	toastr.success("{{ Session::get('mensaje') }}", 'Información');
</script>
<?php  session()->forget('mensaje');?>
@endif
@if (Session::has('alert'))
<script type="text/javascript">toastr.error("{{ Session::get('alert') }}", 'Información');</script>
<?php  session()->forget('alert');?>
@endif
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
<div id="overlay"></div>
<div id="spinner" class="spinner" style="display:none;">
    <div align="center">
    	CARGANDO...
    </div>
    <br>
    <img id="img-spinner" src="{{ asset('245.gif') }}" alt="Cargando..."/>
    <br>
</div>
@endpush
@section('header')
@endsection
@section('content')
<section class="row">
	<div class="col-xs-12">
		<hr />
		<div align="left">
			@if($datosacreditacion)
				<a href="{{ URL::to('/acreditacion/'.$datosacreditacion->id_solicitudacreditacion.'/observaciones') }}" class="btn-lg btn-primary">Observaciones de la acreditación</a>
			@endif
		</div>
		<div align="right">
			@if(!$datosacreditacion)
					<a href="{{ URL::to('acreditacion/'.$personas->id_persona) }}" class="btn btn-success disabled" id="acred">Acreditar</a>
			@else
				@if($datosacreditacion && ($datosacreditacion->fkid_estadosolicitud == 1 || $datosacreditacion->fkid_estadosolicitud == 5 ))
					<a class="btn btn-warning" disabled>Pendiente</a>
				@endif
				@if($datosacreditacion && ($datosacreditacion->fkid_estadosolicitud == 2 ))
					<a href="{{ URL::to('#') }}" class="btn-lg btn-success">Acreditación Aprobada</a>
				@else 
					@if($datosacreditacion && $datosacreditacion->fkid_estadosolicitud == 3)
						<a href="{{ URL::to('#') }}" class="btn btn-danger">Acreditación Rechazada</a>
						<a href="{{ URL::to('acreditacion/'.$personas->id_persona) }}" class="btn btn-success disabled" id="acred"><i class="glyphicon glyphicon-repeat"></i> Reinicio de Proceso</a>
					@endif
				@endif
			@endif
		</div>

	</div>
	<h4 class="col-xs-12 required" style="color:#FF0000;">* Campos obligatorios</h4>
	<h4 class="col-xs-12 required" style="color:#FF0000;">** Campos obligatorios para la acreditación</h4>
</section>

<section class="row">
	<div class="col-xs-12">

		<form name="modificar" method="post" role="form" action="{{ URL::to('personas/'.$personas->id_persona) }}" class="form-horizontal">
			<input type="hidden" name="_method" value="put">
			{{ csrf_field() }}
			<div class="box box-warning">			
			<h4 class="col-xs-12"> Datos Personales </h4>
			<div class="form-group">

				<div class="col-sm-2 col-md-3">
					<span class="required-asterik">**</span>
					<label for="Rut" class="control-label">Rut</label>
					@if($personas->rutd_persona)
						<input type="text" name="rut" value="{{ old('rut') ? old('rut') : $personas->rutd_persona }}" class="form-control" id="rut" autocomplete="off" placeholder="Rut" autofocus="true" maxlength="8" style="<?php if ($errors->has('rut')) {?> border: 1px solid #F00; <?php } ?>" / required readonly>
					@else
						<input type="text" name="rut" value="{{ old('rut') ? old('rut') : $personas->rutd_persona }}" class="form-control" id="rut" autocomplete="off" placeholder="Rut" autofocus="true" maxlength="8" style="<?php if ($errors->has('rut')) {?> border: 1px solid #F00; <?php } ?>" / required>
					@endif
					@if ($errors->has('rut'))
					<p class="alert alert-danger">{{ $errors->first('rut') }}</p>
					@endif
				</div>
				<div class="col-md-1">
					<span class="required-asterik">**</span>
					<label for="Digito" class="control-label">DV</label>
					@if($personas->verifd_persona)
						<input type="text" name="digito" value="{{ old('digito') ? old('digito') : $personas->verifd_persona }}" class="form-control" id="digito" autocomplete="off" placeholder="DV" autofocus="true" maxlength="1" style="<?php if ($errors->has('digito')) {?> border: 1px solid #F00; <?php } ?>" size="1" / required readonly>
					@else
						<input type="text" name="digito" value="{{ old('digito') ? old('digito') : $personas->verifd_persona }}" class="form-control" id="digito" autocomplete="off" placeholder="DV" autofocus="true" maxlength="1" style="<?php if ($errors->has('digito')) {?> border: 1px solid #F00; <?php } ?>" size="1" />
					@endif
					@if ($errors->has('digito'))
					<p class="alert alert-danger">{{ $errors->first('digito') }}</p>
					@endif
				</div>
				<div class="col-md-1">
					
				</div>
				<div class="col-sm-4 col-md-4">
					<span class="required-asterik">*</span>
					<label for="Nombres" class="control-label">Nombres</label>
					<input type="text" name="nombres" value="{{ old('nombres') ? old('nombres') : $personas->nombres_persona  }}" class="form-control" id="nombres" autocomplete="off" placeholder="Nombres" autofocus="true" maxlength="32" style="<?php if ($errors->has('nombres')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('nombres'))
					<p class="alert alert-danger">{{ $errors->first('nombres') }}</p>
					@endif
				</div>
				<div class="col-sm-3 col-md-3">
					<span class="required-asterik">*</span>
					<label for="Patapel" class="control-label">Apellido Paterno</label>
					<input type="text" name="patapel" value="{{ old('patapel') ? old('patapel') : $personas->paterno_persona }}" class="form-control" id="patapel" autocomplete="off" placeholder="Apellido Paterno" autofocus="true" maxlength="32" style="<?php if ($errors->has('patapel')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('patapel'))
					<p class="alert alert-danger">{{ $errors->first('patapel') }}</p>
					@endif
				</div>
				<div class="col-sm-3 col-md-3">
					<label for="Matapel" class="control-label">Apellido Materno</label>
					<input type="text" name="matapel" value="{{ old('matapel') ? old('matapel') : $personas->materno_persona }}" class="form-control" id="matapel" autocomplete="off" placeholder="Apellido Materno" autofocus="true" maxlength="32" style="<?php if ($errors->has('matapel')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('patapel'))
					<p class="alert alert-danger">{{ $errors->first('matapel') }}</p>
					@endif
				</div>
				<div class="col-sm-2 col-md-2">
					<!-- Date -->
					<span class="required-asterik">**</span>
	                <label for="nacimiento" class="control-label">Fecha de nacimiento</label>
	                  <input type="text" class="form-control" id="nacimiento" name="nacimiento" value="{{ old('nacimiento') ? old('nacimiento') : $fecha_nac }}">
	                <!-- /.input group -->	
				</div>
			</div>
			
			
			<div class="box box-warning">
			<h4 class="col-xs-12"> Datos de Contrato </h4>
			<div class="form-group">
				<div class="col-sm-7 col-md-4">
					<label for="tiposcontratos" class="control-label">Tipo de Contrato</label>
					<br>
					<select name="tiposcontratos" class="tiposcontratos" id="tiposcontratos" width="150"  style="width: 300px">
						@foreach ($tiposcontratos as $tipocontrato)
							<option value="{{ $tipocontrato->id_tipocontratoempleado }}"{{ (old('tiposcontratos')) ? (old('tiposcontratos') == $tipocontrato->id_tipocontratoempleado) ? ' selected="selected"' : '' : ($contratoempleado->fkid_tipocontratoempleado == $tipocontrato->id_tipocontratoempleado)  ? ' selected="selected"' : '' }}>{{ $tipocontrato->detalle_tipocontratoempleado }}</option>
						@endforeach
					</select>
					@if ($errors->has('tipocontrato'))
					<p class="alert alert-danger">{{ $errors->first('tipocontrato') }}</p>
					@endif	
				</div>
				<div class="col-sm-7 col-md-8">
					<label for="contratoempresa" class="control-label">Empresa</label>
					<br>
					<select name="contratoempresa" class="contratoempresa" id="contratoempresa" width="150"  style="width: 300px">
						@foreach ($contratosempresas as $contratoempresa)
						<option value="{{ $contratoempresa->id_contratoempresa }}"{{ (old('contratoempresa') == $contratoempresa->id_contratoempresa) ? ' selected="selected"' : '' }}>{{$contratoempresa->codigo_contratoempresa }} {{ $contratoempresa->detalle_contratoempresa }}</option>
						@endforeach
					</select>
					@if ($errors->has('contratoempresa'))
					<p class="alert alert-danger">{{ $errors->first('contratoempresa') }}</p>
					@endif	
				</div>
				<div class="col-sm-6 col-md-4">
					<!-- Date -->
	              <div class="form-group">
	                <label for="iniciocontrato" class="control-label">Fecha de inicio de contrato</label>
	                <div class="input-group date">
	                  <span class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </span>
	                  <input type="text" class="form-control pull-right" id="iniciocontrato" name="iniciocontrato" value="{{ old('iniciocontrato') ? old('iniciocontrato') : $inicio_con }}">
	                </div>
	                @if ($errors->has('iniciocontrato'))
						<p class="alert alert-danger">{{ $errors->first('iniciocontrato') }}</p>
					  @endif
	                <!-- /.input group -->
	              </div>	
				</div>
				<div class="col-sm-6 col-md-4">
					<!-- Date -->
	              <div class="form-group">
	                <label for="fincontrato" class="control-label">Fecha de fin de contrato</label>
	                <div class="input-group date">
	                  <span class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </span>
	                  @if($termino_con)
	                  	<input type="text" class="form-control pull-right" id="fincontrato" name="fincontrato" value="{{ old('fincontrato') ? old('fincontrato') : $termino_con }}">
	                  @else
	                  	<input type="text" class="form-control pull-right" id="fincontrato" name="fincontrato">
	                  @endif

	                  @if ($errors->has('fincontrato'))
						<p class="alert alert-danger">{{ $errors->first('fincontrato') }}</p>
					  @endif
	                </div>
	                <!-- /.input group -->
	              </div>	
				</div>
				<div class="col-sm-10 col-md-9">
					<label for="observacion" class="control-label">Observaciones</label>
					<br>
					<textarea name="observacion" id="observacion" rows="10" cols="60" >{{ $contratoempleado->observacion_contratoempleado}}</textarea>
				</div>
			</div>
			<div align="right">
					<div class="form-group">
						<div class="col-xs-12">
							<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" id="submit" />
							<a href="{{ URL::to('home') }}" class="btn btn-danger">Volver</a>
						</div>
					</div>
				</div>
			</div>
			</div>
			<br>
		</form>
		<div class="col-xs-12">
			<div class="box box-warning">
			<div class="box-header with-border">
              <h3 class="box-title">Carga de Documentos</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <div class="box-body">
			<div class="form-group">
			<div class="pull-left" style="color:#FF0000;">
              	<h4>*Los documentos deben ser de máximo 3MB y sólo PDF o JPG.</h4>	
              </div>
				<div class="col-sm-7 col-md-10">        
				<form method="POST" action="{{ URL::to('storage/create') }}" accept-charset="UTF-8" enctype="multipart/form-data">      
	            <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="form-group">
	              <div align="center">
			            	<table id="tabla" class="table" data-display-length='-1'>
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">NOMBRE DOCUMENTO</th>
									<th class="text-center">CARGAR ARCHIVO</th>
									<th class="text-center">FECHA INICIO</th>
									<th class="text-center">FECHA TERMINO</th>
									<th class="text-center">DESCARGAR</th>				
								</tr>
							</thead>
							<tbody>
	              			@foreach ($empleados_documentos as $key => $documento) 
	                      	<tr>
	                      		<td class="text-center">{{ ++$key }}</td>
								<td class="text-center">{{$documento->detalle_tipodocumento}}</td>
	                      		<td>	
	                      			<input type="file" class="form-control" id="{{$documento->detalle_tipodocumento}}" name="file{{ $key }}">
	                      		</td>
	                      		<td class="text-center">
	                      			<div class="col-sm-6 col-md-6">
									<!-- Date -->
										@if($documento->vigencia_tipodocumento == 'si')
	                      					<input type="text" class="form-control2" id="emision{{$key}}" name="fecha{{$key}}" value="{{ old('fecha'.$key) ? old('fecha'.$key) : $documento->inicio_empleado_tipodocumento }}">		
	                      				@else
	                      					No Aplica
	                      				@endif
	                      			<!-- /.input group -->	
									</div>
	                      		</td>
	                      		<td class="text-center">
	                      			<div class="col-sm-6 col-md-6">
									<!-- Date -->
										@if($documento->vigencia_tipodocumento == 'si')
	                      					<input type="text" class="form-control3" id="termino{{$key}}" name="termino{{$key}}" value="{{ old('termino'.$key) ? old('termino'.$key) : $documento->termino_empleado_tipodocumento }}">		
	                      				@else
	                      					No Aplica
	                      				@endif
	                      			<!-- /.input group -->	
									</div>
	                      		</td>
	                      		<td class="text-center">
	                      			@if($documento->ruta_empleado_tipodocumento)
	                      				<a href="/storage/files/prs/{{Crypt::encrypt($documento->nombre_empleado_tipodocumento)}}" class="btn btn-success">Descargar</a>
	                      			@endif
	                      		</td>

	                    	</tr>
	              			@endforeach
	              			</tbody>
							</table> 
	            	</div>
	 			</div>
		      	</div>
		    </div>
		    <div align="right">
					<div class="form-group">
						<div class="col-xs-12">
							<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" id="submit" />
							<a href="{{ URL::to('home') }}" class="btn btn-danger">Volver</a>
						</div>
					</div>
				</div>
		    </div>
	        </form>
	  </div>
	</div>  
</section>
<script>
$(document).ready(function() {
	if($("#rut").val() && $("#digito").val() && $("#nombres").val() && $("#patapel").val() && $("#nacimiento").val()){
	    $("#acred").removeClass('disabled');
	    $("#acredi").removeClass('disabled');
	    $("#acred").attr("data-toggle", "modal");
	    $("#acredi").attr("data-toggle", "modal");
    } else {
        $("#acred").addClass('disabled');
        $("#acredi").addClass('disabled');
        $("#acred").removeAttr('data-toggle');
        $("#acredi").removeAttr('data-toggle');
    }    
});
$(function () {
    $(".contratoempresa").select2({
        placeholder: "Seleccione una empresa"
    });
    $(".tiposcontratos").select2({
        placeholder: "Seleccione un tipo"
    });
    $('#nacimiento').datepicker({
    	language : 'es',
    	format : 'dd/mm/yyyy',
   	 	autoclose: true,
   	 	startDate: '-75y',
    	endDate: '-18y',
    	weekStart: 1
    });
    $('#iniciocontrato').datepicker({
    	language : 'es',
   	 	format: 'dd/mm/yyyy',
   	 	autoclose: true,
   	 	startDate: '-10y',
    	endDate: '+10y',
    	weekStart: 1
    });
    $('#fincontrato').datepicker({
    	language : 'es',
   	 	format: 'dd/mm/yyyy',
   	 	autoclose: true,
   	 	startDate: '-10y',
    	endDate: '+10y',
    	weekStart: 1	
    });
    $('.form-control2').datepicker({
    	language : 'es',
   	 	format: 'dd/mm/yy',
   	 	autoclose: true,
   	 	startDate: '-6y',
    	endDate: '+6y',
   	 	weekStart: 1	
    });
    $('.form-control3').datepicker({
    	language : 'es',
   	 	format: 'dd/mm/yy',
   	 	autoclose: true,
   	 	weekStart: 1,
   	 	startDate: '0',
    	endDate: '+6y'
    });
  });

$(document).ready(function() {
    $("#tiposcontratos").change(function() {
        if ($(this).val() == 1) {
            $("#fincontrato").prop('disabled', true);
        }
        else {
            $("#fincontrato").prop('disabled', false);
        }
    }).change();
});

$(document).ready(function() {
    $("#tiposcontratos").change(function() {
        if ($(this).val() == 1) {
            $("#fincontrato").prop('disabled', true);
        }
        else {
            $("#fincontrato").prop('disabled', false);
            $("#fincontrato").prop('required', true);
        }
    }).change();
});

$(document).ready(function(){
    $('#submit').click(function() {
    	$('#spinner').show();
    });
});
$(document).ready(function(){
    $('#acred').click(function() {
        $('#spinner').show();
    });
});
$(document).ready(function(){
    $('#submit2').click(function() {
        $('#spinner').show();
    });
});
$(document).ready(function() {
    setTimeout(function() {
        $(".spinner").fadeOut(1500);
    },10000);
});
</script>
@stack('javascript')
@stop