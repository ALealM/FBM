
<!--table class="table table-responsive table-striped table-hover table-bordered dataTable" role='grid' id="data-table"-->
<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">

  <thead>
    <tr>
      <th colspan="3" style="text-align: center">MOD Nómina</th>
      <th colspan="4" style="text-align: center">Materia prima directa</th>
      <th colspan="4" style="text-align: center">Materia prima indirecta</th>
      <th colspan="2" style="text-align: center">Otros</th>
      <th style="text-align: center"></th>
    </tr>
    <tr>
      <th>Contratación</th>
      <th>$</th>
      <th>Duración</th>
      <th>Concepto</th>
      <th>Unidad</th>
      <th>Volumen</th>
      <th>$</th>
      <th>Concepto</th>
      <th>Unidad</th>
      <th>Volumen</th>
      <th>$</th>
      <th>Concepto</th>
      <th>$</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @if( @$oVariables != null )
    @foreach($oVariables as $oVariable)
    <tr id="variable{{ $oVariable->id }}" data-info="{{ $oVariable->contratacion() }} ${{ number_format($oVariable->precio_contrato,2,".",",") }} {{ $oVariable->duracion_contrato }} meses">
      <td>
        {{ $oVariable->contratacion() }}
      </td>
      <td >
        <small>$</small>{{ number_format($oVariable->precio_contrato,2,".",",") }}
      </td>
      <td >
        {{ $oVariable->duracion_contrato }}&nbsp;&nbsp;<small>Meses</small>
      </td>
      <td >
        {{ $oVariable->concepto_directa }}
      </td>
      <td >
        {{ $oVariable->unidadDir() }}
      </td>
      <td >
        {{ number_format($oVariable->volumen_directa) }}
      </td>
      <td >
        <small>$</small>{{ number_format($oVariable->precio_directa,2,".",",") }}
      </td>
      <td >
        {{ $oVariable->concepto_indirecta }}
      </td>
      <td >
        {{ $oVariable->unidadInd() }}
      </td>
      <td >
        {{ number_format($oVariable->volumen_indirecta) }}
      </td>
      <td >
        <small>$</small>{{ number_format($oVariable->precio_indirecta,2,".",",") }}
      </td>
      <td >
        {{ $oVariable->concepto_otros }}
      </td>
      <td>
        <small>$</small>{{ number_format($oVariable->precio_otros,2,".",",") }}
      </td>
      <td>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="actualizar_costo_variable('{{$oVariable->id}}')"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_costo_variable('{{$oVariable->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
    @endif
  </tbody>
</table>

<script>
function actualizar_costo_variable(iId)
{
  $("#myModalLabel").html('Actualizar costo variable al proyecto <br/><h3 class="mt-0">' + $("#nombre").val() + '</h3>');
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "GET",
    url: "{{ asset ('proyectos/edit_costo_variable') }}/" + "{{@$oProyecto->id}}" + '/' + iId,
    data: [],
    cache: false,
    dataType: "json",
    success: function (result) {
      if(result.estatus === 1){
        $("#myModalBody").html( result.resultado );

      }else{
        $("#myModalBody").html('<div class="text-center">Error al agregar</div>');
      }
      $("#myModal").modal();
    },
    error: function (result) {
      console.log("error");
    }
  });
}

function eliminar_costo_variable(iId)
{
  $("#myModalLabel").html('Eliminar costo variable al proyecto<h3 class="mt-0">' + $("#nombre").val() + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( "proyectos/destroy_costo_variable" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>' + $("#variable" + iId).data('info') + '</strong>' +
      '<p>El costo variable se eliminará y no habrá vuelta atrás.</p>' +
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
