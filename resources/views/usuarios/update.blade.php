@extends('layouts.app')
@section('title')
ABAST | MODIFICAR USUARIOS
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/usuarios.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush
@section('content')
<section class="row">
	<h3 class="col-xs-12">Modificar Usuario</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr />
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="modificar" method="post" action="{{ URL::to('usuarios/'.$usuarios->id_usuario) }}" role="form" class="form-horizontal">
			<input type="hidden" name="_method" value="put">
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-9">
					<span class="required-asterik">*</span>
					<label for="Nombres" class="control-label">Nombres</label>
					<input type="text" name="nombres" value="{{ old('nombres') ? old('nombres') : $usuarios->nombres_usuario  }}" class="form-control" id="nombres" autocomplete="off" placeholder="Nombres" autofocus="true" maxlength="32" style="<?php if ($errors->has('nombres')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('nombres'))
					<p class="alert alert-danger">{{ $errors->first('nombres') }}</p>
					@endif
				</div>
				<div class="col-sm-6 col-md-6">
					<span class="required-asterik">*</span>
					<label for="Patapel" class="control-label">Apellido Paterno</label>
					<input type="text" name="patapel" value="{{ old('patapel') ? old('patapel') : $usuarios->patapel_usuario }}" class="form-control" id="patapel" autocomplete="off" placeholder="Apellido Paterno" autofocus="true" maxlength="32" style="<?php if ($errors->has('patapel')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('patapel'))
					<p class="alert alert-danger">{{ $errors->first('patapel') }}</p>
					@endif
				</div>
				<div class="col-sm-6 col-md-6">
					<label for="Matapel" class="control-label">Apellido Materno</label>
					<input type="text" name="matapel" value="{{ old('matapel') ? old('matapel') : $usuarios->matapel_usuario }}" class="form-control" id="matapel" autocomplete="off" placeholder="Apellido Materno" autofocus="true" maxlength="32" style="<?php if ($errors->has('matapel')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('patapel'))
					<p class="alert alert-danger">{{ $errors->first('matapel') }}</p>
					@endif
				</div>
				<div class="col-sm-6 col-md-6">
					<span class="required-asterik">*</span>
					<label for="Email" class="control-label">Email</label>
					<input type="email" name="email" value="{{ old('email') ? old('email') : $usuarios->email_usuario }}" class="form-control" id="email" autocomplete="off" placeholder="Email de usuario" autofocus="true" maxlength="32" style="<?php if ($errors->has('email')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('email'))
					<p class="alert alert-danger">{{ $errors->first('email') }}</p>
					@endif
					@if (session('message'))
					    <p class="alert alert-danger">
					        {{ session('message') }}
					    </p>
					@endif
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-6 col-md-6">
					<span class="required-asterik">*</span>
					<label for="Perfil" class="control-label">Perfil</label>
					<br>
					<select name="perfil" class="perfil" id="perfil" style="width: 75%">
						@foreach ($perfiles as $perfil)
						@if(( $perfil->id_perfil == 9 ) && (Session::get('perfil')==3) )
							<option value="{{ $perfil->id_perfil }}"{{ (old('perfil')) ? (old('perfil') == $perfil->id_perfil) ? ' selected="selected"' : '' : ($usuarios->fkid_perfil == $perfil->id_perfil)  ? ' selected="selected"' : '' }}>{{ $perfil->nombre_perfil }}</option>
						@endif
						@if(Session::get('perfil')!=3)
							<option value="{{ $perfil->id_perfil }}"{{ (old('perfil')) ? (old('perfil') == $perfil->id_perfil) ? ' selected="selected"' : '' : ($usuarios->fkid_perfil == $perfil->id_perfil)  ? ' selected="selected"' : '' }}>{{ $perfil->nombre_perfil }}</option>
						@endif	



						@endforeach
					</select>
					@if ($errors->has('perfil'))
					<p class="alert alert-danger">{{ $errors->first('perfil') }}</p>
					@endif
				</div>
				<div class="col-sm-6 col-md-6">
					<span class="required-asterik">*</span>
					<label for="Area" class="control-label">Area</label>
					<br>
					<select name="area" class="area" id="area" style="width: 75%">
						@foreach ($areas as $area)
						@if(( $area->id_area == Session::get('area') ) && (Session::get('perfil')==3) )
							<option value="{{ $area->id_area }}"{{ (old('area') == $area->id_area) ? ' selected="selected"' : '' }}>{{ $area->nombre_area }}</option>
						@endif
						@if(Session::get('perfil')!=3)
							<option value="{{ $area->id_area }}"{{ (old('area')) ? (old('area') == $area->id_area) ? ' selected="selected"' : '' : ($usuarios->fkid_area == $area->id_area)  ? ' selected="selected"' : '' }}>{{ $area->nombre_area }}</option>
						@endif
						@endforeach
					</select>
					@if ($errors->has('area'))
					<p class="alert alert-danger">{{ $errors->first('area') }}</p>
					@endif
				</div>
			</div>
			<hr />
			<div class="form-group">
				<div class="col-xs-12">
					<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" />
					<a href="{{ URL::to('usuarios') }}" class="btn btn-danger">Volver</a>
				</div>
			</div>
		</form>
	</div>
</section>
<script>
$(function () {
    $(".perfil").select2({
        placeholder: "Seleccione un perfil"
    });
    $(".area").select2({
        placeholder: "Seleccione un area"
    });
  });
</script>
@stack('javascript')
@stop