@if( @count($oCostosIndirectos) > 0 )
  <div class="table-responsive pb-4">
    <table class="table table-hover {{$sTipoVista == 'print' ? 'table-fixed' : 'table-striped'}}">
      <thead>
        <tr>
          <th class="text-center p-0">Costo indirecto</th>
          <th class="text-center p-0">Medida</th>
          <th class="text-center p-0">Costo</th>
          <th class="text-center p-0">Unidades</th>
          <th class="text-center p-0">Costo por {{$sTipoCosto}}</th>
          @if ($sTipoCosto == 'proyecto')
            <th class="text-center p-0">Estado</th>
          @endif
          @if ($sTipoVista != 'print')
            <th></th>
          @endif
        </tr>
      </thead>
      <tbody>
        @php
          $fTotalIndirectos = 0;
        @endphp
        @foreach($oCostosIndirectos as $oCostoIndirecto)
          <!--php( $oCostoIndirecto->costo_total = (($oCostoIndirecto->unidades / $oCostoIndirecto->indirecto_unidades) * $oCostoIndirecto->costo) )-->
          @php
            $oCostoIndirecto->costo_total = (($oCostoIndirecto->unidades / $oCostoIndirecto->indirecto_unidades) * $oCostoIndirecto->costo);
            $aPagos = ( $sTipoCosto=='proyecto' ? $oCostoIndirecto->pagos() : [] );
          @endphp
          <tr id="costoIndirecto{{ $oCostoIndirecto->id }}" class="p-0" data-info="{{$oCostoIndirecto}}" data-descripcion="{{ number_format($oCostoIndirecto->unidades,2,".",",") }} unidades de {{ $oCostoIndirecto->concepto }}" data-pagos="{{collect($aPagos)}}">
            <td class="p-0">
              {{ $oCostoIndirecto->concepto }}
            </td>
            <td class="text-center p-0">
              {{ $oCostoIndirecto->indirecto_unidades }} unidades por {{ $oCostoIndirecto->medida }}
            </td>
            <td class="text-right p-0">
              ${{ number_format($oCostoIndirecto->costo, 2,'.',',') }}
            </td>
            <td class="text-center p-0">
              {{ $oCostoIndirecto->unidades }}
            </td>
            <td class="text-right p-0">
              ${{ number_format( $oCostoIndirecto->costo_total, 2,'.',',') }}
              @php
              $fTotalIndirectos += $oCostoIndirecto->costo_total;
              @endphp
            </td>
            @if ($sTipoCosto == 'proyecto')
              <td class="text-center text-{{$oCostoIndirecto->comprado == 1 ? 'success':'danger'}} p-0">
                {{$oCostoIndirecto->comprado == 1 ? 'Comprado':'Por comprar'}}
              </td>
            @endif
            @if ($sTipoVista != 'print')
              <td class="p-0">
                <div class="btn-group" role="group">
                  <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-menu-bottom" data-toggle="dropdown" aria-expanded="false">
                    Acción
                  </button>
                  <div class="dropdown-menu">
                    <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="agregar_actualizar_costo('{{$oCostoIndirecto->id}}','indirecto')"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
                    @if ($sTipoCosto=='proyecto')
                      <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="marcar_comprado({{$oCostoIndirecto->id}},{{$oCostoIndirecto->comprado}})"><i class="fa fa-{{($oCostoIndirecto->comprado==false?'check-square':'square')}} mr-2"></i>{{($oCostoIndirecto->comprado==false?'Marcar comprado':'Marcar no comprado')}}</a><br>
                      <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="pago({{$oCostoIndirecto->id}},0,'costoIndirecto')"><i class="fa fa-money mr-2"></i>Pagar</a><br>
                    @endif
                    <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_costo('{{$oCostoIndirecto->id}}','indirecto')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
                  </div>
                </div>
              </td>
            @endif
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr class="p-0">
          <th colspan="4" class="text-right p-0"></th>
          <th class="text-right p-0">
            <input id="total_costos_indirectos" class="d-none" value="{{$fTotalIndirectos}}"/>
            SUBTOTAL: <small>$</small>{{number_format($fTotalIndirectos,2,".",",")}}<input id="total_costos_indirectos" type="hidden" value="{{$fTotalIndirectos}}"/>
          </th>
        </tr>
      </tfoot>
    </table>
  </div>
