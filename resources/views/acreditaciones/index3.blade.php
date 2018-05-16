@extends('layouts.app')
@section('title')
ABAST | ACREDITACIONES
@stop

@push('javascript')
<link rel="stylesheet" href={{asset('css/spinner.css')}} >
<link rel="stylesheet" href={{asset('css/index-acred.css')}} >
@if (Session::has('mensaje'))
<script type="text/javascript">toastr.success("{{ Session::get('mensaje') }}", 'Información');</script>
@endif
@if (Session::has('alert'))
<script type="text/javascript">toastr.error("{{ Session::get('alert') }}", 'Información');</script>
@endif
@endpush

@section('header')
<h3 class="col-xs-12">Acreditaciones</h3>
	<ol class="breadcrumb">
        <li><a href="home"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Ver Acreditaciones</li>
      </ol>
<div id="spinner" class="spinner" style="display:none;">
	    <div align="center">
	    	CARGANDO...
	    </div>
	    <br>
	    <img id="img-spinner" src="{{ asset('245.gif') }}" alt="Cargando..."/>
	    <br>
	</div> 
@endsection

@section('content')
<section class="row">
	<div class="col-xs-12">
		<div align="right">
			<a href="{{ URL::to('/acreditacion/') }}" class="btn btn-primary">Pendientes</a>
			<a href="{{ URL::to('/acreditacion/aprobadas')}}" class="btn btn-primary">Aprobadas</a>
			<a href="{{ URL::to('/acreditacion/rechazadas')}}" class="btn btn-primary">Rechazadas</a>
		</div>
	</div>
</section>
<section class="widget-box">
	<div class="widget-content nopadding">
		<table id="table" class="table table-hover table-striped table-bordered table-responsive datatable">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">NOMBRES</th>
					<th class="text-center">APELLIDO</th>
					<th class="text-center">FECHA NACIMIENTO</th>
					<th class="text-center">AREA</th>
					<th class="text-center">VER DATOS</th>
					<th class="text-center">COMENTARIOS</th>
					<th class="text-center">ESTADO</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($acreditaciones as $key => $acreditacion)
				<tr>
					<td class="text-center">{{ ++$key }}</td>
					<td class="text-center">{{$acreditacion->nombres_persona}}</td>
					<td class="text-center">{{$acreditacion->paterno_persona}}</td>
					<td class="text-center">{{$acreditacion->fnacimiento_persona}}</td>
					<td class="text-center">
						{{$acreditacion->nombre_area}}
					</td>
					<td class="text-center">
							<a href="{{ URL::to('/acreditacion/show/'.$acreditacion->fkid_persona) }}" class="btn btn-warning">
							<i class="glyphicon glyphicon-eye-open"></i>
							</a>
					</td>
					<td class="text-center">
							<a href="{{ URL::to('/acreditacion/'.$acreditacion->id_solicitudacreditacion.'/observaciones') }}" class="btn btn-primary">
							<i class="glyphicon glyphicon-eye-open"></i>
							</a>
					</td>
					<td class="text-center">
						@if($acreditacion->detalle_estadosolicitudacreditacion == "APROBADA")
							<a href="{{ URL::to('/rechazar/'.$acreditacion->id_solicitudacreditacion) }}" class="btn btn-danger" id="rech">
							<i class="glyphicon glyphicon-remove"></i> RECHAZAR
							</a>
						@endif
						@if($acreditacion->detalle_estadosolicitudacreditacion == "RECHAZADA")
							<a href="{{ URL::to('/acreditar/'.$acreditacion->id_solicitudacreditacion) }}" class="btn btn-success" id="aprob">
							<i class="glyphicon glyphicon-ok"></i> APROBAR
							</a>
						@endif
						@if($acreditacion->detalle_estadosolicitudacreditacion != "RECHAZADA" && $acreditacion->detalle_estadosolicitudacreditacion != "APROBADA" )
							<a href="{{ URL::to('/rechazar/'.$acreditacion->id_solicitudacreditacion) }}" class="btn btn-danger" id="rech">
							<i class="glyphicon glyphicon-remove"></i> RECHAZAR
							</a>
							<a href="{{ URL::to('/acreditar/'.$acreditacion->id_solicitudacreditacion) }}" class="btn btn-success" id="aprob">
							<i class="glyphicon glyphicon-ok"></i> APROBAR
							</a>
						@endif
					</td>
					
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<script>
		$(document).ready(function(){
		    $('#aprob').click(function() {
		        $('#spinner').show();
		    });
		});
		$(document).ready(function(){
		    $('#rech').click(function() {
		        $('#spinner').show();
		    });
		});
	</script>
@endsection

@section('modal')
<div class="modal fade" id="confirm-aprueba" tabindex="-1" role="dialog" aria-labelledby="confirmApruebaLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><span class="block-title"></span> Acreditación</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<p>&iquest;Est&aacute; seguro que desea aprobar la siguiente acreditación?</p>
						<span class="block-secure"></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form name="acreditar" action="" method="post">
					<input type="hidden" name="_method" value="delete" />
					{{ csrf_field() }}
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<input name="acreditar" type="submit" value="Acreditar" class="btn btn-primary" />
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="confirm-rechaza" tabindex="-1" role="dialog" aria-labelledby="confirmRechazaLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><span class="block-title"></span> Acreditación</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<p>&iquest;Est&aacute; seguro que desea rechazar la siguiente acreditación?</p>
						<span class="block-secure"></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form name="rechazar" action="" method="post">
					<input type="hidden" name="_method" value="delete" />
					{{ csrf_field() }}
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<input name="rechazar" type="submit" value="Rechazar" class="btn btn-primary" />
				</form>
			</div>
		</div>
	</div>
</div>
@stop
	
