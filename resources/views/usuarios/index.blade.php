@extends('layouts.app')
@section('title')
ABAST | USUARIOS
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/usuarios.js') }}"></script>
@endpush

@section('header')
<section class="row">
<h3 class="col-xs-12">Usuarios registrados</h3>
	<p class="col-xs-12 text-right">
		<a href="{{ URL::to('usuarios/create') }}" class="btn btn-primary">Agregar usuarios</a>
	</p>
</section>
@endsection

@section('content')
<section class="widget-box">
		@if (Session::get('perfil')==3)
		<?php $contador=1; ?>
		<div class="widget-content nopadding">
			<table id="table" class="table table-hover table-striped table-bordered table-responsive datatable">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">USUARIO</th>
						<th class="text-center">NOMBRES</th>
						<th class="text-center">APELLIDO PATERNO</th>
						<th class="text-center">EMAIL</th>
						<th class="text-center">ESTADO REGISTRO</th>
						<th class="text-center">PERFIL</th>
						<th class="text-center">AREA</th>
						<th class="text-center">EDITAR</th>
						<th class="text-center">ACTUALIZAR DATOS</th>
						<!-- 
						<th class="text-center">VAL/BLOQ</th>
						-->
						<th class="text-center">HAB/DESH</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($usuarios as $key => $usuario)
					@if((Session::get('area'))==$usuario->id_area)

					<tr>
						<td class="text-center">{{ $contador }} </td>
						<?php $contador++; ?>
						<td class="text-center">{{$usuario->user_usuario}}</td>
						<td class="text-center">{{$usuario->nombres_usuario}}</td>
						<td class="text-center">{{$usuario->patapel_usuario}}</td>
						<td class="text-center">
							{{$usuario->email_usuario}}
						</td>
						<td class="text-center">
							{{$usuario->estadoregistro_usuario}}
						</td>
						<td class="text-center">{{$usuario->nombre_perfil}} </td>
						<td class="text-center">{{$usuario->nombre_area}} </td>
						<td class="text-center">
							<a href="{{ URL::to('usuarios/'.$usuario->id_usuario.'/edit') }}" class="btn btn-gold">
								<i class="glyphicon glyphicon-edit"></i>
							</a>
						</td>
						<td class="text-center">
							<a href="{{ URL::to('personas/'.$usuario->fkid_persona.'/edit') }}" class="btn btn-gold">
								<i class="glyphicon glyphicon-user"></i>
							</a>
						</td>
						<!--
						<td class="text-center">
							<button type="button" class="{{ ($usuario->estadoregistro_usuario=='Verificado') ? 'btn btn-danger' : 'btn btn-success' }}" data-href="{{ URL::to('usuarios/block/'.$usuario->id_usuario) }}" data-toggle="modal" data-target = "#confirm-block" data-whatever="{{ $usuario->nombres_usuario }}|{{ ($usuario->estadoregistro_usuario=='Verificado') ? 'bloquear' : 'validar' }}">
							
								{{ ($usuario->estadoregistro_usuario=='Verificado') ? 'Bloquear' : 'Validar' }}
							</button>
						</td>
						-->
						<td class="text-center">
							<button type="button" class="{{ (is_null($usuario->deleted_at)) ? 'btn btn-danger' : 'btn btn-success' }}" data-href="{{ URL::to('usuarios/'.$usuario->id_usuario) }}" data-toggle="modal" data-target = "#confirm-delete" data-whatever="{{ $usuario->nombres_usuario }}|{{ (is_null($usuario->deleted_at)) ? 'deshabilitar' : 'habilitar' }}">
							
								{{ (is_null($usuario->deleted_at)) ? 'Deshabilitar' : 'Habilitar' }}
							</button>
						</td>
					</tr>
					@endif
					@endforeach
				</tbody>
			</table>
		</div>
		@else
		<div class="widget-content nopadding">
			<table id="table" class="table table-hover table-striped table-bordered table-responsive datatable">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">USUARIO</th>
						<th class="text-center">NOMBRES</th>
						<th class="text-center">APELLIDO PATERNO</th>
						<th class="text-center">EMAIL</th>
						<th class="text-center">ESTADO REGISTRO</th>
						<th class="text-center">PERFIL</th>
						<th class="text-center">AREA</th>
						<th class="text-center">EDITAR</th>
						<th class="text-center">ACTUALIZAR DATOS</th>
						<!--
						<th class="text-center">VAL/BLOQ</th>
						-->
						<th class="text-center">HAB/DESH</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($usuarios as $key => $usuario)
					
					<tr>
						<td class="text-center">{{ ++$key }}</td>
						<td class="text-center">{{$usuario->user_usuario}}</td>
						<td class="text-center">{{$usuario->nombres_usuario}}</td>
						<td class="text-center">{{$usuario->patapel_usuario}}</td>
						<td class="text-center">
							{{$usuario->email_usuario}}
						</td>
						<td class="text-center">
							{{$usuario->estadoregistro_usuario}}
						</td>
						<td class="text-center">{{$usuario->nombre_perfil}} </td>
						<td class="text-center">{{$usuario->nombre_area}} </td>
						<td class="text-center">
							<a href="{{ URL::to('usuarios/'.$usuario->id_usuario.'/edit') }}" class="btn btn-gold">
								<i class="glyphicon glyphicon-edit"></i>
							</a>
						</td>
						<td class="text-center">
							<a href="{{ URL::to('personas/'.$usuario->fkid_persona.'/edit') }}" class="btn btn-gold">
								<i class="glyphicon glyphicon-user"></i>
							</a>
						</td>
						<!--
						<td class="text-center">
							<button type="button" class="{{ ($usuario->estadoregistro_usuario=='Verificado') ? 'btn btn-danger' : 'btn btn-success' }}" data-href="{{ URL::to('usuarios/block/'.$usuario->id_usuario) }}" data-toggle="modal" data-target = "#confirm-block" data-whatever="{{ $usuario->nombres_usuario }}|{{ ($usuario->estadoregistro_usuario=='Verificado') ? 'bloquear' : 'validar' }}">
							
								{{ ($usuario->estadoregistro_usuario=='Verificado') ? 'Bloquear' : 'Validar' }}
							</button>
						</td>
						-->
						<td class="text-center">
							<button type="button" class="{{ (is_null($usuario->deleted_at)) ? 'btn btn-danger' : 'btn btn-success' }}" data-href="{{ URL::to('usuarios/'.$usuario->id_usuario) }}" data-toggle="modal" data-target = "#confirm-delete" data-whatever="{{ $usuario->user_usuario }}|{{ (is_null($usuario->deleted_at)) ? 'deshabilitar' : 'habilitar' }}">
							
								{{ (is_null($usuario->deleted_at)) ? 'Deshabilitar' : 'Habilitar' }}
							</button>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@endif
	<script type="text/javascript">
	$(function () {
	    $(".perfil").select2({
	        placeholder: "Seleccione un perfil",
	        allowClear: true
	    });
	    $(".area").select2({
	        placeholder: "Seleccione un area",
	        allowClear: true
	    });
	  });
	</script>
	@stack('javascript')
</section>
@stop

@section('modal')
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><span class="delete-title"></span> Usuario</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<p>&iquest;Est&aacute; seguro que desea <span class="delete-secure-action"></span> el siguiente usuario?</p>
						<span class="delete-secure"></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form name="eliminar" action="" method="post">
					<input type="hidden" name="_method" value="delete" />
					{{ csrf_field() }}
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<input name="deshabilitar" type="submit" value="Aceptar" class="btn btn-primary" />
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="confirm-block" tabindex="-1" role="dialog" aria-labelledby="confirmBlockLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><span class="block-title"></span> Usuario</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<p>&iquest;Est&aacute; seguro que desea <span class="block-secure-action"></span> el siguiente usuario?</p>
						<span class="block-secure"></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form name="bloquear" action="" method="post" class="form">
					{{ csrf_field() }}
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<input type="submit" value="Aceptar" class="btn btn-primary" />
				</form>
			</div>
		</div>
	</div>
</div>
@stop
