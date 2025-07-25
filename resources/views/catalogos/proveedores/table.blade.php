<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th class="text-center">Nombre</th>
    <th class="text-center">Dirección</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oProveedores as $oProveedor)
    <tr id="proveedor{{$oProveedor->id}}" data-info="{{$oProveedor->nombre}} {{$oProveedor->direccion}}">
      <td>
        {{ $oProveedor->nombre }}
      </td>
      <td>
        {{$oProveedor->direccion}}
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="{{url('/proveedores/editar/'.$oProveedor->id)}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_proveedor('{{$oProveedor->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
<script>
function eliminar_proveedor(iId)
{
  $("#myModalLabel").html('Eliminar proveedor <h3 class="mt-0">' + $("#proveedor" + iId ).data('info') + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( "proveedores/destroy" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>' + $("#proveedor" + iId).data('info') + '</strong>' +
      '<p>El proveedor se eliminará y no habrá vuelta atrás.</p>' +
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
