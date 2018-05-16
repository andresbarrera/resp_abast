@extends('layouts.app')

@section('title')
ABAST | AGREGAR PERFIL
@stop

@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/perfiles.js') }}"></script>
@endpush

@section('content')
<section class="row">
	<h3 class="col-xs-12">Agregar Perfil</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr/>
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="agregar" method="post" action="{{ URL::to('perfil') }}" role="form" class="form-horizontal">
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="perfil" class="control-label">Perfil</label>
					<input type="text" name="perfil" value="{{ old('perfil') }}" class="form-control" id="perfil" autocomplete="off" placeholder="Nombre del perfil" autofocus="true" maxlength="50" style="<?php if ($errors->has('perfil')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('perfil'))
					<p class="alert alert-danger">{{ $errors->first('perfil') }}</p>
					@endif
				</div>
				<div class="col-sm-7 col-md-10">
					<span class="required-asterik">*</span>
					<label for="submenu" class="control-label">Submenus</label>
					<br>
					<select name="submenu[]" class="submenu" id="submenu" multiple="multiple" >
						@foreach ($submenus as $submenu)
						<option value="{{ $submenu->id_submenu }}"{{ (old('submenu') == $submenu->id_submenu) ? ' selected="selected"' : '' }}>{{ $submenu->nombre_submenu }}</option>
						@endforeach
					</select>
					@if ($errors->has('submenu'))
					<p class="alert alert-danger">{{ $errors->first('submenu') }}</p>
					@endif	
				</div>
			</div>			
			<hr />
			<div class="form-group">
				<div class="col-xs-12">
					<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" />
					<a href="{{ URL::to('perfil') }}" class="btn btn-danger">Volver</a>
				</div>
			</div>
		</form>
	</div>
</section>
<script>
$(function () {
    $(".submenu").select2({
        placeholder: "Seleccione los submenus",
        allowClear: true
    });
});
</script>
@stack('javascript')
@stop