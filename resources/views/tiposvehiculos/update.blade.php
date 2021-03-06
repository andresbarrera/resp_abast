@extends('layouts.app')
@section('title')
ABAST | MODIFICAR TIPO DE VEHICULO
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/tiposvehiculos.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush
@section('content')
<section class="row">
	<h3 class="col-xs-12">Modificar Tipo de vehiculo</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr />
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="modificar" method="post" action="{{ URL::to('tiposvehiculos/'.$tiposvehiculos->id_tipovehiculo) }}" role="form" class="form-horizontal">
			<input type="hidden" name="_method" value="put" />
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="tiposvehiculos" class="control-label">Tipo de Vehiculo</label>
					<input type="text" name="tiposvehiculos" value="{{ (old('tiposvehiculos')) ? old('tiposvehiculos') : $tiposvehiculos->detalle_tipovehiculo }}" class="form-control" id="detalle" autocomplete="off" placeholder="Detalle del tipo de vehiculo" autofocus="true" maxlength="50" style="<?php if ($errors->has('tiposvehiculos')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('tiposvehiculos'))
					<p class="alert alert-danger">{{ $errors->first('tiposvehiculos') }}</p>
					@endif
				</div>
			</div>
			<hr />
			<div class="form-group">
				<div class="col-xs-12">
					<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" />
					<a href="{{ URL::to('tiposvehiculos') }}" class="btn btn-danger">Volver</a>
				</div>
			</div>
		</form>
	</div>
</section>
@stop