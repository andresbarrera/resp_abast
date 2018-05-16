@extends('layouts.app')
@section('title')
ABAST | BIBLIOTECA DOCUMENTAL
@stop
@push('javascript')
<link rel="stylesheet" href={{asset('css/archivos-tabla.css')}} >
@if (Session::has('mensaje'))
<script type="text/javascript">
	toastr.options = {
		"preventDuplicates": true,
		"preventOpenDuplicates": true
	};
	toastr.success("{{ Session::get('mensaje') }}", 'Información');
</script>
<?php  session()->forget('mensaje');?>
@endif
@if (Session::has('alert'))
<script type="text/javascript">toastr.error("{{ Session::get('alert') }}", 'Información');</script>
<?php  session()->forget('alert');?>
@endif
@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
<div id="overlay"></div>
<div id="spinner" class="spinner" style="display:none;">
    <div align="center">
    	CARGANDO...
    </div>
    <br>
    <img id="img-spinner" src="{{ asset('245.gif') }}" alt="Cargando..."/>
    <br>
</div>
@endpush
@section('header')
@endsection
@section('content')
<section class="row">
	<div class="col-xs-12">
		<div class="col-xs-12">
			<div class="box box-warning">
			<div class="box-header with-border">
              <h3 class="box-title">Documentos</h3>
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
              <!-- /.box-tools -->
            </div>
            <div class="box-body">
			<div class="form-group">
				<div class="col-sm-7 col-md-10">        
				<form method="POST" action="{{ URL::to('storage/create/doc') }}" accept-charset="UTF-8" enctype="multipart/form-data">      
	            <input type="hidden" name="_token" value="{{ csrf_token() }}">
	            <div class="form-group">
	              <div align="center">
			            	<table id="tabla" class="table" data-display-length='-1'>
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">NOMBRE DOCUMENTO</th>
									<th class="text-center">DESCARGAR</th>				
								</tr>
							</thead>
							<tbody>
	              			<tr>
	                      		<td class="text-center">1</td>
								<td class="text-center">Reglamento Interno</td>
	                      		<td class="text-center">
	                      				<a href="/storage/files/doc/{{Crypt::encrypt('reg-int.pdf')}}" class="btn btn-success">Descargar</a>
	                      		</td>
	                    	</tr>
	                    	<tr>
	                      		<td class="text-center">2</td>
								<td class="text-center">Manual Sustentabilidad</td>
	                      		<td class="text-center">
	                      				<a href="/storage/files/doc/{{Crypt::encrypt('man-sust.docx')}}" class="btn btn-success">Descargar</a>
	                      		</td>
	                    	</tr>
	                    	<tr>
	                      		<td class="text-center">3</td>
								<td class="text-center">Manual Administrador</td>
	                      		<td class="text-center">
	                      				<a href="/storage/files/doc/{{Crypt::encrypt('man-admin.docx')}}" class="btn btn-success">Descargar</a>
	                      		</td>
	                    	</tr>
	                    	
	              			</tbody>
							</table> 
	            	</div>
	 			</div>
		      	</div>
		    </div>
		    <div align="right">
					<div class="form-group">
						<div class="col-xs-12">
							<a href="{{ URL::to('home') }}" class="btn btn-danger">Volver</a>
						</div>
					</div>
				</div>
		    </div>
	        </form>
	  </div>
	</div>  
</section>
@stack('javascript')
@stop