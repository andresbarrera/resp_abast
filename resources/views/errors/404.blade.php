@extends('layouts.app')

@section('content')
      <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! Página no encontrada.</h3>

          <p>
            No se pudo encontrar la página que estabas buscando.
            Por mientras, puedes  <a href="/home">retornar al inicio</a> o ponerte en contacto con soporte.
          </p>

        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
@stop