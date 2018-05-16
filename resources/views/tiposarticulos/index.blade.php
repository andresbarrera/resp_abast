@extends('layouts.app')
@section('title')
ABAST | FAMILIAS
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/tipos.js') }}"></script>
@endpush

@section('header')
<section class="row">
<h3 class="col-xs-12">Familias de artículos</h3>
	<p class="col-xs-12 text-right">
		<a href="{{ URL::to('tiposarticulos/create') }}" class="btn btn-primary">Agregar familia de articulos</a>
	</p>
</section>
@endsection

@section('content')
<section class="widget-box">
	<div class="widget-content nopadding">
		<table id="table" class="table table-hover table-striped table-bordered table-responsive datatable">
			<thead>
				<tr>
					<th width="50">#</th>
					<th>FAMILIA</th>
					<th>NIVEL DE APROBACION</th>
					<th class="col-xs-1">EDITAR</th>
					<th class="col-xs-1">HAB/BLOQ</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($tiposarticulos as $key => $tipoarticulo)
				<tr>
					<td class="text-center">{{ ++$key }}</td>
					<td>{{$tipoarticulo->nombre_tipoarticulo}}</td>
					<td>
						{{$tipoarticulo->nombre_aprobacion}}
					</td>
					<td class="text-center">
						<a href="{{ URL::to('tiposarticulos/'.$tipoarticulo->id_tipoarticulo.'/edit') }}" class="btn btn-gold">
							<i class="glyphicon glyphicon-edit"></i>
						</a>
					</td>

					<td class="text-center">
						<button type="button" class="{{ (is_null($tipoarticulo->deleted_at)) ? 'btn btn-danger' : 'btn btn-success' }}" data-href="{{ URL::to('tiposarticulos/'.$tipoarticulo->id_tipoarticulo) }}" data-toggle="modal" data-target = "#confirm-delete" data-whatever="{{ $tipoarticulo->nombre_tipoarticulo }}|{{ (is_null($tipoarticulo->deleted_at)) ? 'deshabilitar' : 'habilitar' }}">
						
							{{ (is_null($tipoarticulo->deleted_at)) ? 'Deshabilitar' : 'Habilitar' }}
						</button>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</section>
@endsection

@section('modal')
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"><span class="block-title"></span> Familia</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<p>&iquest;Est&aacute; seguro que desea <span class="block-secure-action"></span> la siguiente familia?</p>
						<span class="block-secure"></span>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<form name="eliminar" action="" method="post">
					<input type="hidden" name="_method" value="delete" />
					{{ csrf_field() }}
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<input name="deshabilitar" type="submit" value="Deshabilitar" class="btn btn-primary" />
				</form>
			</div>
		</div>
	</div>
</div>
@stop
