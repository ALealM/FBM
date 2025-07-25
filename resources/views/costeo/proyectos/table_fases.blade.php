<table class="table table-hover table-fixed {{($sTipoVista == 'print' ? '' : 'table-bordered')}}" role='grid' id="data-table">
<!--table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table"-->
  <tbody>
    @php
      $fTotalValor = 0;
    @endphp
    @if ($sTipoVista == 'print')
      <tr>
        <td class="bg-white" colspan="8" style="border: none;"></td>
      </tr>
      <tr>
        <td class="bg-white" colspan="8" style="border: none;"></td>
      </tr>
      <tr>
        <th class="text-center">Responsable</th>
        <th class="text-center">Disponibilidad</th>
        <th class="text-center">Valor hora</th>
        <th class="text-center">Fecha inicio</th>
        <th class="text-center">Fecha fin</th>
        <th class="text-center">Días</th>
        <th class="text-center">Horas</th>
        <th class="text-center">Valor</th>
      </tr>
    @endif
    @foreach($oFases as $key => $oFase)
      @php
      $oRoles = $oFase->roles();
      $oFase->numero_roles = $oRoles->count();
      $fTotalFase = 0;
      $oFase->dias = round( ((strtotime($oFase->fecha_fin)-strtotime($oFase->fecha_inicio))/86400) ) + 1;
      $oFase->horas_trabajo = 0;
      $oFase->valor = 0;
      @endphp
      @if ($sTipoVista != 'print')
        <tr>
          <td class="" colspan="8"></td>
        </tr>
      @endif
      <tr id="fase{{$oFase->id}}" class="p-0 fases" data-info="{{$oFase}}">
        <td class="text-center bg-fbm-blue p-0" colspan="8" style="border: none;">
          @if ($sTipoVista == 'print')
            <h4 class="mb-0"><strong>FASE {{ $key + 1 }}. {{ $oFase->nombre }}</strong></h4>
            Del {{ date('d/m/Y',strtotime($oFase->fecha_inicio))}} al {{ date('d/m/Y',strtotime($oFase->fecha_fin))}}
          @else
            <div class="btn-group m-0" role="group">
              <a href="javascript:;" class="text-white dropdown-toggle dropdown-menu-bottom" data-toggle="dropdown" aria-expanded="false"><strong>FASE {{ $key + 1 }}. {{ $oFase->nombre }}</strong></a>
              <div class="dropdown-menu">
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="agregar_editar_fase({{$oFase->id}})"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="agregar_editar_rol(0,{{$oFase->id}})"><i class="fa fa-plus mr-2"></i>Agregar área</a><br>
                <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_elemento({{$oFase->id}},'fase')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
              </div>
            </div>
          @endif

        </td>
      </tr>
      @if ($sTipoVista != 'print')
        <tr class="p-0">
          <th class="p-0 text-center" style="font-size:70%;">Responsable</th>
          <th class="p-0 text-center" style="font-size:70%;">Disponibilidad</th>
          <th class="p-0 text-center" style="font-size:70%;">Valor hora</th>
          <th class="p-0 text-center" style="font-size:70%;">Fecha inicio</th>
          <th class="p-0 text-center" style="font-size:70%;">Fecha fin</th>
          <th class="p-0 text-center" style="font-size:70%;">Días</th>
          <th class="p-0 text-center" style="font-size:70%;">Horas</th>
          <th class="p-0 text-center" style="font-size:70%;">Valor</th>
        </tr>
      @endif

      @foreach ($oRoles as $oRol)
        @php
        $oParticipantes = $oRol->participantes();
        @endphp
        <tr class="p-0 bg-light">
          <td id="rol{{$oRol->id}}" class="p-0" colspan="8" data-info="{{$oRol}}" style="border: none;">
            @if ($sTipoVista == 'print')
              <div class="mt-2"><strong>ÁREA: {{ $oRol->nombre }}</strong></div>
            @else
              <div class="btn-group mb-0" role="group">
                <a href="javascript:;" class="color-fbm-blue dropdown-toggle dropdown-menu-bottom" data-toggle="dropdown" aria-expanded="false"><strong>ÁREA: {{ $oRol->nombre }}</strong></a>
                <div class="dropdown-menu">
                  <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="agregar_editar_rol({{$oRol->id}},{{$oFase->id}})"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
                  <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="agregar_editar_participante(0,{{$oRol->id}},{{$oFase->id}})"><i class="fa fa-plus mr-2"></i>Agregar responsable</a><br>
                  <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_elemento({{$oRol->id}},'rol')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
                </div>
              </div>
            @endif
          </td>
        </tr>
        <!--tr class="p-0">
          <th class="p-0 text-center" style="font-size:70%;">Responsable</th>
          <th class="p-0 text-center" style="font-size:70%;">Disponibilidad</th>
          <th class="p-0 text-center" style="font-size:70%;">Valor hora</th>
          <th class="p-0 text-center" style="font-size:70%;">Fecha inicio</th>
          <th class="p-0 text-center" style="font-size:70%;">Fecha fin</th>
          <th class="p-0 text-center" style="font-size:70%;">Días</th>
          <th class="p-0 text-center" style="font-size:70%;">Horas</th>
          <th class="p-0 text-center" style="font-size:70%;">Valor</th>
        </tr-->

          @foreach ($oParticipantes as $oParticipante)
            <tr class="p-0">
              <td id="participante{{$oParticipante->id}}" class="p-0" data-info="{{$oParticipante}}">
                @if ($sTipoVista == 'print')
                  {{ $oParticipante->oManoObra->nombre }}
                @else
                  <div class="btn-group m-0" role="group">
                    <a href="javascript:;" class="color-fbm-blue dropdown-toggle dropdown-menu-bottom" data-toggle="dropdown" aria-expanded="false">{{ $oParticipante->oManoObra->nombre }}</a>
                    <div class="dropdown-menu">
                      <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="agregar_editar_participante({{$oParticipante->id}},{{$oRol->id}},{{$oFase->id}})"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
                      <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_elemento({{$oParticipante->id}},'participante')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
                    </div>
                  </div>
                @endif
              </td>
              <td class="text-center p-0">
                {{ floatval($oParticipante->disponibilidad) }}%
              </td>
              <td class="text-right p-0">
                ${{ number_format($oParticipante->valor_hora,2,'.',',') }}
              </td>
              <td class="text-center p-0">
                {{ date('d/m/Y',strtotime($oParticipante->fecha_inicio)) }}
              </td>
              <td class="text-center p-0">
                {{ date('d/m/Y',strtotime($oParticipante->fecha_fin)) }}
              </td>
              <td class="text-center p-0">
                {{$oParticipante->dias}}
              </td>
              <td class="text-center p-0">
                {{$oParticipante->horas_trabajo}}
              </td>
              <td class="text-right p-0">
                @php
                  $fTotalFase += $oParticipante->valor;

                  $oFase->horas_trabajo += $oParticipante->horas_trabajo;
                  $oFase->valor += $oParticipante->valor;
                @endphp
                ${{ number_format($oParticipante->valor,2,'.',',')}}
              </td>
            </tr>
          @endforeach
      @endforeach
      <tr class="p-0">
        <td class="p-0" colspan="7" style="border: none;"></td>
        @php
          $fTotalValor += $fTotalFase;
        @endphp
        <td id="faseTOTAL{{$oFase->id}}" class="p-0 text-right {{($sTipoVista=='print' ? '' : 'text-white bg-fbm-blue')}}" data-info="{{$oFase}}">
          <strong>SUBTOTAL: ${{number_format($fTotalFase,2,'.',',')}}</strong>
        </td>
      </tr>
    @endforeach
    <input id="total_valor" class="d-none" value="{{$fTotalValor}}"/>
  </tbody>
