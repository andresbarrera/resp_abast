@extends('layouts.app')
@section('title')
ABAST | MODIFICAR PERFIL
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/perfiles.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush
@section('content')
<section class="row">
	<h3 class="col-xs-12">Modificar Perfil</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr />
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="modificar" method="post" action="{{ URL::to('perfil/'.$perfil->id_perfil) }}" role="form" class="form-horizontal">
			<input type="hidden" name="_method" value="put" />
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="perfil" class="control-label">Perfil</label>
					<input type="text" name="perfil" value="{{ (old('perfil')) ? old('perfil') : $perfil->nombre_perfil }}" class="form-control" id="nombre" autocomplete="off" placeholder="Nombre del perfil" autofocus="true" maxlength="50" style="<?php if ($errors->has('perfil')) {?> border: 1px solid #F00; <?php } ?>" />
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
							@foreach($submenus_perfiles as $sub)
								@if($sub->fkid_submenu == $submenu->id_submenu)
									<option value="{{ $submenu->id_submenu }}"{{ ($sub->fkid_submenu == $submenu->id_submenu) ? ' selected="selected"' : '' }}>{{ $submenu->nombre_submenu }}</option>
								@endif
							@endforeach		
						@endforeach
						@foreach ($submenus as $submenu)
								@if(old('submenu') != $submenu->id_submenu)
									<option value="{{ $submenu->id_submenu }}">{{ $submenu->nombre_submenu }}</option>
								@endif		
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