@else
  <center>Sin registros</center>
@endif
@include('catalogos.pagos.functions')
<script>
function agregar_actualizar_costo(iId, sTipo)
{
  $("#myModalLabel").html( (iId == 0 ? 'Agregar ' : 'Actualizar ') + ' costo ' + ( sTipo == 'indirecto' ? 'indirecto' : 'por') + ' {{$sTipoCosto}} <br/><h3 class="mt-0">' + $("#nombre").val() + '</h3>');
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "GET",
    url: "{{ asset ($sTipoCosto.'s/edit_costo_') }}" + sTipo + '/' + iId,
    data: [],
    cache: false,
    dataType: "json",
    success: function (result) {
      if(result.estatus === 1){
        if ( result.resultado.sTipoVista == 'editar' ) {
          var aCosto = result.resultado.aCosto;
          var iIdCosto = aCosto['id_costo'];
          var iIdCostoPro = aCosto['id'];
          var fUnidades = aCosto['unidades'];
          var sConcepto = aCosto['concepto'];
          var sMedida = aCosto['unidades_totales'] + ' unidades por ' + aCosto['medida'];

          $("#myModalBody").html(
            '<p>Actualiza las unidades que requiere el {{$sTipoCosto}}.</p><br/>' +
            '<table class="table table-condensed table-striped table-hover dataTable" role="grid" id="data-table-costos"></table>' +
            '<div class="card-footer text-center">' +
            '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
            '</div>'
          );
          ingresar_unidades_costo( iIdCosto, iIdCostoPro, fUnidades, sConcepto, sMedida, result.resultado.sTipoVista , sTipo);
        }else {
          var aCostos = result.resultado.aCostos;
          var htmlCostos = '<thead><tr><td>' + ( sTipo == 'indirecto' ? 'Indirecto' : 'Materia') + '</td><td>Medida</td><td>Costo</td><td></td></tr></thead>';
          for (const i in aCostos) {
            htmlCostos = htmlCostos +
            '<tr>'+
            '<td>' + aCostos[i]['concepto'] + '</td>' +
            '<td>' + aCostos[i]['unidades_totales'] + ' unidades por ' + aCostos[i]['medida'] + '</td>' +
            '<td><small>$</small>' + aCostos[i]['costo'] + '</td>' +
            '<td><a href="javascript:;" onclick="ingresar_unidades_costo(' + aCostos[i]['id'] + ',' + 0 + ',' + 1 + ',\'' + aCostos[i]['concepto'] + '\',\'' + aCostos[i]['unidades_totales'] + ' unidades por ' + aCostos[i]['medida'] + '\',\'' + result.resultado.sTipoVista + '\',\'' + sTipo + '\')" class="btn btn-secondary btn-sm"><i class="fa fa-plus"></i></a><td>' +
            '</tr>';
          }
          $("#myModalBody").html(
            '<p>Selecciona el concepto del costo e ingresa las unidades que requiere el {{$sTipoCosto}}.</p><br/>' +
            '<table class="table table-condensed table-striped table-hover dataTable" role="grid" id="data-table-costos">' + htmlCostos + '</table>' +
            '<div class="card-footer text-center">' +
            '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
            '</div>'
          );
        }
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

function ingresar_unidades_costo(iIdCosto, iIdCostoPro, fUnidades, sConcepto, sMedida, sTipoVista, sTipo)
{
  $('#data-table-costos').html(
    '<thead><tr><td>' +  (sTipo == 'indirecto' ? 'Indirecto' : 'Por {{$sTipoCosto}}' ) + '</td><td>Medida</td><td>Unidades requeridas</td><td></td></tr></thead>' +
    '<tr>' +
    '<td>' + sConcepto + '</td>' +
    '<td>' + sMedida + '</td>' +
    '<td><input id="unidades" name="unidades" class="form-control inputSlim" type="number" step="any" min="0.01" value="' + fUnidades + '"/></td>' +
    '<td><a href="javascript:;" onclick="guardar_unidades_costo(' + iIdCosto + ',' + iIdCostoPro  + ',\'' + sTipoVista + '\',\'' + sTipo + '\')" class="btn btn-success btn-sm"><i class="fa fa-floppy-o"></i></a><td>' +
    '</tr>'
  );
}

function guardar_unidades_costo(iIdCosto, iIdCostoPro, sTipoVista, sTipo)
{
  if ( !($("#unidades").val() > 0) ) {
    notificacion( 'Alerta','Las unidades deben ser un número mayor a 0.','error');
    return false;
  }
  $("#myModal").modal('toggle');
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: (sTipoVista == 'editar' ? "PUT" : "POST" ),
    url: "{{ asset ($sTipoCosto.'s') }}/" + (sTipoVista == 'editar' ? 'update' : 'store' ) + (sTipo == 'indirecto' ? '_costo_indirecto': '_costo_por_producto'),
    data: {
      @if ($sTipoCosto=='producto')
      'id_producto' : $("#id_producto").val(),
      @else
      'id_proyecto' : $("#id_proyecto").val(),
      @endif

      'id_costo' : iIdCosto,
      'id_costo_producto' : iIdCostoPro,
      'id_costo_proyecto' : iIdCostoPro,
      'unidades' : $("#unidades").val()
    },
    cache: false,
    dataType: "json",
    success: function (result) {

      notificacion( (result.estatus == 1 ? 'Datos guardados' : 'Alerta'), result.mensaje, (result.estatus == 1 ? 'success' : 'error'));
      if (result.estatus == 1) {

        if ( sTipo == 'indirecto' ) {
          $("#table_costos_indirectos").html(result.resultado);
        }else {
          $("#table_costos_por_producto").html(result.resultado);
        }
        $('#data-table-costos').html('');

        //generate_tables();
        @if ($sTipoCosto == 'proyecto')
        console.log('HOLA');
          calcular_totales();
        @endif
      }
    },
    error: function (result) {
      console.log("error");
    }
  });
}

function eliminar_costo(iId, sTipo)
{
  $("#myModalLabel").html('Eliminar costo ' + ( sTipo == 'indirecto' ? 'indirecto al {{$sTipoCosto}}' : 'por {{$sTipoCosto}}' ) + ' <h3 class="mt-0">' + $("#nombre").val() + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( $sTipoCosto."s/destroy_costo_" )}}' + sTipo +'" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
    '{{ csrf_field() }}' +
    '<strong>' + $("#costo" + ( sTipo == 'indirecto' ? 'Indirecto' : 'PorProducto' ) + iId).data('descripcion') + '</strong>' +
    '<p>El costo ' + ( sTipo == 'indirecto' ? 'indirecto' : 'por {{$sTipoCosto}}' ) + ' se eliminará y no habrá vuelta atrás.</p>' +
    '<div class="card-footer text-center">' +
    '<input class="d-none" name="id" value="' + iId +'"/>' +
    '<button type="submit" form="form2" class="btn btn-danger btn-sm"><i class="fa fa-times mr-2"></i>Eliminar</button>' +
    '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
    '</div>' +
    '</form>'
  );
  $("#myModal").modal();
}