</table>
<script>
function agregar_editar_fase(iId)
{
  var sNombre = (iId==0 ? '' : $("#fase" + iId ).data('info')['nombre'] );
  var dateFechaInicio = (iId==0 ? '' : $("#fase" + iId ).data('info')['fecha_inicio'] );
  var dateFechaFin = (iId==0 ? '' : $("#fase" + iId ).data('info')['fecha_fin'] );

  $("#myModalLabel").html((iId == 0 ? 'Nueva fase' : 'Editar fase <h3 class="mt-0">' + sNombre + '</h3>' ));
  $("#myModalBody").html(
    '<form id="formModal" onsubmit="event.preventDefault();">' +
      '<div class="row">' +
        '<label class="col-sm-2 col-form-label">Nombre</label>' +
        '<div class="col-sm-12">' +
          '{!! Form::text('nombre_fase',null,['id' => 'nombre_fase', 'class'=>'form-control inputSlim','required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Fecha de inicio</label>' +
        '<div class="col-12">' +
          '{!! Form::date('fecha_inicio_fase',null,['id'=>'fecha_inicio_fase','class'=>'form-control inputSlim','required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Fecha de finalización</label>' +
        '<div class="col-12">' +
          '{!! Form::date('fecha_fin_fase',null,['id'=>'fecha_fin_fase','class'=>'form-control inputSlim','required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="card-footer text-center">' +
        '<input id="id_fase" class="d-none" name="id_fase" value="' + iId +'"/>' +
        '<button class="btn btn-success btn-sm" onclick="guardar_fase('+iId+')"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</form>'
  );
  $("#nombre_fase").val(sNombre);
  $("#fecha_inicio_fase").val(dateFechaInicio);
  $("#fecha_fin_fase").val(dateFechaFin);
  $("#myModal").modal();
}

