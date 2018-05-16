@extends('layouts.app')
@section('title')
ABAST | CENTROS DE COSTOS
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/centroscostos.js') }}"></script>
@endpush

@section('header')
<section class="row">
<h3 class="col-xs-12">Centros de Costos</h3>
	<p class="col-xs-12 text-right">
		<a href="{{ URL::to('centroscostos/create') }}" class="btn btn-primary">Agregar centros de costos</a>
	</p>
</section>
@endsection

@section('content')
<section class="widget-box">
	<div class="widget-content nopadding">
		<table id="table" class="table table-hover table-striped table-bordered table-responsive datatable">
			<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">CÓDIGO</th>
					<th class="text-center">DESCRIPCIÓN</th>
					<th class="text-center">FECHA INICIO</th>
					<th class="text-center">FECHA TERMINO</th>
					<th class="text-center">CENTRO DE COSTO PADRE</th>
					<th class="text-center">EDITAR</th>
					<th class="text-center">HAB/BLOQ</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($centroscostos as $key => $centrocosto)
				<tr>
					<td class="text-center">{{ ++$key }}</td>
					<td class="text-center">{{$centrocosto->cod_centrocosto}}</td>
					<td> 
							<pre><span class="item">{{$centrocosto->descripcion_centrocosto }}</span></pre>
							
					</td>
					<td class="text-center">{{$centrocosto->fechainicio_centrocosto}}</td>
					<td class="text-center">{{$centrocosto->fechafinal_centrocosto}}</td>
					<td class="text-center">
					@if($centrocosto->fkid_centrocosto)
						@foreach ($centros as $key => $centro)
							@if($centrocosto->fkid_centrocosto == $centro->id_centrocosto)
								{{$centro->cod_centrocosto}}
							@endif
						@endforeach
					@else
						NO TIENE
					@endif
					</td>
					<td class="text-center">
						<a href="{{ URL::to('centroscostos/'.$centrocosto->id_centrocosto.'/edit') }}" class="btn btn-gold">
							<i class="glyphicon glyphicon-edit"></i>
						</a>
					</td>

					<td class="text-center">
						<button type="button" class="{{ (is_null($centrocosto->deleted_at)) ? 'btn btn-danger' : 'btn btn-success' }}" data-href="{{ URL::to('centroscostos/'.$centrocosto->id_centrocosto) }}" data-toggle="modal" data-target = "#confirm-delete" data-whatever="{{ $centrocosto->cod_centrocosto }}|{{ (is_null($centrocosto->deleted_at)) ? 'deshabilitar' : 'habilitar' }}">
						
							{{ (is_null($centrocosto->deleted_at)) ? 'Deshabilitar' : 'Habilitar' }}
						</button>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
	<script type="text/javascript">
	$(document).ready(function() {
	  // Configure/customize these variables.
	  var showChar = 25; // How many characters are shown by default
	  var ellipsestext = "...";
	  var moretext = "ver más";
	  var lesstext = "ver menos";

	  $(".item").each(function() {
	    var content = $(this).html();

	    if (content.length > showChar) {
	      var c = content.substr(0, showChar);
	      var h = content.substr(showChar);

	      var html =
	        c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span style="display:none;">' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink" style="color:green">' + moretext + "</a></span>";

	      $(this).html(html);
	    }
	  });

	  $(".morelink").click(function() {
	    if ($(this).hasClass("less")) {
	      $(this).removeClass("less");
	      $(this).html(moretext);
	    } else {
	      $(this).addClass("less");
	      $(this).html(lesstext);
	    }
	    $(this).parent().prev().toggle();
	    $(this).prev().toggle();
	    return false;
	  });
	});
	</script>
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
				<h4 class="modal-title"><span class="block-title"></span> centrocosto</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<p>&iquest;Est&aacute; seguro que desea <span class="block-secure-action"></span> el siguiente centro de costo?</p>
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

