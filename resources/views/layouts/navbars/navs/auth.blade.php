@php
  $oLicencia = \Auth::User()->licencia();
@endphp
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
  <div class="container-fluid">
    <div class="navbar-wrapper">
      <a class="navbar-brand">{{ @$titlePage }} <small>{{ @$descriptionPage }}</small></a>
    </div>

    <div class="">
      <small>
        @if( @$aBreadCrumb != null )
        <a href="{{url('home')}}" class="color-fbm-blue">Inicio</a>
        @foreach($aBreadCrumb as $key => $aBread)
        @if( $aBread['link'] == 'active' )
        / <a> {{$aBread['label']}} </a>
        @else
        / <a href="{{$aBread['link']}}" class="color-fbm-blue"> {{$aBread['label']}} </a>
        @endif
        @endforeach
        @endif
      </small>
    </div>

    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
      <span class="navbar-toggler-icon icon-bar"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end">

      <!--form class="navbar-form">
        <div class="input-group no-border">
          <input type="text" value="" class="form-control" placeholder="Search...">
          <button type="submit" class="btn btn-white btn-round btn-just-icon">
            <i class="material-icons">search</i>
            <div class="ripple-container"></div>
          </button>
        </div>
      </form-->
      @if (session('login_consultor') == true)
        <div class="mr-2 color-fbm-blue">
          <i class="fa fa-eye mr-1"></i><small>Sesión de consultor (asesoría).</small>
        </div>
      @endif

      @php
        $iDiasExpira = round( ( strtotime(\Auth::User()->empresa()->vencimiento_licencia) - strtotime(date('Y-m-d')) ) / 86400);
      @endphp
      @if (@$oLicencia->id == null)
        <div class="mr-2 color-fbm-blue">
          <i class="fa fa-envelope mr-1"></i><small>
            @if ($iDiasExpira <= 0)
              Tu periodo de prueba ha concluido.</small>
              <a href="{{url('soporte_servicios/licenciamiento')}}" class="btn btn-sm bg-fbm-blue text-white">Adquirir licencia</a>
            @else
              Tu periodo de prueba expira {{ ($iDiasExpira == 0 ? 'mañana': ($iDiasExpira == 1 ? 'en un día' : 'en '.$iDiasExpira.' días')) }}.</small>
              <a href="{{url('soporte_servicios/licenciamiento')}}" class="btn btn-sm bg-fbm-blue text-white">Adquirir licencia</a>
            @endif

        </div>
      @elseif ($iDiasExpira <= 0)
        <div class="mr-2 color-fbm-blue">
          <i class="fa fa-envelope mr-1"></i><small>Tu licencia ha vencido.</small> <a href="{{url('soporte_servicios/licenciamiento')}}" class="btn btn-sm bg-fbm-blue text-white">Licenciamiento</a>
        </div>
      @elseif ($iDiasExpira <= 15)
        <div class="mr-2 color-fbm-blue">
          <i class="fa fa-envelope mr-1"></i><small>Tu licencia vence {{ ($iDiasExpira == 0 ? 'mañana': ($iDiasExpira == 1 ? 'en un día' : 'en '.$iDiasExpira.' días')) }}.</small> <a href="{{url('soporte_servicios/licenciamiento')}}" class="btn btn-sm bg-fbm-blue text-white">Licenciamiento</a>
        </div>
      @endif



      <small>{{\Auth::User()->empresa()->nombre}}</small>

      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link" href="#perfil" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <small>| {{\Auth::User()->email}}</small>
            <i class="material-icons">person</i>
            <p class="d-lg-none d-md-block"></p>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
            <div class="text-center">
              <img src="{{ asset( ( \Auth::User()->empresa()->imagen != null ) ? 'images/empresas/'. \Auth::User()->empresa()->imagen : "material/img/FBM_LOGO.png") }}" alt="usuario" style="height: 100px; width: auto"/>
            </div>
            <a class="btn btn-secondary text-left col-12" href="{{ url('usuarios/editar/' . \Auth::User()->id ) }}">Mi perfil</a>
            <a class="btn btn-secondary text-left col-12" href="{{ url('empresa') }}">Mi empresa</a>
            <a class="btn btn-secondary text-left col-12" href="{{ url('facturama/enlace') }}"><span class="badge bg-fbm-blue text-white"><small>NUEVO</small></span> Enlazar cuenta Facturama</a>
            <a class="btn btn-secondary text-left col-12" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar Sesión</a>
          </div>
        </li>
      </ul>

    </div>
  </div>
</nav>
