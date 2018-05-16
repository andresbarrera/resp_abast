@extends('layouts.app')
@section('title')
ABAST | MODIFICAR ARTICULO
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/articulos.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush
@section('content')
<section class="row">
	<h3 class="col-xs-12">Modificar Articulo</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr />
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="modificar" method="post" action="{{ URL::to('articulo/'.$articulos->id_articulo) }}" role="form" class="form-horizontal">
			<input type="hidden" name="_method" value="put" />
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="articulos" class="control-label">Articulo</label>
					<input type="text" name="articulos" value="{{ (old('articulos')) ? old('articulos') : $articulos->nombre_articulo }}" class="form-control" id="nombre" autocomplete="off" placeholder="Nombre del articulo" autofocus="true" maxlength="50" style="<?php if ($errors->has('articulos')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('tiposarticulos'))
					<p class="alert alert-danger">{{ $errors->first('articulos') }}</p>
					@endif
				</div>
				<div class="col-sm-6 col-md-9">
					<label for="descripcion" class="control-label">Descripción</label>
					<br>
					<textarea name="descripcion" id="descripcion" rows="10" cols="60" >{{$articulos->descripcion_articulo}}</textarea>
				</div>
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="Tipoarticulo" class="control-label">Tipo de artículo</label>
					<select name="tipoarticulo" class="tipoarticulo" id="tipoarticulo" style='width: 50%' >
						@foreach ($tiposarticulos as $tipoarticulo)
						<option value="{{ $tipoarticulo->id_tipoarticulo }}"{{ (old('tipoarticulo')) ? (old('tipoarticulo') == $tipoarticulo->id_tipoarticulo) ? ' selected="selected"' : '' : ($articulos->fkid_tipoarticulo == $tipoarticulo->id_tipoarticulo)  ? ' selected="selected"' : '' }}>{{ $tipoarticulo->nombre_tipoarticulo }}</option>
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
        placeholder: "Seleccione los tipos de artículos"
    });
});
</script>
@stack('javascript')
@stop