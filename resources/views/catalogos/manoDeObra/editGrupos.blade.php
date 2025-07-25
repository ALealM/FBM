@foreach ($oManoObraGrupos as $key => $oGrupo)
  <div id="grupo{{$oGrupo->id}}" class="row">
    <div class="col-7">
      {!! Form::text('nombre' . $oGrupo->id,$oGrupo->nombre,['id'=>'nombre'.$oGrupo->id,'class'=>'form-control inputSlim','required','placeholder'=>'Nombre del grupo']) !!}
    </div>
    <div class="col-5">
      <a onclick="guardar({{$oGrupo->id}})" class="btn btn-success btn-sm ml-auto mr-auto text-white"><i class="fa fa-save"></i></a>
      <a onclick="eliminar({{$oGrupo->id}})" class="btn btn-danger btn-sm ml-auto mr-auto text-white"><i class="fa fa-times"></i></a>
    </div>
  </div>
@endforeach
<div id="grupo0" class="row">
  <div class="col-7">
    {!! Form::text('nombre0',null,['id'=>'nombre0','class'=>'form-control inputSlim','required','focus','placeholder'=>'Nuevo grupo']) !!}
  </div>
  <div class="col-3">
    <a onclick="guardar(0)" class="btn btn-success btn-sm ml-auto mr-auto text-white"><i class="fa fa-save"></i></a>
  </div>
</div>

<div class="card-footer mt-3">
  <center>
    <a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>

  </center>
</div>

<script>
function guardar(iIdManoObraGrupo)
{
  if ($("#nombre"+iIdManoObraGrupo).val() == '') {
    notificacion( 'Alerta','Ingresa el nombre del grupo.','warning');
    return false;
  }
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "POST",
    url: "{{ asset ('mano_de_obra/store_update_grupos') }}",
    data: {
      'id' : iIdManoObraGrupo,
      'nombre' : $("#nombre"+iIdManoObraGrupo).val()
    },
    cache: false,
    dataType: "json",
    success: function (result) {
      notificacion( (result.estatus === 1 ? 'Guardado exitoso!' : 'Alerta'),result.mensaje, (result.estatus === 1 ? 'success' : 'error'));
      if(result.estatus === 1){
        $("#myModalBody").html( result.resultado );
      }
    },
    error: function (result) {
      console.log("error");
    }
  });
}

function eliminar(iIdManoObraGrupo)
{
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "DELETE",
    url: "{{ asset ('mano_de_obra/destroy_grupos') }}",
    data: {
      'id' : iIdManoObraGrupo,
    },
    cache: false,
    dataType: "json",
    success: function (result) {
      notificacion( (result.estatus === 1 ? 'Eliminado!' : 'Alerta'),result.mensaje, (result.estatus === 1 ? 'success' : 'error'));
      if(result.estatus === 1){
        $("#myModalBody").html( result.resultado );
      }
    },
    error: function (result) {
      console.log("error");
    }
  });
}
</script>
