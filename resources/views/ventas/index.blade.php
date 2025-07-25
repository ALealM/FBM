@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

  <div class="row">
    <div class="col-lg-7">
      <!--Buscador-->
      <div class="card" ><!--style="box-shadow: 5px 5px 5px 5px grey"-->
        @include('ventas.buscador')
      </div>
      <!--Buscador end-->

      <!--Carrito-->
      <table class="table bg-light">
        <tbody id="tbodyCarrito">
        </tbody>
        <tfoot id="tfootCarrito">
        </tfoot>
      </table>
      <!--Carrito end-->
    </div>

    <!--Totalizador-->
    <div class="col-lg-5">
      <table class="table bg-light">
        <tbody>
          <tr>
            <td>
              <div class="form-check">
                <label class="form-check-label">
                  {!! Form::checkbox('domicilio',true,false,['id'=>'domicilio','class'=>'form-check-input','onchange'=>'cambio_cobro_domicilio()']) !!}
                  Cobro servicio a domicilio
                  <span class="mt-3 form-check-sign">
                    <span class="check"></span>
                  </span>
                </label>
              </div>
            </td>
            <td id="cobro_domicilio" data-value="0" class="text-right">$0.00</td>
          </tr>
          <tr>
            <td>SUBTOTAL (MXN)</td>
            <td id="subtotal" data-value="0" class="text-right">$0.00</td>
          </tr>
          <tr>
            <td>IVA (MXN)</td>
            <td id="iva" data-value="0" class="text-right">$0.00</td>
          </tr>
          <tr>
            <td><strong>TOTAL (MXN)</strong></td>
            <td id="total" data-value="0" class="text-right"><strong>$0.00</strong></td>
          </tr>
        </tbody>
      </table>
      <div class="card-footer justify-content-center">
        <button type="submit" class="btn btn-success" onclick="finalizar_venta()"><i class="fa fa-money mr-2"></i>Finalizar venta</button>
      </div>
    </div>
    <!--Totalizador end-->
  </div>
  @if ($oProductos->count() > 0)
  @else
    <center>Requieres productos para registrar ventas</center>
  @endif

  <script>



  function cambio_cobro_domicilio()
  {
    if ( $("#domicilio").is(':checked') ) {
      $("#tfootCarrito").append(
        '<tr id="domicilio_carrito">' +
          '<td></td>' +
          '<td colspan="2">Servicio a domicilio</td>' +
          '<td>' +
            '<div class="form-check">' +
              '<label class="form-check-label">' +
                '{!! Form::checkbox('domicilio_iva',true,false,['id'=>'domicilio_iva','class'=>'form-check-input','onchange'=>'calcular_total_venta()']) !!}' +
                'IVA' +
                '<span class="mt-3 form-check-sign">' +
                  '<span class="check"></span>' +
                '</span>' +
              '</label>' +
            '</div>' +
          '</td>' +
          '<td class="text-center">(%)</td>' +
          '<td class="text-center">' +
          '<input id="domicilio_porcentaje" class="form-control inputSlim" required="" step="any" min="0" max="100" name="domicilio_porcentaje" type="number" onchange="calcular_total_venta()" value="25">' +
          '</td>' +
          '<td></td>' +
        '</tr>'
      );
    }else {
      $("#domicilio_carrito").remove();
    }
    $("#table_resultados").html('');
    calcular_total_venta();
  }

  function calcular_total_venta()
  {
    var subtotal = 0;
    var iva = 0;
    var total = 0;



    $(".producto").each(function( index ) {
      var aProducto = $(this).data('info');
      var iIdProducto = aProducto['id'];
      var fPrecio = parseFloat(aProducto['precio_venta']);
      var fIvaPercent = parseFloat((aProducto['iva']>0?aProducto['iva']:0));
      var fCantidad = parseFloat($("#cantidad"+iIdProducto).val());

      ivaProducto = (fIvaPercent>0?(fPrecio * fIvaPercent / 100):0);

      subtotal += (fPrecio - (ivaProducto)) * fCantidad;
      iva += ivaProducto;
      total += fPrecio * fCantidad;
    });

    var cobro_domicilio = 0;
    var porcentaje_domicilio = ($("#domicilio_porcentaje").val()>0?parseFloat($("#domicilio_porcentaje").val()):0);
    var domicilio_iva = 0;
    if ($("#domicilio").is(':checked')) {
      cobro_domicilio = subtotal * porcentaje_domicilio / 100;
      domicilio_iva = ($("#domicilio_iva").is(':checked')?(cobro_domicilio * .16):0);

      subtotal += cobro_domicilio;
      iva += domicilio_iva;
      total += domicilio_iva + cobro_domicilio;
    }

    //values
    $("#cobro_domicilio").data('value',cobro_domicilio);
    $("#subtotal").data('value',subtotal);
    $("#iva").data('value',iva);
    $("#total").data('value',total);

    //labels
    $("#cobro_domicilio").html( formatter.format(cobro_domicilio) );
    $("#subtotal").html( formatter.format(subtotal) );
    $("#iva").html( formatter.format(iva) );
    $("#total").html( formatter.format(total) );
  }

  function finalizar_venta()
  {
    var aProductos = [];
    $(".producto").each(function( index ) {
      aProductos[index] = $(this).data('info');
      aProductos[index].unidades_vendidas = parseFloat($("#cantidad"+aProductos[index].id).val());
    });
    if (aProductos.length == 0) {
      notificacion('Alerta','No se han agregado productos al carrito de ventas.', 'warning');
      return false;
    }
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "POST",
      url: "{{asset("ventas/store")}}",
      data: {
        'productos' : aProductos,
        'subtotal' : $("#subtotal").data('value'),
        'iva' : $("#iva").data('value'),
        'total' : $("#total").data('value'),
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if (result.estatus == 1) {
          notificacion('Venta','Venta finalizada.','success');
          $("#tbodyCarrito").html('');
          calcular_total_venta();
        }
      },
      error: function (result) {
        console.log("error");
      }
    });
  }
  </script>
@endsection
