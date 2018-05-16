@extends('layouts.login')

@section('content')
<div class="login-form">
	<div class="login-content">
		<form name="veryfy" method="post" action="verify-password" role="form">
			{{ csrf_field() }}
			<div class="form-group alert alert-success alert-success-password">
				<p>Para verificar su cuenta y acceder al sistema, debe cambiar su contrase&ntilde;a siguiendo las siguientes instrucciones:</p>
				<p>
					&bull; M&iacute;nimo 6 caracteres
					<br />
					&bull; Debe reingresar la misma contrase&ntilde;a.
				</p>
			</div>
			<div class="form-group">
				<div class="input-group" style="<?php if ($errors->has('password')) {?> border: 1px solid #F00; <?php } ?>">
					<div class="input-group-addon">
						<i class="entypo-key"></i>
					</div>
					<input type="password" class="form-control" name="password" id="password" value="{{ old('password') }}" placeholder="NUEVA CONTRASEÑA" autocomplete="off" autofocus="autofocus" />
				</div>
				@if ($errors->has('password'))
				<p class="alert alert-danger">{{ $errors->first('password') }}</p>
				@endif
			</div>
			<div class="form-group">
				<div class="input-group" style="<?php if ($errors->has('repassword')) {?> border: 1px solid #F00; <?php } ?>">
					<div class="input-group-addon">
						<i class="entypo-key"></i>
					</div>
					<input type="password" class="form-control" name="repassword" id="repassword" placeholder="REINGRESE NUEVA CONTRASEÑA" autocomplete="off" />
				</div>
				@if ($errors->has('repassword'))
				<p class="alert alert-danger">{{ $errors->first('repassword') }}</p>
				@endif
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block btn-login">
					<i class="entypo-login"></i>
					Cambiar
				</button>
			</div>
		</form>
	</div>
</div>
@stop