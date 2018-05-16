@extends('layouts.app')
@section('title')
ABAST | MODIFICAR TIPOS DE DOCUMENTOS
@stop
@push('javascript')
<script type="text/javascript" src="{{ asset('js/customs/tiposdocumentos.js') }}"></script>
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
<style type="text/css" media="screen">
	.extensiones {
    width: 200px;       
	}
	.bigdrop{
	    width: 100px !important;

	}
</style>
@endpush
@section('content')
<section class="row">
	<h3 class="col-xs-12">Modificar Tipos de Documentos</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr />
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="modificar" method="post" action="{{ URL::to('tiposdocumentos/'.$tiposdocumentos->id_tipodocumento) }}" role="form" class="form-horizontal">
			<input type="hidden" name="_method" value="put" />
			{{ csrf_field() }}
			<div class="form-group">
				<div class="col-sm-6 col-md-4">
					<span class="required-asterik">*</span>
					<label for="tiposdocumentos" class="control-label">Tipo de Documento</label>
					<input type="text" name="tiposdocumentos" value="{{ (old('tiposdocumentos')) ? old('tiposdocumentos') : $tiposdocumentos->detalle_tipodocumento }}" class="form-control" id="nombre" autocomplete="off" placeholder="Nombre del tipo de documento" autofocus="true" maxlength="50" style="<?php if ($errors->has('tiposdocumentos')) {?> border: 1px solid #F00; <?php } ?>" />
					@if ($errors->has('tiposdocumentos'))
					<p class="alert alert-danger">{{ $errors->first('tiposdocumentos') }}</p>
					@endif
				</div>
				<div class="col-sm-2 col-md-2">
					<span class="required-asterik">*</span>
					<label for="vigencia" class="control-label">Vigencia</label>
					<div class="radio">
                    	<label>
                      	<input type="radio" name="vigencia" id="optionsRadios1" value="si" onchange="habilitar(this.value);" @if(old('vigencia',$tiposdocumentos->vigencia_tipodocumento)=="si") checked @endif>
                      	SI
                    	</label>
                    	<label>
	                    <input type="radio" name="vigencia" id="optionsRadios2" value="no" onchange="habilitar(this.value);" @if(old('vigencia',$tiposdocumentos->vigencia_tipodocumento)=="no") checked @endif>
	                    NO
	                    </label>
                 	</div>
				</div>
				<div class="col-sm-2 col-md-2">
					<span class="required-asterik">*</span>
					<label for="obligatoriedad" class="control-label">Obligatoriedad</label>
					<div class="radio">
                    	<label>
                      	<input type="radio" name="obligatoriedad" id="optionsRadios1" value="si" @if(old('obligatoriedad',$tiposdocumentos->obligatoriedad_tipodocumento)=="si") checked @endif>
                      	SI
                    	</label>
                    	<label>
	                    <input type="radio" name="obligatoriedad" id="optionsRadios2" value="no" @if(old('obligatoriedad',$tiposdocumentos->obligatoriedad_tipodocumento)=="no") checked @endif>
	                    NO
	                    </label>
                 	</div>
				</div>
				<div class="col-sm-3 col-md-3">
					<span class="required-asterik">*</span>
					<label for="duracion" class="control-label">Duración(años)</label>
					<input type="number" name="duracion" value="{{ (old('duracion')) ? old('duracion') : $tiposdocumentos->duracion_tipodocumento }}" class="form-control" id="duracion" autocomplete="off" placeholder="Duracion del tipo de documento" autofocus="true" maxlength="50" style="<?php if ($errors->has('duracion')) {?> border: 1px solid #F00; <?php } ?>" min="1" max="10" />

					@if ($errors->has('duracion'))
					<p class="alert alert-danger">{{ $errors->first('duracion') }}</p>
					@endif
				</div>
				<div class="col-sm-10 col-md-9">
					<label for="descripcion" class="control-label">Descripción</label>
					<br>
					<textarea name="descripcion" id="descripcion" rows="10" cols="60" value="{{ (old('descripcion')) ? old('descripcion') : $tiposdocumentos->descripcion_tipodocumento }}" >{{$tiposdocumentos->descripcion_tipodocumento}}</textarea>
				</div>
				<br>
			</div>
			<hr />
			<div class="form-group">
				<div class="col-xs-12">
					<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" />
					<a href="{{ URL::to('tiposdocumentos') }}" class="btn btn-danger">Volver</a>
				</div>
			</div>
		</form>
	</div>
</section>
</section>
<script type="text/javascript" >
	function habilitar(value)
	{
		if(value=="si" || value==true)			{
			// habilitamos
			document.getElementById("duracion").disabled=false;
			document.getElementById("duracion").required=true;
		}
		else if(value=="no" || value==false){
			// deshabilitamos
			document.getElementById("duracion").disabled=true;
			document.getElementById("duracion").required=false;
			document.getElementById("duracion").value="";	
		}
	}
</script>
@stop