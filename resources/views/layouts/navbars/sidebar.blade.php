@php
  $aPermisos = \Auth::User()->permisos();
@endphp
<div class="sidebar" data-color="" data-background-color="white" data-image="{{ asset('material') }}/img/FBM_LOGO.png">
  <div class="logo text-center">
    <a href="/" class="simple-text logo-mini">
      <img style="width:50px; height:auto;" src="{{ asset('material') }}/img/FBM_LOGO.png">
    </a>
    <!--span class="badge badge-primary"><small>{{$aPermisos['nombre_licencia']}}</small></span-->
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">

      <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons {{ $activePage == 'inicio' ? 'color-fbm-blue' : '' }}">home</i>
          <p>inicio</p>
        </a>
      </li>
      <!--li class="nav-item">
        <a class="nav-link" href=" route('catalogos') ">
          <i class="material-icons {{ $activePage == 'catalogos' ? 'color-fbm-blue' : '' }}">dashboard</i>
          <p>Catálogos</p>
        </a>
      </li-->

    

      @if ( $aPermisos['m_mano_obra'] )
      <li class="nav-item">
        <a class="nav-link" href="{{ url('mano_de_obra') }}">
          <i class="material-icons {{ $activePage == 'mano_de_obra' ? 'color-fbm-blue' : '' }}">engineering</i>
          <p>Plantilla</p>
        </a>
      </li>
      @endif

      @if ( $aPermisos['m_costos_fijos'] )
      <li class="nav-item">
        <a class="nav-link" href="{{ url('costos_fijos') }}">
          <i class="material-icons {{ $activePage == 'costos_fijos' ? 'color-fbm-blue' : '' }}">linear_scale</i>
          <p>Costos fijos</p>
        </a>
      </li>
      @endif

      @if ( $aPermisos['m_materia_prima'] )
      <li class="nav-item">
        <a class="nav-link" href="{{ url('materia_prima') }}">
          <i class="material-icons {{ $activePage == 'materia_prima' ? 'color-fbm-blue' : '' }}">scatter_plot</i>
          <p>Materia prima</p>
        </a>
      </li>
      @endif

      @if ( $aPermisos['m_costos_fijos'] )
      <li class="nav-item">
        <a class="nav-link" href="{{ url('costos_indirectos') }}">
          <i class="material-icons {{ $activePage == 'costos_indirectos' ? 'color-fbm-blue' : '' }}">vertical_distribute</i>
          <p>Costos indirectos</p>
        </a>
      </li>
      @endif

      @if ( $aPermisos['m_proyectos'] )
        <li class="nav-item">
          <a class="nav-link" href="{{url('proyectos')}}">
            <i class="material-icons {{ $activePage == 'proyectos' ? 'color-fbm-blue' : '' }}">assistant_photo</i>
            <p>Proyectos</p>
          </a>
        </li>
      @endif

      @if ( $aPermisos['m_productos'] )<!--Con permiso o en modo prueba y que sea el tipo de sistema-->
      <li class="nav-item">
        <a class="nav-link" href="{{ url('productos') }}">
          <i class="material-icons {{ $activePage == 'productos' ? 'color-fbm-blue' : '' }}">local_mall</i>
          <p>Productos</p>
        </a>
      </li>
      @endif

      <!--li class="nav-item">
        <a class="nav-link" href=" url('almacen') ">
          <i class="material-icons {{ $activePage == 'almacen' ? 'color-fbm-blue' : '' }}">store</i>
          <p>Almacén</p>
        </a>
      </li-->

      @if ( $aPermisos['m_ventas'] )<!--Con permiso o en modo prueba y que sea el tipo de sistema-->
      <li class="nav-item">
        <a class="nav-link" href="{{ url('ventas') }}">
          <i class="material-icons {{ $activePage == 'ventas' ? 'color-fbm-blue' : '' }}">shopping_cart</i>
          <p>Ventas</p>
        </a>
      </li>
      @endif

      @if ( $aPermisos['m_estado_cuenta'] )
        <li class="nav-item">
          <a class="nav-link" href="{{ url('cuentas') }}">
            <i class="material-icons {{ $activePage == 'cuentas' ? 'color-fbm-blue' : '' }}">request_page</i>
            <p>Estado de cuenta</p>
          </a>
        </li>
      @endif


      <!--li class="nav-item">
        <a class="nav-link" href=" url('reportes') ">
          <i class="material-icons {{ $activePage == 'reportes' ? 'color-fbm-blue' : '' }}">trending_up</i>
          <p>Reportes</p>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href=" route('actividad') ">
          <i class="material-icons {{ $activePage == 'actividad' ? 'color-fbm-blue' : '' }}">grading</i>
          <p>Actividad</p>
        </a>
      </li-->

      @if ( $aPermisos['m_proyecciones'] )<!--Con permiso o en modo prueba y que sea el tipo de sistema-->
      <li class="nav-item">
        <a class="nav-link" href="{{ url('proyecciones') }}">
          <i class="material-icons {{ $activePage == 'proyecciones' ? 'color-fbm-blue' : '' }}">timeline</i>
          <p>Proyecciones</p>
        </a>
      </li>
      @endif

      @if ( $aPermisos['m_escenarios'] )<!--Con permiso o en modo prueba y que sea el tipo de sistema-->
      <li class="nav-item">
        <a class="nav-link" href="{{ url('escenarios') }}">
          <i class="material-icons {{ $activePage == 'escenarios' ? 'color-fbm-blue' : '' }}">insert_chart</i>
          <p>Escenarios</p>
        </a>
      </li>
      @endif

      @if ( $aPermisos['m_facturama'] )<!--Con permiso o en modo prueba y que sea el tipo de sistema-->
      <li class="nav-item">
        <a class="nav-link" href="{{ url('facturama') }}">
          <i class="material-icons {{ $activePage == 'facturama' ? 'color-fbm-blue' : '' }}">snippet_folder</i>
          <p>Facturama</p>
        </a>
      </li>
      @endif

      <li class="nav-item">
        <a class="nav-link" href="{{ url('soporte_servicios') }}">
          <i class="material-icons {{ $activePage == 'soporte_servicios' ? 'color-fbm-blue' : '' }}">admin_panel_settings</i>
          <p>Soporte y servicios</p>
        </a>
      </li>

    </ul>
  </div>
</div>
