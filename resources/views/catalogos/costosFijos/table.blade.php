<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th class="text-center">Concepto</th>
    <th class="text-center">Inicio</th>
    <th class="text-center">Periodo</th>
    <th class="text-center">Costo</th>
    <th class="text-center">Pagos</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oFijos as $key => $oFijo)
      @php
      $aPagos = $oFijo->pagos();
      @endphp
      <tr id="costoFijo{{$oFijo->id}}" data-info="{{$oFijo}}" data-descripcion="{{ $oFijo->concepto }} ${{ number_format($oFijo->costo,2,".",",") }}" data-pagos="{{collect($aPagos)}}">
        <td>
          {{ $oFijo->concepto }}
        </td>
        <td class="text-center">
          {{ date("d/m/Y", strtotime($oFijo->inicio)) }}
        </td>
        <td class="text-center">
          {{ $oFijo->nombre_periodo }}
        </td>
        <td class="text-right">
          ${{ number_format($oFijo->costo,2,".",",") }}
        </td>
        <td class="text-center">
          <span class="badge badge-{{($aPagos["numero_pagos_pendientes"] > 0 ? 'danger' : 'success' )}}">{{ $aPagos["numero_pagos_pendientes"] }} pendientes</span>
        </td>
        <td class="text-center">
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              Acción
            </button>
            <div class="dropdown-menu">
              <a href="{{url('/costos_fijos/editar/'.$oFijo->id)}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-pencil mr-2"></i>Ver/Editar</a><br>
              <a href="{{url('/costos_fijos/editar/'.$oFijo->id)}}#pagos" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-list mr-2"></i>Pagos</a><br>
              <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="pago('{{$oFijo->id}}',0,'costoFijo')"><i class="fa fa-money mr-2"></i>Pagar</a><br>
              <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_costo_fijo('{{$oFijo->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
            </div>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@include('catalogos.pagos.functions')
<script>
function eliminar_costo_fijo(iId)
{
  $("#myModalLabel").html('Eliminar costo fijo <h3 class="mt-0">' + $("#costoFijo" + iId ).data('descripcion') + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( "costos_fijos/destroy" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
    '{{ csrf_field() }}' +
    '<strong>' + $("#costoFijo" + iId).data('descripcion') + '</strong>' +
    '<p>El costo fijo se eliminará y no habrá vuelta atrás.</p>' +
    '<div class="card-footer text-center">' +
    '<input class="d-none" name="id" value="' + iId +'"/>' +
    '<button type="submit" form="form2" class="btn btn-danger btn-sm"><i class="fa fa-times mr-2"></i>Eliminar</button>' +
    '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
    '</div>' +
    '</form>'
  );
  $("#myModal").modal();
}
</script>
