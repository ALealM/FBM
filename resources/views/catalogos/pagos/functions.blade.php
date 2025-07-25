<script>
  function pago(iId,iIdPago,sTipo)//sTipo: 1 =  manoObra , 2 = costoFijo, 3 = costoIndirectoProyecto
  {
    var sNombre = "";
    var iTipoManoObra = 0;//tipo de mano de obra
    var sPeriodo = "";//quincenal,mensual,bimestral
    var fCosto = 0;
    var aPagos = $("#" + sTipo + iId ).data('pagos');
    var aPagosRealizados = [];
    var aPagosPendientes = [];
    var iIdMovimiento = (iIdPago>0? $("#pago"+iIdPago).data('info')['id_movimiento'] : 0 );
    var fIvaPercent = 16;
    var sRutaPdf = "";
    var sRutaXml = "";

    switch (sTipo) {
      case 'manoObra':
        sNombre = $("#manoObra" + iId ).data('info')['nombre'];
        iTipoManoObra = parseInt($("#manoObra" + iId ).data('info')['tipo']);//tipo de mano de obra
        $("#" + sTipo + iId ).data('info')['nombre_periodo'];
        fCosto = parseFloat( $("#" + sTipo + iId ).data('info')['costo'] );
        aPagosPendientes = aPagos["pagos_pendientes"];
      break;
      case 'costoFijo':
        sNombre = $("#costoFijo" + iId ).data('info')['concepto'];
        fIvaPercent = parseFloat( $("#costoFijo" + iId ).data('info')['iva'] );
        if (iIdPago>0) {
          sRutaPdf = $("#pago" + iIdPago ).data('info')['pdf'];
          sRutaXml = $("#pago" + iIdPago ).data('info')['xml'];
        }else {
          aPagosPendientes = aPagos["pagos_pendientes"];
        }
        $("#" + sTipo + iId ).data('info')['nombre_periodo'];
        fCosto = parseFloat( $("#" + sTipo + iId ).data('info')['costo'] );
      break;
      case 'costoIndirecto':
        sNombre = $("#costoIndirecto" + iId ).data('info')['concepto'];
        fCosto = parseFloat($("#costoIndirecto" + iId ).data('info')['costo_total']);
        if (iIdPago>0) {
          sRutaPdf = $("#pago" + iIdPago ).data('info')['pdf'];
          sRutaXml = $("#pago" + iIdPago ).data('info')['xml'];
        }else {
          aPagosRealizados = aPagos["pagos"];
        }
      break;
      default:
    }

    var sHtmlPagosRealizados = '';
    for (const i in aPagosRealizados) {
      sHtmlPagosRealizados = sHtmlPagosRealizados + '<li><small> ' + aPagosRealizados[i]['concepto'] + ' ' + formatter.format(aPagosRealizados[i]['monto'])  + '.</small></li>';
    }

    var sHtmlPagosPendientes = '';
    for (const i in aPagosPendientes) {
      sHtmlPagosPendientes = sHtmlPagosPendientes + '<li><small>Del ' + aPagosPendientes[i]['fecha_inicio'] + ' al ' + aPagosPendientes[i]['fecha_fin'] + '.</small></li>';
    }

    $("#myModalLabel").html( (iIdPago>0?'Modificar':'Registrar') + ' pago <h3 class="mt-0">' + sNombre + '</h3>');
    $("#myModalBody").html(
      '<form id="formModal" method="POST" action="'+(iIdPago>0?'{{url( "pagos/update" )}}':'{{url( "pagos/store" )}}')+'" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
        '{{ csrf_field() }} ' +
        ( iIdPago>0 ? '{{ method_field('PUT') }}' : '' ) +
        ( sHtmlPagosRealizados != '' ?
        '<p>Pagos realizados: </p>' +
        '<div style="max-height:150px; overflow: scroll;"><ul class="text-success">' + sHtmlPagosRealizados + '</ul></div>' : '') +
        ( sHtmlPagosPendientes != '' && iIdPago == 0 ?
        '<p>' + sNombre + ' tiene pagos pendientes: </p>' +
        '<div style="max-height:150px; overflow: scroll;"><ul class="text-danger">' + sHtmlPagosPendientes + '</ul></div>' : '') +
        '<div class="row">' +
          '<label class="col-12 col-form-label">Concepto</label>' +
          '<div class="col-lg-12">' +
            '{!! Form::text('concepto_pago',null,['id'=>'concepto_pago','class'=>'form-control inputSlim','required']) !!}' +
          '</div>' +
        '</div>' +
        (sPeriodo != '' ?
        '<div class="row">' +
          '<label class="col-12 col-form-label">Periodo</label>' +
          '<div class="col-lg-12">' +
            sPeriodo +
          '</div>' +
        '</div>' : '' ) +
        '<div class="row">' +
          '<label class="col-12 col-form-label">' + (sTipo == 'costoIndirecto' ? 'Fecha' : 'Periodo a cubrir <small>(Seleccione el día del periodo a cubrir)</small>') + '</label>' +
          '<div class="col-lg-12">' +
            '{!! Form::date('fecha',null,['id'=>'fecha','class'=>'form-control inputSlim','required']) !!}' +
          '</div>' +
        '</div>' +
        (iTipoManoObra == 3 || sTipo == 'costoFijo' || sTipo == 'costoIndirecto'  ? //honorarios o costos fijos o costoIndirectoProyecto
        '<div class="row">' +
          '<label class="col-12 col-form-label">IVA <small>(%)</small></label>' +
          '<div class="col-lg-12">' +
            '{!! Form::number('iva',16,['id'=>'iva_modal','class'=>'form-control inputSlim','min'=>0,'step'=>.01,'required']) !!}' +
          '</div>' +
        '</div>' : '') +
        '<div class="row">' +
          '<label class="col-12 col-form-label">Monto <small>($)</small></label>' +
          '<div class="col-lg-12">' +
            '{!! Form::number('monto',null,['id'=>'monto','class'=>'form-control inputSlim','min'=>0.01,'step'=>.01,'required']) !!}' +
          '</div>' +
        '</div>' +
        '<div class="row">' +
          '<label class="col-12 col-form-label">Cuenta</label>' +
          '<div class="col-lg-12">' +
            '{{ Form::select('id_cuenta',@app('App\Http\Controllers\CuentasController')->get_cuentas_disponibles()->pluck('nombre','id'),null,['id'=>'id_cuenta','placeholder'=>'Seleccione una cuenta...','class'=>'form-control','required']) }}' +
          '</div>' +
        '</div>' +
        ( iIdPago > 0 ?
        '<div class="card-footer text-center">' +
        ( sRutaPdf != '' && sRutaPdf != null ?
        '<a href="{{asset('pagos/get_pdf')}}/'+iIdPago+'" class="btn btn-secondary btn-lg"><i class="fa fa-file-pdf-o mr-2"></i>PDF</a>'
        : '' ) +
        ( sRutaXml != '' && sRutaXml != null ?
        '<a href="{{asset('pagos/get_xml')}}/'+iIdPago+'" class="btn btn-secondary btn-lg"><i class="fa fa-file-code-o mr-2"></i>XML</a>'
        : '' ) +
        '</div>'
        : '') +
        ( sTipo == 'costoFijo' || sTipo == 'costoIndirecto' ?
        '<div class="card-footer text-center">' +
          '{{Form::file('pdf', ['id'=>'pdf','class'=>'form-control d-none','accept'=>"application/pdf",'onchange'=>'cambio_file_pdf()'] )}}' +
          '<a id="boton_pdf" href="javascript:;" class="btn btn-secondary btn-sm btn-circle" onclick="click_file_pdf()"><i class="fa fa-paperclip mr-2"></i>PDF</a>' +
          '{{Form::file('xml', ['id'=>'xml','class'=>'form-control d-none','accept'=>"application/xml",'onchange'=>'cambio_file_xml()'] )}}' +
          '<a id="boton_xml" href="javascript:;" class="btn btn-secondary btn-sm" onclick="click_file_xml()"><i class="fa fa-paperclip mr-2"></i>XML</a>' +
        '</div>' : '' ) +
        '<div class="card-footer text-center">' +
          '<input class="d-none" name="id_elemento" value="' + iId +'"/>' +
          '<input class="d-none" name="tipo_pago" value="' + (sTipo == 'manoObra' ? 1 : (sTipo == 'costoFijo' ? 2 : 3 )) + '"/>' +
          '<input class="d-none" name="id_pago" value="'+iIdPago+'"/>' +
          '<input class="d-none" name="id_movimiento" value="'+iIdMovimiento+'"/>' +
          '<button type="submit" form="formModal" class="btn btn-success btn-sm"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>' +
          '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
        '</div>' +
      '</form>'
    );

    if (iIdPago>0) {
      var sConcepto = $("#pago" + iIdPago ).data('info')['concepto'];
      var sFecha = $("#pago" + iIdPago ).data('info')['fecha'];
      var fIva = parseFloat(($("#pago" + iIdPago ).data('info')['iva']>0?$("#pago" + iIdPago ).data('info')['iva']:0));
      var fMonto = parseFloat(($("#pago" + iIdPago ).data('info')['monto']>0?$("#pago" + iIdPago ).data('info')['monto']:0));
      var iIdCuenta = $("#pago" + iIdPago ).data('info')['id_cuenta'];

      $("#concepto_pago").val(sConcepto);
      $("#fecha").val(sFecha);
      $("#iva_modal").val(fIva);
      $("#monto").val(fMonto);
      $("#id_cuenta").val(iIdCuenta);
    }else {
      $("#monto").val(fCosto);
      $("#iva_modal").val(fIvaPercent);
    }
    $("#myModal").modal();
  }

  function click_file_pdf(){ $( "#pdf" ).click(); }

  function cambio_file_pdf(){
    if ($("#pdf").val() != null) {
      $("#boton_pdf").html('<i class="fa fa-paperclip mr-2"></i>PDF cargado...');
    }else {
      $("#boton_pdf").html('<i class="fa fa-paperclip mr-2"></i>PDF');
    }
  }

  function click_file_xml(){ $( "#xml" ).click(); }

  function cambio_file_xml(){
    if ($("#xml").val() != null) {
      $("#boton_xml").html('<i class="fa fa-paperclip mr-2"></i>XML cargado...');
    }else {
      $("#boton_xml").html('<i class="fa fa-paperclip mr-2"></i>XML');
    }
  }


  function eliminar_pago(iId)
  {
    var iIdMovimiento = $("#pago"+iId).data('info')['id_movimiento'];
    $("#myModalLabel").html('Eliminar registro de pago <h3 class="mt-0">' + $("#pago" + iId).data('info')['concepto'] + '</h3>');
    $("#myModalBody").html(
      '<form id="form2" method="POST" action="{{url( "pagos/destroy" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
        '{{ csrf_field() }}' +
        '{{ method_field('DELETE') }}' +
        'Concepto <strong>' + $("#pago" + iId).data('info')['concepto'] + '</strong> con monto de ' + formatter.format($("#pago" + iId).data('info')['monto']) + '.' +
        '<p>El registro del pago será eliminado y afectará el estado de cuenta.</p>' +
        '<div class="card-footer text-center">' +
          '<input class="d-none" name="id_pago" value="' + iId +'"/>' +
          '<input class="d-none" name="id_movimiento" value="' + iIdMovimiento +'"/>' +
          '<button type="submit" form="form2" class="btn btn-danger btn-sm"><i class="fa fa-times mr-2"></i>Eliminar</button>' +
          '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
        '</div>' +
      '</form>'
    );
    $("#myModal").modal();
  }
</script>
