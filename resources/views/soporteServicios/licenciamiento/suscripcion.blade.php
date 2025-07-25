@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

  <div class="row">
    @if ($oEmpresa->subscribed('main'))
      <div class="col-12 text-center">
        <h3 class="">
          Licencia actual: {{@$oLicenciaUsuario->nombre}} <br>
          <small>Vencimiento de licencia {{date('d/m/Y',strtotime($oEmpresa->vencimiento_licencia))}}</small>
        </h3>
      </div>
    @endif
    <div class="col-lg-4 col-md-12 col-sm-12">
      @include('stripe.metodosPagos')
    </div>
    <div class="col-lg-8 col-md-12 col-sm-12">
      @include('soporteServicios.licenciamiento.licencia')
    </div>
  </div>
  <div class="card-footer justify-content-center">
    @if ( $oLicencia->id != @$oLicenciaUsuario->id || $boolSubscriptionStripe == false )
      <a href="javascript:;" class="btn btn-success btn-sm" onclick="suscribirse()"><i class="fa fa-money mr-2"></i>Suscribirse</a>
    @elseif ($boolSubscriptionStripe)
      <a href="javascript:;" class="btn btn-danger btn-sm" onclick="cancelar_suscripcion()"><i class="fa fa-times-circle-o mr-2"></i>Cancelar licencia actual</a>
    @endif
    <a class="btn btn-secondary btn-sm" href="{{url('/soporte_servicios/licenciamiento')}}"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
  </div>

  <script>
    function suscribirse()
    {
      $("#myModalLabel").html('Suscribirse<h3 class="mt-0">{{$oLicencia->nombre}}</h3>');
      $("#myModalBody").html(
        '<form id="form" method="POST" action="' + "{{url("soporte_servicios/licenciamiento/suscribirse")}}" + '" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
        '{{ csrf_field() }}' +
        '<strong>Cargo de $ {{number_format($oLicencia->costo,2,'.',',')}} cada {{$oLicencia->duracion}} (meses).</strong>' +
        '<div class="card-footer text-center">' +
        '<input id="id_licencia" class="d-none" name="id_licencia" value="{{$oLicencia->id}}"/>' +
        '<button type="submit" form="form" class="btn btn-success btn-sm" ><i class="fa fa-money mr-2"></i>Suscribirse</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
        '</div>' +
        '</form>'
      );
      $("#myModal").modal();
    }

    function cancelar_suscripcion()
    {
      $("#myModalLabel").html('Cancelar suscripción a <h3 class="mt-0">{{$oLicencia->nombre}}</h3>');
      $("#myModalBody").html(
        '<form id="form" method="POST" action="' + "{{url("soporte_servicios/licenciamiento/cancelar_suscripcion")}}" + '" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
        '{{ csrf_field() }}' +
        '<strong>Suspender el pago recurrente y las funciones ofrecidas por el plan <strong>{{$oLicencia->nombre}}</strong>.</strong>' +
        '<div class="card-footer text-center">' +
        '<input id="id_licencia" class="d-none" name="id_licencia" value="{{$oLicencia->id}}"/>' +
        '<button type="submit" form="form" class="btn btn-danger btn-sm" ><i class="fa fa-times-circle-o mr-2"></i>Cancelar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
        '</div>' +
        '</form>'
      );
      $("#myModal").modal();
    }
  </script>
@endsection
