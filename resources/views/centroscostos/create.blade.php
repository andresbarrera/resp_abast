@extends('layouts.app')

@section('title')
ABAST | AGREGAR CENTRO DE COSTO
@stop

@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/centroscostos.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush

@section('content')
<section class="row">
	<h3 class="col-xs-12">Agregar Centro de Costo</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr/>
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="box box-warning">
	<div class="col-xs-12">
		<form name="agregar" method="post" action="{{ URL::to('centroscostos') }}" role="form" class="form-horizontal">
			{{ csrf_field() }}
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="centroscostos" class="control-label">Código del centro de costo:</label>
					<input type="text" name="centroscostos" value="{{ old('centroscostos') }}" class="form-control" id="centroscostos" autocomplete="off" placeholder="Código del centro de costo" autofocus="true" maxlength="50" style="<?php if ($errors->has('centroscostos')) {?> border: 1px solid #F00; <?php } ?>" />

					@if ($errors->has('centroscostos'))
					<p class="alert alert-danger">{{ $errors->first('centroscostos') }}</p>
					@endif
				</div>



				<div class="col-sm-6 col-md-4">
					<!-- Date -->
	              <div class="form-group">
	                <span class="required-asterik">*</span>
					<label for="fechainicio" class="control-label">Fecha Inicial</label>
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" id="fechainicio" name="fechainicio">
	                </div>
	                <!-- /.input group -->
	              </div>	
				</div>

				<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<!-- Date -->
	                <span class="required-asterik">*</span>
					<label for="fechafinal" class="control-label">Fecha Final</label>
	                <div class="input-group date">
	                  <div class="input-group-addon">
	                    <i class="fa fa-calendar"></i>
	                  </div>
	                  <input type="text" class="form-control pull-right" id="fechafinal" name="fechafinal">
	                </div>
	                <!-- /.input group -->
	              </div>	
				</div>

				<div class="col-sm-6 col-md-4">
						<span class="required-asterik">*</span>
						<label for="codcentro" class="control-label">Centro de Costo Padre</label>
						<br>
						<select name="codcentro" class="codcentro" id="codcentro" style="width: 100%" placeholder='Seleccione centro de costo padre'>
							@foreach ($centroscostos as $centrocosto)
							<option value="{{ $centrocosto->id_centrocosto }}"{{(old('centrocosto') == $centrocosto->id_centrocosto) ? ' selected="selected"' : '' }}>{{ $centrocosto->cod_centrocosto }}</option>
							@endforeach
						</select>	
				</div>

				<div class="col-sm-6 col-md-11">
					<label for="descripcion" class="control-label">Descripcion</label>
					<br>
					<textarea name="descripcion" id="descripcion" rows="10" cols="100" ></textarea>
				</div>
			<hr />

			<div class="form-group">
				<div class="col-xs-12">
					<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" />
					<a href="{{ URL::to('centroscostos') }}" class="btn btn-danger">Volver</a>
				</div>	
			</div>
		</form>
		</div>
	</div>
</section>
<script>
	
$(function () {
    $(".codcentro").select2({
        placeholder: "Seleccione centro de costo padre"
    });
    //Date picker
    $('#fechainicio').datepicker({
    	language : 'es',
   	 	format: 'dd/mm/yyyy',
   	 	autoclose: true	
    }).on('changeDate', function (selected) {
    var startDate = new Date(selected.date.valueOf());
    $('#fechafinal').datepicker('setStartDate', startDate);
	}).on('clearDate', function (selected) {
    $('#fechafinal').datepicker('setStartDate', null);
    });

    
    $('#fechafinal').datepicker({
    	language : 'es',
   	 	format: 'dd/mm/yyyy',
   	 	autoclose: true
    	}).on('changeDate', function (selected) {
   		var endDate = new Date(selected.date.valueOf());
   		$('#fechainicio').datepicker('setEndDate', endDate);
		}).on('clearDate', function (selected) {
   		$('#fechainicio').datepicker('setEndDate', null);
   	});
});
</script>
@stack('javascript')
@stop