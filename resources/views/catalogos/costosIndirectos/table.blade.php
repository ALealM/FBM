<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th class="text-center">Concepto</th>
    <th class="text-center">Medida</th>
    <th class="text-center">Unidades por medida</th>
    <th class="text-center">Costo</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oCostosIndirectos as $oCostoIndirecto)
    <tr id="costosIndirecto{{$oCostoIndirecto->id}}" data-info="{{ $oCostoIndirecto->concepto }} con {{ number_format($oCostoIndirecto->unidades) }} unidades">
      <td>
        {{ $oCostoIndirecto->concepto }}
      </td>
      <td class="text-center">
        {{ $oCostoIndirecto->medida()->medida }}
      </td>
      <td class="text-center">
        {{ number_format($oCostoIndirecto->unidades) }}
      </td>
      <td class="text-right">
        <small>$</small>{{ number_format($oCostoIndirecto->costo,2,".",",") }}
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="{{url('/costos_indirectos/editar/'.$oCostoIndirecto->id)}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_costo_indirecto('{{$oCostoIndirecto->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<script>
function eliminar_costo_indirecto(iId)
{
  $("#myModalLabel").html('Eliminar costo indirecto <h3 class="mt-0">' + $("#costosIndirecto" + iId ).data('info') + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( "costos_indirectos/destroy" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>' + $("#costosIndirecto" + iId).data('info') + '</strong>' +
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
