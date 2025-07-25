
<script>
function eliminar_cfdi(iId)
{
  var sDescripcion = $("#cfdi" + iId ).data('descripcion');
  $("#myModalLabel").html('Cancelar CFDI <h3 class="mt-0">' + sDescripcion + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="POST" action="{{url( "facturama/delete_cfdi" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '{{ method_field('DELETE') }}' +
      '<p>El CFDI se cancelará con el procedimiento de Facturama.</p>' +
      '<div class="card-footer text-center">' +
        '<input class="d-none" name="cfdi_id" value="' + iId +'"/>' +
        '<input class="d-none" name="type" value="issued"/>' +
        '<button type="submit" form="form2" class="btn btn-danger btn-sm"><i class="fa fa-times mr-2"></i>Cancelar CFDI</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>' +
      '</div>' +
    '</form>'
  );
  $("#myModal").modal();
}
</script>
