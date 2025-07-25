@extends('layouts.app', ['activePage' => @$sActivePage ])
<!--extends('layouts.app', ['class' => (@$boolAuth == true ? '' : 'off-canvas-sidebar'), 'activePage' => (@$boolAuth == true ? @$sActivePage  : 'login'), 'title' => __('Full Business Manager')])-->
@section('content')

  <div class="row {{(@$boolAuth == true ? '' : 'container' )}} m-2">

    @if (@$oEmpresa->id != null)
      <div class="col-12 text-center">
        <h4 class="{{(@$boolAuth == true ? '' : 'text-white' )}}">Vencimiento de licencia {{@$oLicenciaUsuario->id!=null?$oLicenciaUsuario->nombre:'TRIAL'}}: {{date('d/m/Y',strtotime($oEmpresa->vencimiento_licencia))}}</h4>
      </div>
    @endif


    @if ( false )<!--if ( @$oLastSolicitud->id != null)-->
      <div class="col-12 justify-content-center {{(@$boolAuth == true ? '' : 'text-white' )}} text-center">
        Última solictud enviada el {{ date('d/m/Y',strtotime($oLastSolicitud->fecha_registro)) }} en estado:
        @if ( $oLastSolicitud->estado == 1 )
          <span class="text-success ml-2">Enviada.</span>
        @elseif ( $oLastSolicitud->estado == 2 )
          <span class="text-success ml-2">Aceptada.</span>
        @elseif ( $oLastSolicitud->estado == 3 )
          <span class="text-danger ml-2">Rechazada.</span>
        @endif
      </div>
    @endif

    @foreach ($oLicencias as $key => $oLicencia)
      <div class="col-lg-3 col-md-6 col-sm-12">
        @include('soporteServicios.licenciamiento.licencia')
      </div>
    @endforeach
  </div>
  <script>
  function solicitar(iId)
  {
    var sNombre = $("#licencia" + iId ).data('info')['nombre'];
    var fCosto = parseFloat($("#licencia" + iId).data('info')['costo']);

    $("#myModalLabel").html('Solicitar licencia <h3 class="mt-0">' + sNombre + '</h3>');
    $("#myModalBody").html(
      '<form id="form" method="GET" action="' + "{{url("soporte_servicios/licenciamiento/solicitud_licencia_store")}}" + '" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong> $ ' + fCosto + '</strong>' +
      '<p>Tu solicitud será atendida por un asesor para proceder con el acuerdo.</p>' +
      '<div class="card-footer text-center">' +
      '<input id="id_licencia" class="d-none" name="id_licencia" value="' + iId +'"/>' +
      '<input id="id_usuario_solicitante" class="d-none" name="id_usuario_solicitante" value="{{$iIdUsuario}}"/>' +
      '<button type="submit" form="form" class="btn btn-success btn-sm" ><i class="fa fa-check mr-2"></i>Enviar solicitud</button>' +
      '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
      '</form>'
    );
    $("#myModal").modal();
  }

  /*function enviar_solicitud()
  {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "GET",
      url: "{{ asset ('soporte_servicios/licenciamiento/solicitud_licencia_store') }}",
      data: {
        'id_licencia' : $("#id_licencia").val(),
        'id_usuario_solicitante' : $("#id_usuario_solicitante").val()
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){

        }else {

        }
        //$("#myModal").modal();
      },
      error: function (result) {
        console.log("error");
      }
    });
  }*/
  </script>
@endsection
