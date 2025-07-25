@php
  $boolLicenciaActual = $oLicencia->id == @$oLicenciaUsuario->id && $boolSubscriptionStripe;
  $aPermisos = \Auth::User()->permisos();
@endphp

<div id="licencia{{ $oLicencia->id }}" class="col-lg-12 col-md-12 col-sm-12" data-info="{{ $oLicencia }}">
  <div class="card pricing" style="height:90%">
    <div class="card-body {{ ( $boolLicenciaActual ? 'bg-primary text-white' : '' ) }}">
      <div class="text-right">

        @if (@$sTipoVista == 'index')
          <div class="btn-group m-0" role="group">
            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              Opciones
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              @if ( $boolLicenciaActual == false )
                <!--a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="solicitar({{$oLicencia->id}})"><i class="fa fa-tags mr-2"></i>Solicitar</a><br-->
                <a href="{{asset('soporte_servicios/licenciamiento/suscripcion').'/'.$oLicencia->id }}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-tags mr-2"></i>Suscribirse</a><br>
              @elseif ( $boolLicenciaActual )
                <a href="{{asset('soporte_servicios/licenciamiento/suscripcion').'/'.$oLicencia->id }}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-tags mr-2"></i>Cancelar suscripción</a><br>
              @endif
            </div>
          </div>
        @endif
      </div>
      <div class="text-center">
        @if ( $boolLicenciaActual )
          <div style="display: flex;justify-content:center;">
            <i class="material-icons mr-2 color-fbm-blue">stars</i>Licencia en uso
          </div>
        @endif
        <div class="h3">{{$oLicencia->nombre}}</div>
        <div class="pricing-price h4">$ {{number_format($oLicencia->costo,2,'.',',')}}</div>
      </div>
      <div>
        <p>{{$oLicencia->descripcion}}</p>
        <ul class="fa-ul pricing-list">
          @if ($aPermisos['tipo_sistema'] != 3)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_mano_obra == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_mano_obra == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> Plantilla</span>
            </li>
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_costos_fijos == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_costos_fijos == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual? 'white' : 'dark' ) }}"> Costos fijos</span>
            </li>
          @endif
          @if ($aPermisos['tipo_sistema'] == 1)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_materia_prima == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_materia_prima == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> Materia prima</span>
            </li>
          @endif
          @if ($aPermisos['tipo_sistema'] != 3)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_costos_indirectos == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_costos_indirectos == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual? 'white' : 'dark' ) }}"> Costos indirectos</span>
            </li>
          @endif

          @if ($aPermisos['tipo_sistema'] == 1)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_productos == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_productos == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> Productos</span>
            </li>
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_ventas == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_ventas == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> Ventas</span>
            </li>
          @endif

          @if ($aPermisos['tipo_sistema'] != 3)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_estado_cuenta == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_estado_cuenta == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> Estado cuenta</span>
            </li>
          @endif

          @if ($aPermisos['tipo_sistema'] == 1)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_proyecciones == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_proyecciones == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> Proyecciones</span>
            </li>
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_escenarios == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_escenarios == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> Escenarios</span>
            </li>
          @endif

          @if ($aPermisos['tipo_sistema'] == 2)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 text-{{($oLicencia->m_proyectos == 1 ? 'success' : 'danger' )}}">{{($oLicencia->m_proyectos == 1 ? 'check_circle_outline' : 'clear' )}}</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> Proyectos</span>
            </li>
          @endif

          @if ($aPermisos['tipo_sistema'] != 3)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 color-fbm-blue">engineering</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> {{$oLicencia->numero_mano_obra}} registros en plantilla</span>
            </li>
          @endif

          @if ($aPermisos['tipo_sistema'] == 1)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 color-fbm-blue">local_mall</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> {{$oLicencia->numero_productos}} productos</span>
            </li>
          @endif

          @if ($aPermisos['tipo_sistema'] == 2)
            <li class="pricing-list-item mt-2" style="display: flex;">
              <i class="material-icons mr-2 color-fbm-blue">assistant_photo</i>
              <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> {{$oLicencia->numero_proyectos}} proyectos</span>
            </li>
          @endif

          <li class="pricing-list-item mt-2" style="display: flex;">
            <i class="material-icons mr-2 color-fbm-blue">calendar_today</i>
            <span class="text-{{ ( $boolLicenciaActual ? 'white' : 'dark' ) }}"> ({{$oLicencia->duracion}}) meses</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
