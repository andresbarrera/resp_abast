@extends('layouts.app')

@section('content')
      <div class="error-page">
        <h2 class="headline text-yellow"> 500</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Hubo un error en el sistema.</h3>

          <p>
            Trabajaremos en solucionarlo en este instante.
            Por mientras, puedes  <a href="/home">retornar al inicio</a> o ponerte en contacto con soporte.
          </p>

        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
@stop