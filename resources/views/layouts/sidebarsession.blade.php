<!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">


      <!-- Sidebar Menu -->
      <div class="sidebar-menu">
        <div class="sidebar-menu-inner">
          <header class="logo-env">
            <!-- logo 
            <div class="logo">
              <img src="{{ asset('images/logo_workmate.png') }}" width="120" alt="Resource Manager" />
            </div>
            -->
            <!-- logo collapse icon -->
            <div class="sidebar-collapse">
              <a href="#" class="sidebar-collapse-icon with-animation">
                <i class="entypo-menu"></i>
              </a>
            </div>
            <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
            <div class="sidebar-mobile-menu visible-xs">
              <a href="#" class="with-animation">
                <i class="entypo-menu"></i>
              </a>
            </div>
          </header>
              
              
            <ul class="sidebar-menu">
              <!-- add class "multiple-expanded" to allow multiple submenus to open -->
              <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
              @foreach (Session::get('menus') as $menu)
              <li class="active treeview">
                <a href="{{ !is_null($menu['accion_menu']) ? URL::to($menu['accion_menu']) : '#' }}">
                  <i class="{{ $menu['icono_menu'] }}"></i>
                  <span class="title">{{ $menu['nombre_menu'] }}</span>
                </a>
                @if (is_null($menu['accion_menu']))
                <ul class="treeview-menu">
                  @foreach ($menu['submenu'] as $submenu)
                  <li class="treeview">
                    <a href="{{ !is_null($submenu['accion_submenu']) ? URL::to($submenu['accion_submenu']) : '#' }}">
                      <i class="{{ $submenu['icono_submenu'] }}"></i>
                      <span>{{ $submenu['nombre_submenu'] }}</span>
                    </a>
                  </li>
                  @endforeach
                </ul>
                @endif
              </li>

              @endforeach
            </ul>
           
        </div>
      </div>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>