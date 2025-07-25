<script>
function cobro_proyecto(iId)
{
  cobro(iId,'');
}

function cobro(iId,sHtmlFactura)
{
  var sNombre = $("#proyecto" + iId ).data('info')['nombre'];
  var fIvaPercent = parseFloat($("#proyecto" + iId ).data('info')['iva']);
  var aPresupuesto = $("#proyecto" + iId ).data('presupuesto');
  var aCobros = $("#proyecto" + iId ).data('cobros');
  var aCobrosRealizados = aCobros["cobros"];
  var fTotalCobrado = aCobros['total_cobrado'];
  var fPrecioVenta = aPresupuesto['precio_venta'];
  var sHtmlCobrosRealizados = '';
  for (const i in aCobrosRealizados) {
    sHtmlCobrosRealizados = sHtmlCobrosRealizados + '<li><small> ' + aCobrosRealizados[i]['concepto'] + ' ' + formatter.format(aCobrosRealizados[i]['monto'])  + '.</small></li>';
  }
  $("#myModalLabel").html('Cobro de proyecto <h3 class="mt-0">' + sNombre + '</h3>');
  $("#myModalBody").html(
    '<form id="formModal" method="POST" action="{{url( "cobros/store" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>Precio venta: </strong>' + formatter.format(fPrecioVenta) +
      ( sHtmlCobrosRealizados != '' ?
      '<p>Cobros realizados: </p>' +
      '<div style="height:150px; overflow: scroll;"><ul class="text-success">' + sHtmlCobrosRealizados + '</ul></div>' : '') +
      sHtmlFactura +
      '<div class="row">' +
        '<label class="col-12 col-form-label">Concepto del cobro</label>' +
        '<div class="col-lg-12">' +
          '{!! Form::text('concepto','Factura proyecto',['id'=>'concepto','class'=>'form-control inputSlim','required']) !!}' +
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
          '{!! Form::number('iva',null,['id'=>'iva_modal','class'=>'form-control inputSlim','min'=>0,'step'=>.01,'required']) !!}' +
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
          '{{ Form::select('id_cuenta',(@$aCuentas!=null?$aCuentas:[]),null,['placeholder'=>'Seleccione una cuenta...','class'=>'form-control','required']) }}' +
        '</div>' +
      '</div>' +
      '<div class="card-footer text-center">' +
        '<input class="d-none" name="id_elemento" value="' + iId +'"/>' +
        '<input class="d-none" name="tipo_cobro" value="1"/>' +
        '<button type="submit" form="formModal" class="btn btn-success btn-sm"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</form>'
  );
  $("#iva_modal").val(fIvaPercent);
  $("#monto").val((fPrecioVenta-fTotalCobrado).toFixed(2));
  $("#myModal").modal();
}

function factura_proyecto(iId)
{
  var sNombre = $("#proyecto" + iId ).data('info')['nombre'];
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "GET",
    url: "{{ asset ('proyectos/edit_factura') }}",
    data: {
      'id_proyecto' : iId,
    },
    cache: false,
    dataType: "json",
    success: function (result) {
      if(result.estatus === 1){
        $("#myModalLabel").html('Crear factura a proyecto <h3 class="mt-0">' + sNombre + '</h3>');
        $("#myModalBody").html(
          {{--'<form id="formModal" method="POST" action="{{url( "facturama/store_cfdi" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
            '{{ csrf_field() }}' +--}}
            result.resultado
            {{--'<div class="card-footer text-center">' +
              '<button type="submit" form="formModal" class="btn btn-success btn-sm"><i class="fa fa-check mr-2"></i>Crear factura</button>' +
              '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
            '</div>' +
          '</form>'--}}
        );
        $("#myModal").modal();
      }else {
        notificacion('Alerta','Error al intentar crear la factura.','error');
      }
    },
    error: function (result) {
      console.log("error");
    }
  });
}


function duplicar_proyecto(iId)
{
  var sNombre = $("#proyecto" + iId ).data('info')['nombre'];

  $("#myModalLabel").html('Duplicar proyecto <h3 class="mt-0">' + sNombre + '</h3>');
  $("#myModalBody").html(
    '<form id="formModal" method="POST" action="{{url( "proyectos/duplicar" )}}/'+iId+'" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<p>Duplicar el proyecto para reventa. Se usará la misma estructura y se podrá modificar para poder realizar un nuevo presupuesto en espera de una nueva venta.</p>' +
      '<div class="card-footer text-center">' +
        '<button type="submit" form="formModal" class="btn btn-success btn-sm"><i class="fa fa-clone mr-2"></i>Si, duplicar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</form>'
  );
  $("#myModal").modal();
}

</script>
