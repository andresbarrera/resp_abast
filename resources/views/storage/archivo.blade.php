@extends('layouts.app')
@section('title')
RM WORKMATE | ABAST - CARGA DE DOCUMENTOS
@stop
@push('javascript')
@if (Session::has('mensaje'))
<script type="text/javascript">toastr.success("{{ Session::get('mensaje') }}", 'Información');</script>
@endif
@if (Session::has('alert'))
<script type="text/javascript">toastr.error("{{ Session::get('alert') }}", 'Información');</script>
@endif
@endpush

@section('content')
 
<div class="container">
 
<div class="row">
  <div class="col-md-10 col-md-offset-1">
    <div class="panel panel-default">
      <div class="panel-heading">Agregar archivos</div>
        <div class="panel-body">
          <form method="POST" action="http://localhost:8000/storage/create" accept-charset="UTF-8" enctype="multipart/form-data">
            
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              @foreach ($documentos as $key => $documento) 
                    <div class="form-group">
                    <br>
                      <label class="col-md-6 control-label">{{$documento->detalle_tipodocumento}}</label>
                      <br>
                          <div class="col-md-9">
                            <input type="file" class="form-control" id="{{$documento->detalle_tipodocumento}}" name="file{{ ++$key }}">
                            @foreach($empleados_documentos as $empleado)

                                @if(Storage::disk('prs')->exists($empleado->nombre_empleado_tipodocumento) && $empleado->fkid_tipodocumento == $documento->id_tipodocumento)
                                  <a href="/storage/files/prs/{{Crypt::encrypt($empleado->nombre_empleado_tipodocumento)}}">Descargar</a>

                                @endif
                            @endforeach
                          </div>
                      <br>
                    </div>
              @endforeach


            </div>
 
            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">Enviar</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
 
@endsection