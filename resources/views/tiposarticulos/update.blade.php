@extends('layouts.app')
@section('title')
ABAST | MODIFICAR FAMILIA
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/tipos.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush
@section('content')
<section class="row">
	<h3 class="col-xs-12">Modificar Familia</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr />
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="modificar" method="post" action="{{ URL::to('tiposarticulos/'.$tiposarticulos->id_tipoarticulo) }}" role="form" class="form-horizontal">
			<input type="hidden" name="_method" value="put" />
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="tiposarticulos" class="control-label">Familia</label>
					<input type="text" name="tiposarticulos" value="{{ (old('tiposarticulos')) ? old('tiposarticulos') : $tiposarticulos->nombre_tipoarticulo }}" class="form-control" id="nombre" autocomplete="off" placeholder="Nombre de la familia" autofocus="true" maxlength="50" style="<?php if ($errors->has('tiposarticulos')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('tiposarticulos'))
					<p class="alert alert-danger">{{ $errors->first('tiposarticulos') }}</p>
					@endif
				</div>
				<br>
				<div class="col-sm-7 col-md-10">
					<span class="required-asterik">*</span>
					<label for="Aprobacion" class="control-label">Aprobacion</label>
					<br>
					<select name="aprobacion" class="aprobacion" id="aprobacion" style="width: 25%" placeholder="Seleccione tipo de artículo"  >
						@foreach ($aprobaciones as $aprobacion)
						<option value="{{ $aprobacion->id_aprobacion }}"{{ (old('aprobacion')) ? (old('aprobacion') == $aprobacion->id_aprobacion) ? ' selected="selected"' : '' : ($tiposarticulos->fkid_aprobacion == $aprobacion->id_aprobacion)  ? ' selected="selected"' : '' }}>{{ $aprobacion->nombre_aprobacion }}</option>
						@endforeach
					</select>
					@if ($errors->has('aprobacion'))
					<p class="alert alert-danger">{{ $errors->first('aprobacion') }}</p>
					@endif	
				</div>
			</div>
			<hr />
			<div class="form-group">
				<div class="col-xs-12">
					<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" />
					<a href="{{ URL::to('tiposarticulos') }}" class="btn btn-danger">Volver</a>
				</div>
			</div>
		</form>
	</div>
</section>
<script>
$(function () {
    $(".aprobacion").select2({
        placeholder: "Seleccione el tipo de aprobacion"
    });
});
</script>
@stop