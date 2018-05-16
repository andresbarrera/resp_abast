@extends('layouts.app')

@section('title')
ABAST | AGREGAR TIPO DE LICENCIA
@stop

@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/tiposlicencias.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush

@section('content')
<section class="row">
	<h3 class="col-xs-12">Agregar Tipo de Licencia</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr/>
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="agregar" method="post" action="{{ URL::to('tiposlicencias') }}" role="form" class="form-horizontal">
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="tiposlicencias" class="control-label">Tipo de Licencia</label>
					<input type="text" name="tiposlicencias" value="{{ old('tiposlicencias') }}" class="form-control" id="tiposlicencias" autocomplete="off" placeholder="Detalle del tipo de licencia" autofocus="true" maxlength="50" style="<?php if ($errors->has('tiposlicencias')) {?> border: 1px solid #F00; <?php } ?>" />

					@if ($errors->has('tiposlicencias'))
					<p class="alert alert-danger">{{ $errors->first('tiposlicencias') }}</p>
					@endif
				</div>
			</div>
			<hr />
			<div class="form-group">
				<div class="col-xs-12">
					<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" />
					<a href="{{ URL::to('tiposlicencias') }}" class="btn btn-danger">Volver</a>
				</div>
			</div>
		</form>
	</div>
</section>
@stop