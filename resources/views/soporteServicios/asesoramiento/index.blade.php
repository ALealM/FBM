@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  <div class="mb-2 mt-2">
    <a href="{{url('soporte_servicios/asesoramiento/solicitudes')}}" class="btn btn-success"><i class="fa fa-file-o mr-2"></i>Solicitudes enviadas</a>
  </div>

  @if ( @$oLastSolicitud->id != null)
    <div class="col-12 justify-content-center text-center">
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

  <div class="row" style="justify-content: center;">
    @foreach ($oTiposAsesorias as $key => $oTipoAsesoria)
      <div id="tipoasesoria{{ $oTipoAsesoria->id }}" class="col-lg-3 col-md-6 col-sm-6" data-info="{{ $oTipoAsesoria }}">
        <div class="card pricing" style="height:90%">
          <div class="card-body">
            <div class="text-right">
              <div class="btn-group m-0" role="group">
                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Opciones
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                  <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="solicitar({{$oTipoAsesoria->id}})"><i class="fa fa-tags mr-2"></i>Solicitar</a><br>
                </div>
              </div>
            </div>
            <div class="text-center">
              <img src="{{ asset('material/img/FBM_LOGO.png') }}" style="height: 150px; width: auto;"/>
              <div class="h3">{{$oTipoAsesoria->nombre}}</div>
              <div class="pricing-price h4">$ {{number_format($oTipoAsesoria->costo,2,'.',',')}}</div>
            </div>
            <div>
              <p>{{$oTipoAsesoria->descripcion}}</p>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <script>
  function solicitar(iId)
  {
    var sNombre = $("#tipoasesoria" + iId ).data('info')['nombre'];
    var fCosto = parseFloat($("#tipoasesoria" + iId).data('info')['costo']);
    var sDescripcion = $("#tipoasesoria" + iId).data('info')['descripcion'];

    $("#myModalLabel").html('Solicitar asesoría <h3 class="mt-0">' + sNombre + '</h3>');
    $("#myModalBody").html(
      '<form id="form" method="GET" action="' + "{{url("soporte_servicios/asesoramiento/solicitud_asesoramiento_store")}}" + '" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<p>' + sDescripcion + '</p>' +
      '<strong> $ ' + fCosto + '</strong>' +
      '<p>Tu solicitud será atendida por un asesor para proceder con el acuerdo.</p>' +
      '<div class="card-footer text-center">' +
      '<input id="id_asesoria" class="d-none" name="id_asesoria" value="' + iId +'"/>' +
      '<input id="id_usuario_solicitante" class="d-none" name="id_usuario_solicitante" value="{{\Auth::User()->id}}"/>' +
      '<button type="submit" form="form" class="btn btn-success btn-sm" ><i class="fa fa-check mr-2"></i>Enviar solicitud</button>' +
      '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
      '</form>'
    );
    $("#myModal").modal();
  }
  </script>
@endsection
