<!--table class="table tile table-hover table-responsive dataTable" role='grid' id="data-table"-->
<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
<!--table class="table table-responsive table-striped table-hover table-bordered dataTable" role='grid' id="data-table"-->

  <thead>
    <th>Nombre</th>
    <th class="text-center">Precio venta</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oProductos as $oProducto)
    <tr id="producto{{ $oProducto->id }}" data-info="{{ $oProducto->nombre }}">
      <td>{{ $oProducto->producto }}</td>
      <td class="text-right"><small>$</small>{{ number_format($oProducto->precio_venta,2,".",",") }}</td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="{{url('/productos/editar/'.$oProducto->id)}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
            <a href="{{url('/productos/editar/'.$oProducto->id)}}#costos_por_producto" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-dollar mr-2"></i>Costos por producto </a>
            <a href="{{url('/productos/editar/'.$oProducto->id)}}#costos_indirectos" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-dollar mr-2"></i>Costos indirectos </a>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_producto('{{$oProducto->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<script>
function eliminar_producto(iId)
{
  $("#myModalLabel").html('Eliminar producto <h3 class="mt-0">' + $("#producto" + iId ).data('info') + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( "productos/destroy" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>' + $("#producto" + iId).data('info') + '</strong>' +
      '<p>El producto se eliminará y no habrá vuelta atrás.</p>' +
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
