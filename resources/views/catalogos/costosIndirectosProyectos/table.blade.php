<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th>Concepto</th>
    <th class="text-center">Medida</th>
    <th class="text-center">Unidades por medida</th>
    <th class="text-center">Proyecto</th>
    <th class="text-center">Costo</th>
    <th class="text-center">Estado</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oCostosIndirectos as $oCostoIndirecto)
      @php
        //$oCostoIndirecto->costo_total = (@$oCostoIndirectoProyecto->unidades > 0 ? $oCostoIndirectoProyecto->unidades : 1 ) * $oCostoIndirecto->costo;
        $oCostoIndirectoProyecto = $oCostoIndirecto->get_costo_indirecto_proyecto();
        $oCostoIndirecto->costo_total = $oCostoIndirecto->costo;
        $oCostoIndirecto->id_costo_indirecto_proyecto = @$oCostoIndirectoProyecto->id;
        $aPagos = ( $oCostoIndirectoProyecto != null ? $oCostoIndirecto->pagos() : [] );
      @endphp
    <tr id="costoIndirecto{{$oCostoIndirecto->id}}" data-descripcion="{{ number_format($oCostoIndirecto->unidades,2,".",",") }} unidades de {{ $oCostoIndirecto->concepto }}" data-info="{{ $oCostoIndirecto }}" data-pagos="{{collect($aPagos)}}">
      <td>
        {{ $oCostoIndirecto->concepto }}
      </td>
      <td class="text-center">
        {{ $oCostoIndirecto->medida()->medida }}
      </td>
      <td class="text-center">
        {{ number_format($oCostoIndirecto->unidades) }}
      </td>
      <td class="text-center p-0">
        {{ @$oCostoIndirectoProyecto->nombre_proyecto }}
      </td>
      <td class="text-right">
        <small>$</small>{{ number_format($oCostoIndirecto->costo,2,".",",") }}
      </td>

      <td class="text-center text-{{$oCostoIndirecto->comprado == 1 ? 'success':'danger'}} p-0">
        {{$oCostoIndirecto->comprado == 1 ? 'Facturado':'Cotizado'}}
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="{{url('/costos_indirectos/editar/'.$oCostoIndirecto->id)}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
            <a href="{{url('/costos_indirectos/editar/'.$oCostoIndirecto->id)}}#pagos" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-list mr-2"></i>Pagos</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="pago('{{$oCostoIndirecto->id}}',0,'costoIndirecto')"><i class="fa fa-money mr-2"></i>Pagar</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_costo_indirecto('{{$oCostoIndirecto->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@include('catalogos.pagos.functions')
<script>
function eliminar_costo_indirecto(iId)
{
  $("#myModalLabel").html('Eliminar costo indirecto <h3 class="mt-0">' + $("#costoIndirecto" + iId ).data('info')['concepto'] + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( "costos_indirectos/destroy" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>' + $("#costoIndirecto" + iId).data('descripcion') + '</strong>' +
      '<p>El costo indirecto se eliminará y no habrá vuelta atrás.</p>' +
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
