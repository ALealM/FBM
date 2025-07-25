<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th class="text-center">Concepto</th>
    <th class="text-center">Medida</th>
    <th class="text-center">Unidades por medida</th>
    <th class="text-center">Costo</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oMateriasPrima as $oMateriaPrima)
    <tr id="materiaPrima{{$oMateriaPrima->id}}" data-info="{{$oMateriaPrima->concepto}} con {{ number_format($oMateriaPrima->unidades) }} unidades">
      <td>
        {{ $oMateriaPrima->concepto }}
      </td>
      <td class="text-center">
        {{ $oMateriaPrima->medida()->medida }}
      </td>
      <td class="text-center">
        {{ number_format($oMateriaPrima->unidades) }}
      </td>
      <td class="text-right">
        <small>$</small>{{ number_format($oMateriaPrima->costo,2,".",",") }}
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="{{url('/materia_prima/editar/'.$oMateriaPrima->id)}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_materia_prima('{{$oMateriaPrima->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<script>
function eliminar_materia_prima(iId)
{
  $("#myModalLabel").html('Eliminar la materia prima <h3 class="mt-0">' + $("#materiaPrima" + iId ).data('info') + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( "materia_prima/destroy" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>' + $("#materiaPrima" + iId).data('info') + '</strong>' +
      '<p>La materia prima se eliminará y no habrá vuelta atrás.</p>' +
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
