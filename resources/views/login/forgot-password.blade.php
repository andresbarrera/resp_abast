@extends('layouts.login')

@section('content')
<div class="login-form">
	<div class="login-content">
		<form name="forgotpassword" method="post" action="forgot-password" role="form">
			{{ csrf_field() }}
			<div class="form-group">
				<div class="input-group" style="<?php if ($errors->has('email')) {?> border: 1px solid #F00; <?php } ?>">
					<div class="input-group-addon">
						<i class="entypo-mail"></i>
					</div>
					<input type="text" class="form-control" name="email" id="email" placeholder="CORREO ELECTRÓNICO" autocomplete="off" autofocus="autofocus" />
				</div>
				@if ($errors->has('email'))
				<p class="alert alert-danger">{{ $errors->first('email') }}</p>
				@endif
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block btn-login">
					<i class="entypo-login"></i>
					Enviar
				</button>
			</div>
		</form>
	</div>
</div>
@stop