function pagar_costo_indirecto_proyecto(iId)
{
  var sConcepto = $("#costoIndirecto" + iId ).data('info')['concepto'];
  var sDescripcion = $("#costoIndirecto" + iId ).data('descripcion');

  var aPagos = $("#costoIndirecto" + iId ).data('pagos');
  var aPagosRealizados = aPagos["pagos"];
  var fCostoTotal = parseFloat($("#costoIndirecto" + iId ).data('info')['costo_total']);

  var sHtmlPagosRealizados = '';
  for (const i in aPagosRealizados) {
    sHtmlPagosRealizados = sHtmlPagosRealizados + '<li><small> ' + aPagosRealizados[i]['concepto'] + ' ' + formatter.format(aPagosRealizados[i]['monto'])  + '.</small></li>';
  }

  $("#myModalLabel").html('Facturar costo indirecto del proyecto<h3 class="mt-0">' + sDescripcion + '</h3>');
  $("#myModalBody").html(
    '<form id="formModal" method="POST" action="{{url( "pagos/store" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>Costo total del costo indirecto: </strong>' + formatter.format(fCostoTotal) +
      ( sHtmlPagosRealizados != '' ?
      '<p>Pagos realizados: </p>' +
      '<div style="height:150px; overflow: scroll;"><ul class="text-success">' + sHtmlPagosRealizados + '</ul></div>' : '') +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Concepto</label>' +
        '<div class="col-lg-12">' +
          '{!! Form::text('concepto','Pago costo indirecto',['id'=>'concepto','class'=>'form-control inputSlim','required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Fecha</label>' +
        '<div class="col-lg-12">' +
          '{!! Form::date('fecha',date('Y-m-d'),['id'=>'fecha','class'=>'form-control inputSlim','required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">IVA <small>(%)</small></label>' +
        '<div class="col-lg-12">' +
          '{!! Form::number('iva',16,['id'=>'iva_modal','class'=>'form-control inputSlim','min'=>0,'step'=>.01,'required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Monto <small>($)</small></label>' +
        '<div class="col-lg-12">' +
          '{!! Form::number('monto',null,['id'=>'monto','class'=>'form-control inputSlim','min'=>0.01,'step'=>.01,'required']) !!}' +
        '</div>' +
      '</div>' +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Cuenta</label>' +
        '<div class="col-lg-12">' +
          '{{ Form::select('id_cuenta',@app('App\Http\Controllers\CuentasController')->get_cuentas_disponibles()->pluck('nombre','id'),null,['placeholder'=>'Seleccione una cuenta...','class'=>'form-control','required']) }}' +
        '</div>' +
      '</div>' +
      '<div class="card-footer text-center">' +
        '<input class="d-none" name="id_elemento" value="' + iId +'"/>' +
        '<input class="d-none" name="tipo_pago" value="3"/>' +
        '<button type="submit" form="formModal" class="btn btn-success btn-sm"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</form>'
  );
  $("#monto").val( (fCostoTotal).toFixed(2) );
  $("#myModal").modal();
}

