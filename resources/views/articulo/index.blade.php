@extends('layouts.app')
@section('title')
ABAST | ARTICULOS
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/articulos.js') }}"></script>
@endpush

@section('header')
<section class="row">
<h3 class="col-xs-12">Artículos</h3>
	<p class="col-xs-12 text-right">
		<a href="{{ URL::to('articulo/create') }}" class="btn btn-primary">Agregar articulos</a>
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
					<th class="text-center">ARTÍCULO</th>
					<th class="text-center">DESCRIPCIÓN</th>
					<th class="text-center">ESTADO</th>
					<th class="text-center">TIPO DE ARTICULO</th>
					<th class="text-center">EDITAR</th>
					<th class="text-center">HAB/BLOQ</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($articulos as $key => $articulo)
				<tr>
					<td class="text-center">{{ ++$key }}</td>
					<td class="text-center">{{$articulo->nombre_articulo}}</td>
					<td> 
							<pre><span class="item">{{$articulo->descripcion_articulo }}</span></pre>
							
					</td>
					<td class="text-center">{{$articulo->estado_articulo}}</td>
					<td class="text-center">
						{{$articulo->nombre_tipoarticulo}}
					</td>
					<td class="text-center">
						<a href="{{ URL::to('articulo/'.$articulo->id_articulo.'/edit') }}" class="btn btn-gold">
							<i class="glyphicon glyphicon-edit"></i>
						</a>
					</td>

					<td class="text-center">
						<button type="button" class="{{ (is_null($articulo->deleted_at)) ? 'btn btn-danger' : 'btn btn-success' }}" data-href="{{ URL::to('articulo/'.$articulo->id_articulo) }}" data-toggle="modal" data-target = "#confirm-delete" data-whatever="{{ $articulo->nombre_articulo }}|{{ (is_null($articulo->deleted_at)) ? 'deshabilitar' : 'habilitar' }}">
						
							{{ (is_null($articulo->deleted_at)) ? 'Deshabilitar' : 'Habilitar' }}
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
				<h4 class="modal-title"><span class="block-title"></span> Articulo</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<p>&iquest;Est&aacute; seguro que desea <span class="block-secure-action"></span> el siguiente articulo?</p>
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
