@extends('layouts.app')
@section('title')
ABAST | MODIFICAR TIPO DE OBLIGATORIEDAD
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/tiposobligatoriedades.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush
@section('content')
<section class="row">
	<h3 class="col-xs-12">Modificar Tipo de Obligatoriedad</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr />
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="modificar" method="post" action="{{ URL::to('tiposobligatoriedades/'.$tiposobligatoriedades->id_tipoobligatoriedad) }}" role="form" class="form-horizontal">
			<input type="hidden" name="_method" value="put" />
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="tiposobligatoriedades" class="control-label">tipoobligatoriedad</label>
					<input type="text" name="tiposobligatoriedades" value="{{ (old('tiposobligatoriedades')) ? old('tiposobligatoriedades') : $tiposobligatoriedades->detalle_tipoobligatoriedad}}" class="form-control" id="nombre" autocomplete="off" placeholder="Nombre del tipo de obligatoriedad" autofocus="true" maxlength="50" style="<?php if ($errors->has('tiposobligatoriedades')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('tiposobligatoriedades'))
					<p class="alert alert-danger">{{ $errors->first('tiposobligatoriedades') }}</p>
					@endif
				</div>
			</div>
			<hr />
			<div class="form-group">
				<div class="col-xs-12">
					<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" />
					<a href="{{ URL::to('tiposobligatoriedades') }}" class="btn btn-danger">Volver</a>
				</div>
			</div>
		</form>
	</div>
</section>
@stop