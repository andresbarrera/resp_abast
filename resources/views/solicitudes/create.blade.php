@extends('layouts.app')

@section('title')
ABAST | CREAR SOLICITUD
@stop

@push('javascript')

<!--	<script type="text/javascript" src="{{ asset('js/customs/areas.js') }}"></script> -->

@if ($errors->any())
<script type="text/javascript">toastr.error('Existen errores en la solicitud. Por favor, busque los campos marcados en rojo para corregirlos.', 'Información', {"closeButton": true});</script>
@endif
@endpush

@section('content')
<section class="row">
	<h3 class="col-xs-12">Solicitud</h3>
</section>
<section class="row">
	<div class="col-xs-12">
		<hr/>
	</div>
	<p class="col-xs-12 required">* Campos obligatorios</p>
</section>
<section class="row">
	<div class="col-xs-12">
		<form name="agregar" method="post" action="{{ URL::to('solicitud') }}" role="form" class="form-horizontal">
			{{ csrf_field() }}
			<div class="form-group">
			<div class="box box-warning">
			<h4 class="col-xs-12"> Datos de Solicitud </h4>
			<div class="form-group">
				<div class="col-sm-2 col-md-2">
					<span class="required-asterik">*</span>
					<label for="gastoreembolsable" class="control-label">Gasto Reembolsable</label>
					<div class="radio">
                    	<label>
                      	<input type="radio" name="gastoreembolsable" id="optionsRadios1" value="si" onchange="habilitar(this.value);" checked>
                      	SI
                    	</label>
                    	<label>
	                    <input type="radio" name="gastoreembolsable" id="optionsRadios2" value="no" onchange="habilitar(this.value);">
	                    NO
	                    </label>
                 	</div>
				</div>
				<div class="col-sm-7 col-md-4">
					<label for="prioridades" class="control-label">Prioridad</label>
					<br>
					<select name="prioridades" class="prioridades" id="tiposcontratos" width="150"  style="width: 100px">
						@foreach ($prioridades as $prioridad)
							<option value="{{ $prioridad->id_prioridad }}">{{ $prioridad->nombre_prioridad }}</option>
						@endforeach
					</select>
					@if ($errors->has('prioridad'))
					<p class="alert alert-danger">{{ $errors->first('prioridad') }}</p>
					@endif	
				</div>
				<div class="col-sm-10 col-md-10 field_wrapper">
				    <table>
				    	<tbody>
				    		<div>
				    			<tr>
				    				<td><select name="tiposarticulos" class="tiposarticulos" style="width: 300px">
						                    @foreach ($tipos_articulos as $key => $value)
												<option value="{{ $key }}">{{ $value }}</option>
											@endforeach
					                </select></td>
					                <td><select name="articulos" class="articulos" style="width: 300px"></select></td>
				    				<td><a href="javascript:void(0);" class="btn btn-success glyphicon glyphicon-plus add_button"></a></td>
				    			</tr>
				    		</div>
				    	</tbody>
				    </table>
				</div>

			</div>
			<div align="right">
					<div class="form-group">
						<div class="col-xs-12">
							<input type="submit" name="guardar" value="Guardar" class="btn btn-primary" id="submit" />
							<a href="{{ URL::to('home') }}" class="btn btn-danger">Volver</a>
						</div>
					</div>
				</div>
			</div>
			</div>
			<hr />
		</form>
	</div>
</section>
<script type="text/javascript">
$(function () {
    $(".prioridades").select2({
        placeholder: "Seleccione un tipo"
    });
  });
$(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var x = 1; //Initial field counter is 1
    $(addButton).click(function(){ //Once add button is clicked
        if(x < maxField){ //Check maximum number of input fields
        	var fieldHTML = '<div><tr><td><select id="tiposarticulos'+x+'" name="tiposarticulos'+x+'"  class="tiposarticulos" style="width: 300px">@foreach ($tipos_articulos as $key => $value)<option value="{{ $key }}">{{ $value }}</option>@endforeach</select></td><td><select id="articulos'+x+'" name="articulos'+x+'" class="articulos" style="width: 300px"></select></td><td><a href="javascript:void(0);" class="btn btn-danger glyphicon glyphicon-minus remove_button" title="Remove field"></a></td></tr></div>'; //New input field html 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); // Add field html
        }
    });
    $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});
$(document).ready(function() {
    $('select[name="tiposarticulos"]').on('change', function() {
        var stateID = $(this).val();
        if(stateID) {
            $.ajax({
                url: '/lista-art/ajax/'+stateID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('select[name="articulos"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="articulos"]').append('<option value="'+ key +'">'+ value +'</option>');
                    	});
                }
            });
        }else{
            $('select[name="articulos"]').empty();
        }
    });
});
$(function(){
      $(document).on('change', '#tiposarticulos1', function(){
      	var stateID = $(this).val();
        if(stateID) {
            $.ajax({
                url: '/lista-art/ajax/'+stateID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#articulos1').empty();
                        $.each(data, function(key, value) {
                            $('#articulos1').append('<option value="'+ key +'">'+ value +'</option>');
                    	});
                }
            });
        }else{
            $('#articulos1').empty();
        }
      });
      
    });
$(function(){
      $(document).on('change', '#tiposarticulos2', function(){
      	var stateID = $(this).val();
        if(stateID) {
            $.ajax({
                url: '/lista-art/ajax/'+stateID,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#articulos2').empty();
                        $.each(data, function(key, value) {
                            $('#articulos2').append('<option value="'+ key +'">'+ value +'</option>');
                    	});
                }
            });
        }else{
            $('#articulos2').empty();
        }
      });
      
    });
</script>
@stop