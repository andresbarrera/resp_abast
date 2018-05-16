@extends('layouts.app')
@section('title')
ABAST | TIPOS DE DOCUMENTOS
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/tiposdocumentos.js') }}"></script>
@endpush

@section('header')
<section class="row">
<h3 class="col-xs-12">Tipos de documentos</h3>
	<p class="col-xs-12 text-right">
		<a href="{{ URL::to('tiposdocumentos/create') }}" class="btn btn-primary">Agregar tipo de documento</a>
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
					<th class="text-center">NOMBRE</th>
					<th class="text-center">DESCRIPCIÓN</th>
					<th class="text-center">VIGENCIA?</th>
					<th class="text-center">DURACION(AÑOS)</th>
					<th class="text-center">OBLIGATORIO?</th>
					<th class="text-center">EDITAR</th>
					<th class="text-center">HAB/BLOQ</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($tiposdocumentos as $key => $tipodocumento)
				<tr>
					<td class="text-center">{{ ++$key }}</td>
					<td class="text-center">{{$tipodocumento->detalle_tipodocumento}}</td>
					<td> 
							<pre><span class="item">{{$tipodocumento->descripcion_tipodocumento }}</span></pre>
							
					</td>
					<td class="text-center">{{$tipodocumento->vigencia_tipodocumento}}</td>
					<td class="text-center">
						@if($tipodocumento->vigencia_tipodocumento == 'no')
							NO TIENE
						@else
							{{$tipodocumento->duracion_tipodocumento}}
						@endif
					</td>
					<td class="text-center">
						{{$tipodocumento->obligatoriedad_tipodocumento}}
					</td>
					<td class="text-center">
						<a href="{{ URL::to('tiposdocumentos/'.$tipodocumento->id_tipodocumento.'/edit') }}" class="btn btn-gold">
							<i class="glyphicon glyphicon-edit"></i>
						</a>
					</td>

					<td class="text-center">
						<button type="button" class="{{ (is_null($tipodocumento->deleted_at)) ? 'btn btn-danger' : 'btn btn-success' }}" data-href="{{ URL::to('tiposdocumentos/'.$tipodocumento->id_tipodocumento) }}" data-toggle="modal" data-target = "#confirm-delete" data-whatever="{{ $tipodocumento->detalle_tipodocumento }}|{{ (is_null($tipodocumento->deleted_at)) ? 'deshabilitar' : 'habilitar' }}">
						
							{{ (is_null($tipodocumento->deleted_at)) ? 'Deshabilitar' : 'Habilitar' }}
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
				<h4 class="modal-title"><span class="block-title"></span> Tipo de documento</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12">
						<p>&iquest;Est&aacute; seguro que desea <span class="block-secure-action"></span> el siguiente tipo de documento?</p>
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