function guardar_fase(iId)
{
  //$("#formModal").validate();
  if ( $("#formModal").valid() ) {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: (iId==0 ? "POST" : "PUT"),
      url: (iId==0 ? "{{ asset ('proyectos/store_fase') }}" : "{{ asset ('proyectos/update_fase') }}" ),
      data: {
        id: iId,
        nombre: $("#nombre_fase").val(),
        id_proyecto: $("#id_proyecto").val(),
        fecha_inicio: $("#fecha_inicio_fase").val(),
        fecha_fin: $("#fecha_fin_fase").val()
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){
          notificacion( 'Fase guardada','Los datos de la fase han sido guardados.','success');
          $("#table_fases").html( result.resultado );
          calcular_totales();
          $('#myModal').modal('toggle');
          //$("#myModal").modal();
        }else {
          notificacion( 'Error al guardar',result.mensaje,'danger');
        }
      },error: function (result) {console.log("error");}
    });
  }
}

function agregar_editar_rol(iId,iIdFase)
{
  var sNombre = (iId==0 ? '' : $("#rol" + iId ).data('info')['nombre'] );
  var sNombreFase = $("#fase" + iIdFase ).data('info')['nombre'];

  $("#myModalLabel").html((iId == 0 ? 'Nueva área' : 'Editar área <h3 class="mt-0">' + sNombre + '</h3>Fase: ' + sNombreFase ));
  $("#myModalBody").html(
    '<form id="formModal" onsubmit="event.preventDefault();">' +
      '<div class="row">' +
        '<label class="col-sm-2 col-form-label">Nombre</label>' +
        '<div class="col-sm-12">' +
          '{!! Form::text('nombre_rol',null,['id' => 'nombre_rol', 'class'=>'form-control inputSlim','required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="card-footer text-center">' +
        '<input id="id_fase" class="d-none" name="id_fase" value="' + iIdFase +'"/>' +
        '<input id="id_rol" class="d-none" name="id_fase" value="' + iId +'"/>' +
        '<button class="btn btn-success btn-sm" onclick="guardar_rol('+iId+','+iIdFase+')"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</form>'
  );
  $("#nombre_rol").val(sNombre);
  $("#myModal").modal();
}

function guardar_rol(iId,iIdFase)
{
  //$("#formModal").validate();
  if ( $("#formModal").valid() ) {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: (iId==0 ? "POST" : "PUT"),
      url: (iId==0 ? "{{ asset ('proyectos/store_rol') }}" : "{{ asset ('proyectos/update_rol') }}" ),
      data: {
        id: iId,
        nombre: $("#nombre_rol").val(),
        id_fase: $("#id_fase").val(),
        id_proyecto: $("#id_proyecto").val()
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){
          notificacion( 'Área guardada','Los datos del área han sido guardados.','success');
          $("#table_fases").html( result.resultado );
          calcular_totales();
          $('#myModal').modal('toggle');
          //$("#myModal").modal();
        }else {
          notificacion( 'Error al guardar',result.mensaje,'danger');
        }
      },error: function (result) {console.log("error");}
    });
  }
}

