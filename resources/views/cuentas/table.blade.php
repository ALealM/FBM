@if($oCuentas->isEmpty())
  <div class="text-center">No hay registros para mostrar</div>
@else
<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
      <tr>
        <th>Nombre</th>
        <th>Cuenta</th>
        <th>Banco</th>
        <th>Monto</th>
        <th width="30"></th>
      </tr>
    </thead>
    <tbody>
      @foreach($oCuentas as $key => $oCuenta)
        @php
          $fTotal = $oCuenta->getTotal($oCuenta->id);
        @endphp
        <tr id="cuenta{{$oCuenta->id}}" data-info="{{ $oCuenta }}">
          <td>{{ $oCuenta->nombre }}</td>
          <td>{{ $oCuenta->numero }}</td>
          <td>{{ $oCuenta->banco }}</td>
          <td class="text-right text-{{( $fTotal > 0 ? 'success' : 'danger')}}">
            ${{ number_format( $oCuenta->getTotal($oCuenta->id) ,2,'.',',') }}
          </td>
          <td>

            <div class="btn-group" role="group">
              <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Acción
              </button>
              <div class="dropdown-menu">
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="edit_cuenta({{$oCuenta->id}})"><i class="fa fa-pencil mr-2"></i>Editar</a><br/>
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="ingresar_movimiento({{$oCuenta->id}},1)"><i class="fa fa-plus mr-2"></i>$ movimiento</a><br/>
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="ingresar_movimiento({{$oCuenta->id}},0)"><i class="fa fa-minus mr-2"></i>$ movimiento</a><br/>
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="ver_historial({{$oCuenta->id}})"><i class="fa fa-list mr-2"></i>Historial movimientos</a><br/>
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_cuenta({{$oCuenta->id}})"><i class="fa fa-times mr-2"></i>Eliminar</a><br/>
              </div>
            </div>

          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endif

<script>

function ver_historial(iId)
{
  var aCuenta = $("#cuenta" + iId).data('info');

  var sNombre = ( iId != 0 ? (aCuenta['nombre'] != null ? aCuenta['nombre'] : '') : '');
  var sNumero = ( iId != 0 ? (aCuenta['numero'] != null ? aCuenta['numero'] : '') : '');
  var sHtml = '<br><center>Ningún movimiento</center>';
  $("#myModalLabel").html('Historial de movimientos');

  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'GET',
    url:  '{{url('cuentas/historial_movimientos')}}',
    data: {'id' : iId},
    cache: false,
    dataType: "json",
    success: function (result) {
      if(result.estatus === 1){
        $("#myModalBody").html(
          '<div class="col-lg-12">' +
            '<strong>' + sNombre + '</strong><br>' +
            '<strong>' + sNumero + '</strong>' +
            '<div class="table-responsive">' +
            result.resultado.html +
            '</div>' +
            '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">' +
              '<div class="modal-body form-horizontal" style="padding-bottom: 0px; padding-top: 0px"><br>' +
                '<div class="text-center">' +
                  '<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-rotate-left mr-2"></i>Regresar</button>' +
                '</div>' +
              '</div>' +
            '</div>' +
          '</div>'
        );
        $("#myModal").modal();
        //generate_tables();
      }else{
        $("#myModalBody").html(
          '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">' +
            '<p>' + sNombre + ' - ' + sNumero + '</p>' +
            '<br><center>Error al consultar</center>' +
            '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">' +
              '<div class="modal-body form-horizontal" style="padding-bottom: 0px; padding-top: 0px"><br>' +
                '<div class="text-center">' +
                  '<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-rotate-left mr-2"></i>Regresar</button>' +
                '</div>' +
              '</div>' +
            '</div>' +
          '</div>'
        );
        $("#myModal").modal();
      }
    }
  });
}

function ingresar_movimiento(iId,iTipo)
{
  var aCuenta = $("#cuenta" + iId).data('info');

  var sNombre = ( iId != 0 ? (aCuenta['nombre'] != null ? aCuenta['nombre'] : '') : '');
  var sNumero = ( iId != 0 ? (aCuenta['numero'] != null ? aCuenta['numero'] : '') : '');

  $("#myModalLabel").html('Ingresar movimiento ' + ( iTipo == 1 ? 'de entrada' : 'de salida') );
  $("#myModalBody").html(
    '<form id="formModal" onsubmit="event.preventDefault();">' +
      '<p><strong>Cuenta: </strong>' + sNombre + ' - ' + sNumero + '</p>' +

      '<label>Fecha del movimiento</label>' +
      '<input id="fecha" name="fecha" class="input-sm form-control" type="date" required value="{{date('Y-m-d')}}"/>' +

      '<label>Concepto</label>' +
      '<input id="concepto" name="concepto" class="input-sm form-control" type="text" required  autocomplete="off" />' +

      '<label>Monto de ' + ( iTipo == 1 ? 'entrada' : 'salida') + ' ($)</label>' +
      '<input id="monto" name="monto" class="input-sm form-control" type="number" required  autocomplete="off" min=".01" step=".01" value="' + 0 + '" />' +

      '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">' +
        '<div class="modal-body form-horizontal" style="padding-bottom: 0px; padding-top: 0px"><br>' +
          '<div class="text-center">' +
            '<input id="id" name="id" type="hidden" value="' + iId + '" />' +
            '<input id="tipo" name="tipo" type="hidden" value="' + iTipo + '" />' +
            '<button class="btn btn-success btn-sm" onclick="guardar_movimiento('+iId+',' + iTipo + ')"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>' +
            '<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-rotate-left mr-2"></i>Cancelar</button>' +
          '</div>' +
        '</div>' +
      '</div>' +
    '</form>'
  );
  $("#myModal").modal();
}

