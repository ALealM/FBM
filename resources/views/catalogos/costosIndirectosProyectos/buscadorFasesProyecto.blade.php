<div class="row">
  <label class="col-sm-2 col-form-label">Proyecto</label>
  <div class="col-sm-7">
    {!! Form::select('id_proyecto',$aProyectos,(@$oCostoIndirectoProyecto!=null ? $oCostoIndirectoProyecto->id_proyecto : null),['id'=>'id_proyecto','class'=>'form-control inputSlim','placeholder'=>'Seleccionar proyecto...','onchange'=>'cambio_proyecto()']) !!}
  </div>
</div>
<div id="divFases" class="row">
  <label class="col-sm-2 col-form-label">Fase</label>
  <div class="col-sm-7">
    {!! Form::select('id_fase',[],(@$oCostoIndirectoProyecto!=null ? $oCostoIndirectoProyecto->id_fase : null),['id'=>'id_fase','class'=>'form-control inputSlim','placeholder'=>'Seleccionar fase...']) !!}
  </div>
</div>
<script>
  $("#divFases").slideUp();
  cambio_proyecto({{@$oCostoIndirectoProyecto->id_fase}});
  function cambio_proyecto(iIdFase)
  {
    if ( $("#id_proyecto").val() > 0 ) {
      $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: "GET",
        url: "{{ asset ('proyectos/get_fases') }}",
        data: {
          'id_proyecto' : $("#id_proyecto").val(),
        },
        cache: false,
        dataType: "json",
        success: function (result) {
          if(result.estatus === 1){
            $("#id_fase").html('');
            var aFase = [];
            for (var i in result.resultado) {
              aFase = result.resultado[i];
              $("#id_fase").append('<option value="'+aFase['id']+'" '+(iIdFase == aFase['id'] ? 'selected' : '' )+'>'+aFase['nombre']+'</option>');
            }
            $("#divFases").slideDown();
          }else {
            notificacion('Alerta',result.mensaje,'error');
          }
        },
        error: function (result) {
          console.log("error");
        }
      });
    }else {
      $("#id_fase").html('');
      $("#divFases").slideUp();
    }
  }
</script>