function marcar_comprado(iId,boolComprado)
{
  var sConcepto = $("#costoIndirecto" + iId ).data('info')['concepto'];
  var sDescripcion = $("#costoIndirecto" + iId ).data('descripcion');

  $("#myModalLabel").html('Marcar como ' + (boolComprado==1?'no comprado':'comprado') + ' <h3 class="mt-0">' + sConcepto + '</h3>');
  $("#myModalBody").html(
    '<div>' +
      '<p>Marcarlo como comprado señala que el costo indirecto ya ha sido adquirido y listo para ser utilizado en el proyecto.</p>' +
      '<div class="card-footer text-center">' +
        '<button class="btn btn-success btn-sm" onclick="guardar_marcar_comprado('+iId+','+(boolComprado==1?0:1)+')"><i class="fa fa-'+(boolComprado==1?'times':'check')+' mr-2"></i>' + (boolComprado==1?'Marcar no comprado':'Marcar comprado') + '</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</div>'
  );
  $("#myModal").modal();
}

function guardar_marcar_comprado(iId,boolComprado)
{
  $("#myModal").modal('toggle');
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "PUT",
    url: "{{asset("proyectos/marcar_comprado_costo_indirecto")}}",
    data: {
      'id_proyecto' : $("#id_proyecto").val(),
      'id_costo_proyecto' : iId,
      'comprado' : boolComprado
    },
    cache: false,
    dataType: "json",
    success: function (result) {
      notificacion( (result.estatus == 1 ? 'Datos guardados' : 'Alerta'), result.mensaje, (result.estatus == 1 ? 'success' : 'error'));
      if (result.estatus == 1) {
        $("#table_costos_indirectos").html(result.resultado);
      }
    },
    error: function (result) {
      console.log("error");
    }
  });
}
</script>
