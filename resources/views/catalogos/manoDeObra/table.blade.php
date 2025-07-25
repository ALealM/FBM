<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th>Nombre</th>
    <th class="text-center">Puesto</th>
    <th class="text-center">Grupo</td>
    <th class="text-center">Contratación</th>
    <th class="text-center">Periodo</th>
    <th class="text-center">Sueldo</th>
    <th class="text-center">Pagos</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oManoDeObra as $oObra)
      @php
        $aPagos = $oObra->pagos();
      @endphp
    <tr id="manoObra{{$oObra->id}}" data-info="{{$oObra}}" data-pagos="{{collect($aPagos)}}" data-descripcion="{{$oObra->concepto}} ${{ number_format($oObra->costo,2,".",",") }}">
      <td>
        {{ $oObra->nombre }}
      </td>
      <td class="text-center">
        {{ $oObra->concepto }}
      </td>
      <td class="text-center">
        {{ ($oObra->nombre_grupo != null ? $oObra->nombre_grupo : '-Sin grupo-') }}
      </td>
      <td class="text-center">
        {{ @$aContratacion[$oObra->tipo] }}
      </td>
      <td class="text-center">
        {{ $oObra->nombre_periodo }}
      </td>
      <td class="text-right">
        ${{ number_format($oObra->costo,2,".",",") }}
      </td>
      <td class="text-center">
        <!--rel="tooltip" title="" data-original-title="Remove"-->
        <span class="badge badge-{{($aPagos["numero_pagos_pendientes"] > 0 ? 'danger' : 'success' )}}">{{ $aPagos["numero_pagos_pendientes"] }} pendientes</span>
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="{{url('/mano_de_obra/editar/'.$oObra->id)}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
            <a href="{{url('/mano_de_obra/editar/'.$oObra->id)}}#calculo_impuestos" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-university mr-2"></i>Impuestos</a><br>
            <a href="{{url('/mano_de_obra/editar/'.$oObra->id)}}#pagos" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-list mr-2"></i>Pagos</a><br>

            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="pago('{{$oObra->id}}',0,'manoObra')"><i class="fa fa-money mr-2"></i>Pagar</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_mano_de_obra('{{$oObra->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@include('catalogos.pagos.functions')
<script>
function eliminar_mano_de_obra(iId)
{
  $("#myModalLabel").html('Eliminar mano de obra <h3 class="mt-0">' + $("#manoObra" + iId ).data('descripcion') + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( "mano_de_obra/destroy" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>' + $("#manoObra" + iId).data('descripcion') + '</strong>' +
      '<p>La mano de obra se eliminará y no habrá vuelta atrás.</p>' +
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
