@extends('layouts.login')
@section('title')
RM WORKMATE | ABAST
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/usuarios.js') }}"></script>
@if (Session::has('mensaje'))
<script type="text/javascript">toastr.success("{{ Session::get('mensaje') }}", 'Información');</script>
@endif
@if (Session::has('alert'))
<script type="text/javascript">toastr.error("{{ Session::get('alert') }}", 'Información');</script>
@endif
@endpush
@section('content')
<div class="login-form">
	<div class="login-content">
		@if (Session::has('message'))
		<button class="btn btn-danger btn-block btn-icon icon-left error-login">
			<p class="login-error">{{ Session::get('message') }}</p>
			<i class="entypo-attention"></i>
		</button>
		@endif
		<form name="ingresar" method="post" action="login" role="form">
			{{ csrf_field() }}
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">
						<i class="entypo-user"></i>
					</div>
					<input type="text" class="form-control" name="username" id="username" placeholder="USUARIO" autocomplete="off" autofocus="autofocus" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" />
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon">
						<i class="entypo-key"></i>
					</div>
					<input type="password" class="form-control" name="password" id="password" placeholder="CONTRASEÑA" autocomplete="off" />
				</div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block btn-login">
					<i class="entypo-login"></i>
					Entrar
				</button>
			</div>
		</form>
		<div class="login-bottom-links">
			<a href="forgot-password" class="link">&iquest;Olvid&oacute; su contrase&ntilde;a?</a>
		</div>
	</div>
</div>
@stop