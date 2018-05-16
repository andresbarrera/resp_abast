<!DOCTYPE html>
<html lang="es-CL">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Módulo ABAST V2" />
    <meta name="author" content="Workmate - Resource Manager" />
    <title>
      @yield('title')
    </title>
    <link rel="stylesheet" type="text/css" href="{{ asset('js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-icons/entypo/css/entypo.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/neon-core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/neon-theme.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/neon-forms.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/skins/cafe.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}" />
    <script type="text/javascript" src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/toastr.js') }}"></script>
    <script type="text/javascript">
    $.noConflict();
    var baseurl = '<?php echo url('/'); ?>';
    </script>
    <!--[if lt IE 9]>
      <script type="text/javascript" src="{{ asset('js/ie8-responsive-file-warning.js') }}"></script>
      <script type="text/javascript" src="{{ asset('js/html5shiv.js') }}"></script>
      <script type="text/javascript" src="{{ asset('js/respond.min.js') }}"></script>
    <![endif]-->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon" />
  </head>
  <body class="page-body login-page login-form-fall skin-cafe">
    <div class="login-container">
      <div class="login-header login-caret">
        <div class="login-content">
          <img src="{{ asset('images/logo_wm.png') }}" width="120" alt="Resource Manager" />
          <p class="description">
            <strong>ABAST</strong>
            <br />
            v 2.0
          </p>
        </div>
      </div>
      @yield('content')
      <br />
      <div class="login-content navegadores">
        <div class="col-xs-12">NAVEGADORES RECOMENDADOS</div>
        <br />
        <div class="col-xs-4 text-center">
          <a href="https://www.mozilla.org/es-CL/firefox/new/" target="_blank">
            <img src="{{ asset('images/mozilla_firefox.png') }}" width="30" alt="Mozilla Firefox" />
          </a>
          <br />
          FIREFOX
        </div>
        <div class="col-xs-4 text-center">
          <a href="https://www.google.com/chrome/browser/desktop/index.html" target="_blank">
            <img src="{{ asset('images/chrome_ico.png') }}" width="30" alt="Google Chrome" />
          </a>
          <br />
          CHROME
        </div>
        <div class="col-xs-4 text-center">
          <a href="http://windows.microsoft.com/es-es/internet-explorer/download-ie" target="_blank">
            <img src="{{ asset('images/internet_explorer.png') }}" width="30" alt="Internet Explorer 11" />
          </a>
          <br />
          IEXPLORER 11
        </div>
      </div>
      <br /><br /><br />
      <!-- Footer -->
      <hr />
      <footer class="text-center">
        &copy; 2014 - {{ date('Y') }} <a href="http://www.workmate.cl/" target="_blank">Workmate</a>
        <br />
        Av. Santa Mar&iacute;a 2274, Providencia, Santiago, Chile | (56) 2 2862 9100
        <br />
        Soporte: soportedms@workmate.cl
      </footer>
      <br />
    </div>
    <!-- Bottom scripts (common) -->
    <script type="text/javascript" src="{{ asset('js/gsap/main-gsap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/joinable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/resizeable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/neon-api.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/neon-login.js') }}"></script>
    <!-- JavaScripts initializations and stuff -->
    <script type="text/javascript" src="{{ asset('js/neon-custom.js') }}"></script>
    <!-- Demo Settings -->
    <script type="text/javascript" src="{{ asset('js/neon-demo.js') }}"></script>
    @stack('javascript')
  </body>
</html>