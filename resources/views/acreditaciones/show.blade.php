@extends('layouts.app')
@section('title')
ABAST | ACREDITACIONES
@stop
@push('javascript')
<link rel="stylesheet" href={{asset('css/archivos-tabla.css')}} >
@endpush
@section('header')
<section class="row">
<h3 class="col-xs-12">Acreditaciones</h3>
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
	.spinner {
		background-color: white;
		border: 1px solid black;
		position: fixed;
		margin: 1px;
	    top: 40%;
	    left: 40%;
	    margin-left: -50px; /* half width of the spinner gif */
	    margin-top: -50px; /* half height of the spinner gif */
	    text-align:center;
	    z-index:1234;
	    overflow: visible;
	    width: 400px; /* width of the spinner gif */
	    height: 102px; /*hight of the spinner gif +2px to fix IE8 issue */
	}

</style>
<div id="spinner" class="spinner" style="display:none;">
    <div align="center">
    	CARGANDO...
    </div>
    <br>
    <img id="img-spinner" src="{{ asset('245.gif') }}" alt="Cargando..."/>
    <br>
</div>
@endsection

@section('content')
<section class="row">
	<h3 class="col-xs-12">Datos de la Acreditación</h3>
</section>
<section class="row">
	<div class="col-xs-12">

		<div class="form-horizontal">
			<input type="hidden" name="_method" value="put">
			{{ csrf_field() }}
			<div class="box box-warning">			
			<h4 class="col-xs-12"> Datos Personales </h4>
			<div class="form-group">

				<div class="col-sm-2 col-md-3">
					<label for="Rut" class="control-label">Rut</label>
					<input type="text" name="rut" value="{{ old('rut') ? old('rut') : $personas->rutd_persona }}" class="form-control" id="rut" autocomplete="off" placeholder="Rut" autofocus="true" maxlength="9" style="<?php if ($errors->has('rut')) {?> border: 1px solid #F00; <?php } ?>" readonly/>
					@if ($errors->has('rut'))
					<p class="alert alert-danger">{{ $errors->first('rut') }}</p>
					@endif
				</div>
				<div class="col-xs-12 col-md-1">
					<label for="Digito" class="control-label">DV</label>
					<input type="text" name="digito" value="{{ old('digito') ? old('digito') : $personas->verifd_persona }}" class="form-control" id="digito" autocomplete="off" placeholder="DV" autofocus="true" maxlength="1" style="<?php if ($errors->has('digito')) {?> border: 1px solid #F00; <?php } ?>" size="1" readonly/>
					@if ($errors->has('digito'))
					<p class="alert alert-danger">{{ $errors->first('digito') }}</p>
					@endif
				</div>

				<div class="col-sm-5 col-md-5">
					<span class="required-asterik">*</span>
					<label for="Nombres" class="control-label">Nombres</label>
					<input type="text" name="nombres" value="{{ old('nombres') ? old('nombres') : $personas->nombres_persona  }}" class="form-control" id="nombres" autocomplete="off" placeholder="Nombres" autofocus="true" maxlength="32" style="<?php if ($errors->has('nombres')) {?> border: 1px solid #F00; <?php } ?>" readonly/>
					@if ($errors->has('nombres'))
					<p class="alert alert-danger">{{ $errors->first('nombres') }}</p>
					@endif
				</div>
				<div class="col-sm-3 col-md-3">
					<span class="required-asterik">*</span>
					<label for="Patapel" class="control-label">Apellido Paterno</label>
					<input type="text" name="patapel" value="{{ old('patapel') ? old('patapel') : $personas->paterno_persona }}" class="form-control" id="patapel" autocomplete="off" placeholder="Apellido Paterno" autofocus="true" maxlength="32" style="<?php if ($errors->has('patapel')) {?> border: 1px solid #F00; <?php } ?>" readonly/>
					@if ($errors->has('patapel'))
					<p class="alert alert-danger">{{ $errors->first('patapel') }}</p>
					@endif
				</div>
				<div class="col-sm-3 col-md-3">
					<label for="Matapel" class="control-label">Apellido Materno</label>
					<input type="text" name="matapel" value="{{ old('matapel') ? old('matapel') : $personas->materno_persona }}" class="form-control" id="matapel" autocomplete="off" placeholder="Apellido Materno" autofocus="true" maxlength="32" style="<?php if ($errors->has('matapel')) {?> border: 1px solid #F00; <?php } ?>" readonly/>
					@if ($errors->has('patapel'))
					<p class="alert alert-danger">{{ $errors->first('matapel') }}</p>
					@endif
				</div>
				<div class="col-sm-5 col-md-5">
					<!-- Date -->
					<label for="nacimiento" class="control-label">Fecha de nacimiento</label>
	                  <input type="text" class="form-control" id="nacimiento" name="nacimiento" value="{{ old('nacimiento') ? old('nacimiento') : $fin }}" disabled>
	                <!-- /.input group -->	
				</div>

			</div>
			</div>
			<div class="box box-warning">
			<h4 class="col-xs-12"> Datos de Contrato </h4>
			<div class="form-group">
				<div class="col-sm-7 col-md-4">
					<label for="tiposcontratos" class="control-label">Tipo de Contrato</label>
					<br>
					<select name="tiposcontratos" class="tiposcontratos" id="tiposcontratos" width="150"  style="width: 300px" disabled>
						@foreach ($tiposcontratos as $tipocontrato)
						<option value="{{ $tipocontrato->id_tipocontratoempleado }}"{{ (old('tipocontrato') == $tipocontrato->id_tipocontratoempleado) ? ' selected="selected"' : '' }}>{{ $tipocontrato->detalle_tipocontratoempleado }}</option>
						@endforeach
					</select>
					@if ($errors->has('tipocontrato'))
					<p class="alert alert-danger">{{ $errors->first('tipocontrato') }}</p>
					@endif	
				</div>
				<div class="col-sm-7 col-md-8">
					<label for="contratoempresa" class="control-label">Empresa</label>
					<br>
					<select name="contratoempresa" class="contratoempresa" id="contratoempresa" width="150"  style="width: 300px" disabled>
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
	                  <input type="text" class="form-control pull-right" id="iniciocontrato" name="iniciocontrato" disabled>
	                </div>
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
	                  <input type="text" class="form-control pull-right" id="fincontrato" name="fincontrato" disabled>
	                </div>
	                <!-- /.input group -->
	              </div>	
				</div>
				
			</div>
			</div>
			<br>
		</div>
		<div class="col-xs-12">
			<div class="box box-warning">
			<div class="box-header with-border">
              <h3 class="box-title">Documentos de la acreditación</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <div class="box-body">
			<div class="form-group">
			
				<div class="col-sm-7 col-md-10">        
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="form-group">
	            	<div align="center">
	            	<table id="tabla" class="table" data-display-length='-1'>
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th class="text-center">NOMBRE DOCUMENTO</th>
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
	                      		<td class="text-center">
	                      				@if($documento->vigencia_tipodocumento == 'si')
	                      					{{$documento->inicio_empleado_tipodocumento}}
	                      				@else
	                      					No Aplica
	                      				@endif

	                      		</td>
	                      		<td class="text-center">
	                      				@if($documento->vigencia_tipodocumento == 'si')
	                      					{{$documento->termino_empleado_tipodocumento}}
	                      				@else
	                      					No Aplica
	                      				@endif
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
	 			<br>
	 			</div>
		      </div>
		      </div>
		    </div>

		    <div align="right">
				<div class="form-group">
					<div class="col-xs-12">
						<a href="{{ URL::to('/acreditacion') }}" class="btn btn-danger">Volver</a>
					</div>
				</div>
			</div>
			<hr>

		    <div class="col-xs-12">
		    	<div class="box box-warning">
					<div class="box-header with-border">
		              <h3 class="box-title">Observaciones de la acreditación</h3>
		              <div class="box-tools pull-right">
		                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		                </button>
		              </div>
		              <!-- /.box-tools -->
		            </div>
	            	<div class="box-body">
					<form method="post" action="/observaciones/{{$id_contrato->id_contratoempleado}}" role="form" class="form-horizontal">
					{{ csrf_field() }}
						<center>
						<div class="form-group">
							<div class="col-xs-12">        
								<div class="col-sm-6 col-md-9">
									<label for="observacion" class="control-label">Observacion</label>
									<br>
									<textarea name="observacion" id="observacion" rows="10" cols="60" ></textarea>
								</div>	
					        </div>
						</div>
						</center>
					</div>
				</div>
				<div align="right">
				<div class="form-group">
						<div class="col-xs-12">
							<input type="submit" value="Guardar" id="submit" class="btn btn-primary" />
							<a href="{{ URL::to('/acreditacion') }}" class="btn btn-danger">Volver</a>
						</div>
					</div>
				</div>
				</form>
			</div>
</section>
<script>
$(function () {
    $(".contratoempresa").select2({
        placeholder: "Seleccione una empresa"
    });
    $(".tiposcontratos").select2({
        placeholder: "Seleccione un tipo"
    });
    $('#nacimiento').datepicker({
    	language : 'es',
   	 	format: 'dd/mm/yyyy',
   	 	autoclose: true,
   	 	weekStart: 1	
    });
    $('#iniciocontrato').datepicker({
    	language : 'es',
   	 	format: 'dd/mm/yyyy',
   	 	autoclose: true,
   	 	weekStart: 1	
    });
    $('#fincontrato').datepicker({
    	language : 'es',
   	 	format: 'dd/mm/yyyy',
   	 	autoclose: true
   	 	weekStart: 1	
    });
  });
$(document).ready(function(){
    $('#submit').click(function() {
        $('#spinner').show();
    });
});
</script>
@stack('javascript')
@stop