function agregar_editar_participante(iId,iIdRol,iIdFase)
{
  var sNombre = (iId==0 ? '' : $("#participante" + iId ).data('info')['oManoObra']['nombre'] );
  var iIdManoObra = (iId==0 ? '' : $("#participante" + iId ).data('info')['id_mano_obra'] );
  var iDisponibilidad = (iId==0 ? 0 : $("#participante" + iId ).data('info')['disponibilidad'] );
  var dateFechaInicio = (iId==0 ? 0 : $("#participante" + iId ).data('info')['fecha_inicio'] );
  var dateFechaFin = (iId==0 ? 0 : $("#participante" + iId ).data('info')['fecha_fin'] );
  var sNombreFase = $("#fase" + iIdFase ).data('info')['nombre'];
  var sNombreRol = $("#rol" + iIdRol ).data('info')['nombre'];

  $("#myModalLabel").html((iId == 0 ? 'Agregar responsable' : 'Editar responsable <h3 class="mt-0">' + sNombre + '</h3>') + '<br>Fase: ' + sNombreFase + '<br>Área: ' + sNombreRol);
  $("#myModalBody").html(
    '<form id="formModal" onsubmit="event.preventDefault();">' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Responsable</label>' +
        '<div class="col-12">' +
          '{!! Form::select('id_mano_obra',app('App\Http\Controllers\ProyectosController')->get_mano_obra_disponible()->pluck('nombre','id'),null,['id'=>'id_mano_obra','placeholder'=>'Seleccione al responsable...','class'=>'form-control','required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Disponibilidad <small>(%)</small></label>' +
        '<div class="col-12">' +
          '{!! Form::number('disponibilidad',null,['id'=>'disponibilidad','class'=>'form-control inputSlim','min'=> 1,'max'=>100,'step'=>.01,'required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Fecha de inicio</label>' +
        '<div class="col-12">' +
          '{!! Form::date('fecha_inicio_participante',null,['id'=>'fecha_inicio_participante','class'=>'form-control inputSlim','required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Fecha de finalización</label>' +
        '<div class="col-12">' +
          '{!! Form::date('fecha_fin_participante',null,['id'=>'fecha_fin_participante','class'=>'form-control inputSlim','required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="card-footer text-center">' +
        '<input id="id_fase" class="d-none" name="id_fase" value="' + iIdFase +'"/>' +
        '<input id="id_rol" class="d-none" name="id_fase" value="' + iIdRol +'"/>' +
        '<button class="btn btn-success btn-sm" onclick="guardar_participante('+iId+','+iIdRol+','+iIdFase+')"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</form>'
  );
  $("#id_mano_obra").val(iIdManoObra);
  $("#disponibilidad").val(iDisponibilidad);
  $("#fecha_inicio_participante").val(dateFechaInicio);
  $("#fecha_fin_participante").val(dateFechaFin);
  $("#myModal").modal();
}

function guardar_participante(iId,iIdRol,iIdFase)
{
  //$.validator.messages.required = '';
  if ( $("#formModal").valid() ) {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: (iId==0 ? "POST" : "PUT"),
      url: (iId==0 ? "{{ asset ('proyectos/store_participante') }}" : "{{ asset ('proyectos/update_participante') }}" ),
      data: {
        id: iId,
        id_mano_obra: $("#id_mano_obra").val(),
        disponibilidad: $("#disponibilidad").val(),
        fecha_inicio: $("#fecha_inicio_participante").val(),
        fecha_fin: $("#fecha_fin_participante").val(),
        id_rol: iIdRol,
        id_fase: iIdFase,
        id_proyecto: $("#id_proyecto").val()
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){
          notificacion( 'Responsable guardado','Los datos del responsable han sido guardados.','success');
          $("#table_fases").html( result.resultado );
          calcular_totales();
          $('#myModal').modal('toggle');
          //$("#myModal").modal();
        }else {
          notificacion( 'Error al guardar',result.mensaje,'danger');
        }
      },error: function (result) {console.log("error");}
    });
  }
}
function eliminar_elemento(iId,sTipo)
{
  console.log(iId,sTipo);
  var sNombre = $("#" + sTipo + iId ).data('info')['nombre'] ;

  $("#myModalLabel").html('Eliminar <h3 class="mt-0">' + sNombre + '</h3>');
  $("#myModalBody").html(
    '<form id="formModal" onsubmit="event.preventDefault();">' +
      '<p>¿Deseas eliminar <strong>' + sNombre + '</strong>? <br>No habrá vuelta atrás.</p>' +
      '<div class="card-footer text-center">' +
        '<button class="btn btn-success btn-sm" onclick="destroy_elemento('+iId+',\''+sTipo+'\')"><i class="fa fa-times mr-2"></i>Eliminar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</form>'
  );
  $("#myModal").modal();
}

function destroy_elemento(iId,sTipo)
{
  //$("#formModal").validate();
  if ( $("#formModal").valid() ) {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "DELETE",
      url: "{{ asset ('proyectos/destroy_') }}" + sTipo,
      data: {
        id: iId,
        id_proyecto: $("#id_proyecto").val()
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){
          notificacion( 'Elemento eliminado','Ha sido eliminado exitosamente.','success');
          $("#table_fases").html( result.resultado );
          calcular_totales();
          $('#myModal').modal('toggle');
          //$("#myModal").modal();
        }else {
          notificacion( 'Error al guardar',result.mensaje,'danger');
        }
      },error: function (result) {console.log("error");}
    });
  }
}
</script>
