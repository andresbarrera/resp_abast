@extends('layouts.app')

@section('title')
ABAST | AGREGAR ARTICULO
@stop

@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/articulos.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush

@section('content')
<section class="row">
	<h3 class="col-xs-12">Agregar Articulo</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr/>
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="agregar" method="post" action="{{ URL::to('articulo') }}" role="form" class="form-horizontal">
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="articulos" class="control-label">Articulo</label>
					<input type="text" name="articulos" value="{{ old('articulos') }}" class="form-control" id="articulos" autocomplete="off" placeholder="Nombre del articulo" autofocus="true" maxlength="50" style="<?php if ($errors->has('articulos')) {?> border: 1px solid #F00; <?php } ?>" />

					@if ($errors->has('articulos'))
					<p class="alert alert-danger">{{ $errors->first('articulos') }}</p>
					@endif
				</div>
				<div class="col-sm-6 col-md-9">
					<label for="descripcion" class="control-label">Descripcion</label>
					<br>
					<textarea name="descripcion" id="descripcion" rows="10" cols="60" ></textarea>
				</div>
				<br>
				<div class="col-sm-7 col-md-10">
					<span class="required-asterik">*</span>
					<label for="tipoarticulo" class="control-label">Tipo de Articulo</label>
					<br>
					<select name="tipoarticulo" class="tipoarticulo" id="tipoarticulo" style="width: 25%" placeholder='Seleccione tipo de artículo'>
						@foreach ($tiposarticulos as $tipoarticulo)
						<option value="{{ $tipoarticulo->id_tipoarticulo }}"{{(old('tipoarticulo') == $tipoarticulo->id_tipoarticulo) ? ' selected="selected"' : '' }}>{{ $tipoarticulo->nombre_tipoarticulo }}</option>
						@endforeach
					</select>
					@if ($errors->has('tipoarticulo'))
					<p class="alert alert-danger">{{ $errors->first('tipoarticulo') }}</p>
					@endif	
				</div>
			</div>
			<hr />
			<div class="form-group">
				<div class="col-xs-12">
					<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" />
					<a href="{{ URL::to('articulo') }}" class="btn btn-danger">Volver</a>
				</div>
			</div>
		</form>
	</div>
</section>
<script>
$(function () {
    $(".tipoarticulo").select2({
        placeholder: "Seleccione tipo de artículo",
        allowClear: true
    });
});
</script>
@stack('javascript')
@stop