function guardar_movimiento(iId, iTipo)
{
  if ( $("#formModal").valid() ) {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: 'PUT',
      url:  '{{url('cuentas/movimiento')}}',
      data: {
        id : iId,
        tipo : iTipo,
        fecha : $("#fecha").val(),
        concepto : $("#concepto").val(),
        monto : $("#monto").val()
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){
          $("#table_cuentas").html(result.resultado.html);
          generate_tables();
          $('#myModal').modal('toggle');
          notificacion('Movimiento exitoso',result.mensaje, 'success');
        }else{
          console.log("ERROR",result);
        }
      }
    });
  }
}

function edit_cuenta(iId)
{
  var aCuenta = $("#cuenta" + iId).data('info');

  var sNombre = ( iId != 0 ? (aCuenta['nombre'] != null ? aCuenta['nombre'] : '') : '');
  var sNumero = ( iId != 0 ? (aCuenta['numero'] != null ? aCuenta['numero'] : '') : '');
  var sDescripcion = ( iId != 0 ? (aCuenta['descripcion'] != null ? aCuenta['descripcion'] : '') : '');
  var sBanco = ( iId != 0 ? (aCuenta['banco'] != null ? aCuenta['banco'] : '') : '');


  $("#myModalLabel").html('Agregar cuenta');
  $("#myModalBody").html(
    '<form id="formModal" onsubmit="event.preventDefault();">' +
      '<label>Nombre</label>' +
      '<input id="nombre" name="nombre" class="input-sm form-control" type="text" required  autocomplete="off" value="' + sNombre + '" />' +
      '<label>Descripción</label>' +
      '<textarea id="descripcion" name="descripcion" class="input-sm form-control" type="text"  autocomplete="off" value="' + sDescripcion + '">' + sDescripcion + '</textarea>' +
      '<label>Banco</label>' +
      '<input id="banco" name="banco" class="input-sm form-control" type="text" required  autocomplete="off" value="' + sBanco + '" />' +
      '<label>Cuenta</label>' +
      '<input id="numero" name="numero" class="input-sm form-control" type="text" autocomplete="off" value="' + sNumero + '" />' +
      '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">' +
        '<div class="modal-body form-horizontal" style="padding-bottom: 0px; padding-top: 0px"><br>' +
          '<div class="text-center">' +
            '<input id="id" name="id" type="hidden" value="' + iId + '" />' +
            '<button class="btn btn-success btn-sm" onclick="guardar_cuenta('+iId+')"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>' +
            '<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-rotate-left mr-2"></i>Cancelar</button>' +
          '</div>' +
        '</div>' +
      '</div>' +
    '</form>'
  );
  $("#myModal").modal();
}

function guardar_cuenta(iId)
{
  if ( $("#formModal").valid() ) {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: ($("#id").val() == 0 ? 'POST' : 'PUT'),
      url:  ($("#id").val() == 0 ? '{{url('cuentas/store')}}' : '{{url('cuentas/update')}}'),
      data: {
        id : iId,
        nombre : $("#nombre").val(),
        descripcion : $("#descripcion").val(),
        banco : $("#banco").val(),
        numero : $("#numero").val()
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){
          $("#table_cuentas").html(result.resultado.html);
          generate_tables();
          $('#myModal').modal('toggle');
          notificacion('Guardado exitoso',result.mensaje, 'success');
        }else{
          console.log("ERROR",result);
        }
      }
    });
  }
}

function eliminar_cuenta(iId)
{
  var aCuenta = $("#cuenta" + iId).data('info');
  var sNombre = ( iId != 0 ? (aCuenta['nombre'] != null ? aCuenta['nombre'] : '') : '');

  $("#myModalLabel").html('Eliminar cuenta');
  $("#myModalBody").html(
    '<form id="formModal" onsubmit="event.preventDefault();">' +
      '<p>¿Deseas eliminar la cuenta <strong>' + sNombre + '</strong>?</p>' +
      '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">' +
        '<div class="modal-body form-horizontal" style="padding-bottom: 0px; padding-top: 0px"><br>' +
          '<div class="text-center">' +
            '<input id="id" name="id" type="hidden" value="' + iId + '" />' +
            '<button class="btn btn-danger btn-sm" onclick="destroy_cuenta('+iId+')"><i class="fa fa-times mr-2"></i>Eliminar</button>' +
            '<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-rotate-left mr-2"></i>Cancelar</button>' +
          '</div>' +
        '</div>' +
      '</div>' +
    '</form>'
  );
  $("#myModal").modal();
}

function destroy_cuenta(iId)
{
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'PUT',
    url:  '{{url('cuentas/destroy')}}',
    data: {
      id : iId
    },
    cache: false,
    dataType: "json",
    success: function (result) {
      if(result.estatus === 1){
        $("#table_cuentas").html(result.resultado.html);
        generate_tables();
        $('#myModal').modal('toggle');
        notificacion('Eliminación exitosa',result.mensaje, 'success');
      }else{
        console.log("ERROR",result);
      }
    }
  });
}

</script>
