<!--table class="table tile table-hover table-responsive dataTable" role='grid' id="data-table"-->
<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <!--table class="table table-responsive table-striped table-hover table-bordered dataTable" role='grid' id="data-table"-->
  <thead>
    <th>#</th>
    <th>Asunto</th>
    <th>Descripción</th>
    <th>Prioridad</th>
    <th>Fecha</th>
    <th>Estado</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oTickets as $key => $oTicket)
      <tr id="ticket{{ $oTicket->id }}" data-info="{{ $oTicket }}">
        <td>{{ $oTicket->id }}</td>
        <td class="">{{ $oTicket->asunto }}</td>
        <td class="">{{ substr($oTicket->descripcion, 0,20) }}...</td>
        <td>
          @if ($oTicket->prioridad == 0)
            Baja
          @elseif ( $oTicket->prioridad == 1)
            Media
          @elseif ($oTicket->prioridad > 1)
            <span class="text-danger">Alta</span>
          @endif
        </td>
        <td class="">{{ date('d/m/Y',strtotime($oTicket->fecha_registro)) }}</td>
        <td class="">
          @if ($oTicket->estado == 1)
            <span class="text-success">Nuevo</span>
          @elseif ( $oTicket->estado == 2)
            <span class="text-warning">Abierto</span>
          @elseif ($oTicket->estado == 3)
            Cancelado
          @elseif ($oTicket->estado == 4)
            <span class="text-warning">En espera</span>
          @elseif ($oTicket->estado == 5)
            Sin solución
          @elseif ($oTicket->estado == 6)
            Solucionado y cerrado
          @endif
        </td>
        <td class="text-center">
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              Acción
            </button>
            <div class="dropdown-menu">
              <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="detalle_ticket('{{$oTicket->id}}',1)"><i class="fa fa-pencil mr-2"></i>Detalle/Bitácora</a><br>
              @if ($oTicket->estado == 1)
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="detalle_ticket('{{$oTicket->id}}',3)"><i class="fa fa-lock mr-2"></i>Cancelar</a><br>
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="detalle_ticket('{{$oTicket->id}}',0)"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
              @endif
            </div>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

<script>
function detalle_ticket(iId,iEstado)
{
  var sTipo = ( iId == 0 ? 'problema' : $("#ticket" + iId ).data('info')['tipo'] );
  var iPrioridad = ( iId == 0 ? 0 : $("#ticket" + iId ).data('info')['prioridad'] );
  var sAsunto = ( iId == 0 ? '' : $("#ticket" + iId ).data('info')['asunto'] );
  var sDescripcion = ( iId == 0 ? '' : $("#ticket" + iId ).data('info')['descripcion'] );
  var sTipo = ( iId == 0 ? '' : $("#ticket" + iId ).data('info')['tipo'] );
  var sBitacora = ( iId == 0 ? '' : ($("#ticket" + iId ).data('info')['bitacora'] == null ? '' : $("#ticket" + iId ).data('info')['bitacora'] ) );
  sBitacora = sBitacora.replace(/\[/g, '<br><strong>[').replace(/\]/g,']</strong>');

  $("#myModalLabel").html( ( iId == 0 ? 'Nuevo ticket' : 'Ticket <strong>#' + iId + '</strong><br>Asunto: ' + sAsunto ));
  $("#myModalBody").html(
    '<form id="form" method="GET" action="' + ( iId == 0 ? "{{url("soporte_servicios/tickets/tickets_store")}}" : "{{url("soporte_servicios/tickets/tickets_update")}}" ) + '" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
    '{{ csrf_field() }}' +
    '<div class="row">' +
      '<div class="col-lg-6 row">' +
        '<label class="col-12 col-form-label">Tipo</label>' +
        '<div class="col-12">' +
          ( iId == 0 ? '{!! Form::select('tipo',$aTipos,null,['id'=>'tipo','class'=>'form-control inputSlim','required']) !!}'
          : '{!! Form::select('tipo',$aTipos,null,['id'=>'tipo','class'=>'form-control inputSlim','disabled']) !!}' ) +
        '</div>' +
      '</div>' +
      '<div class="col-lg-6 row">' +
        '<label class="col-12 col-form-label">Prioridad</label>' +
        '<div class="col-12">' +
          ( iId == 0 ? '{!! Form::select('prioridad',$aPrioridades,null,['id'=>'prioridad','class'=>'form-control inputSlim','required']) !!}'
          : '{!! Form::select('prioridad',$aPrioridades,null,['id'=>'prioridad','class'=>'form-control inputSlim','disabled']) !!}' ) +
        '</div>' +
      '</div>' +
    '</div>' +
    '<div class="row">' +
      '<label class="col-12 col-form-label">Asunto</label>' +
      '<div class="col-12">' +
        ( iId == 0 ? '{!! Form::text('asunto',null,['id'=>'asunto','class'=>'form-control inputSlim','required']) !!}'
        : '{!! Form::text('asunto',null,['id'=>'asunto','class'=>'form-control inputSlim','disabled']) !!}' ) +
      '</div>' +
    '</div>' +
    '<div class="row">' +
      '<label class="col-12 col-form-label">Descripción</label>' +
      '<div class="col-12">' +
        ( iId == 0 ? '{!! Form::textarea('descripcion', null, ['id'=>'descripcion','class'=>'form-control','required','rows' => 4 ]) !!}'
        : '{!! Form::textarea('descripcion', null, ['id'=>'descripcion','class'=>'form-control','disabled','rows' => 4 ]) !!}' ) +
      '</div>' +
    '</div>' +
    (sBitacora != '' ?
      '<div class="row">' +
        '<label class="col-12 col-form-label">Bitácora de respuestas</label>' +
        '<div class="col-12">' +
          '<div style="height:150px; overflow: scroll; background-color:#f8f9fa">' + sBitacora + '</div>' +
        '</div>' +
      '</div>'
      : ''
    ) +
    ((iEstado == 1 && iId != 0) ?
      '<div class="row">' +
        '<label class="col-12 col-form-label">Respuesta a bitácora</label>' +
        '<div class="col-12">' +
          '{!! Form::textarea('bitacora', null, ['id'=>'bitacora','class'=>'form-control','required','rows' => 2 ]) !!}' +
        '</div>' +
      '</div>'
      : ''
    ) +
    '<div class="card-footer text-center">' +
    '<input id="id" class="d-none" name="id" value="' + iId +'"/>' +
    '<input id="estado_nuevo" class="d-none" name="estado_nuevo" value="'+ iEstado +'"/>' +
    ( iEstado == 1 ? '<button type="submit" form="form" class="btn btn-success btn-sm" ><i class="fa fa-floppy-o mr-2"></i>Guardar</button>' : '' ) +
    ( iEstado == 3 ? '<button type="submit" form="form" class="btn btn-danger btn-sm" ><i class="fa fa-lock mr-2"></i>Cancelar ticket</button>' : '' ) +
    ( iEstado == 0 ? '<button type="submit" form="form" class="btn btn-danger btn-sm" ><i class="fa fa-times mr-2"></i>Eliminar</button>' : '' ) +
    '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
    '</div>' +
    '</form>'
  );
  $("#myModal").modal();
  $("#tipo").val(sTipo);
  $("#prioridad").val(iPrioridad);
  $("#asunto").val(sAsunto);
  $("#descripcion").val(sDescripcion);

}
</script>
