@if ($sTipoVista != 'print')
  <!--strong class="color-fbm-blue ml-4"></strong-->
@endif
<div id="table_totales" class="table-responsive pt-4">
  <table class="table table-hover table-fixed table-bordered" role='grid'>
    <thead>
      <tr class="p-0">
        <th class="p-0 text-center {{$sTipoVista=='print'?'':'text-white bg-fbm-blue'}}">Fase</th>
        <th class="p-0 text-center {{$sTipoVista=='print'?'':'text-white bg-fbm-blue'}}">Días</th>
        <th class="p-0 text-center {{$sTipoVista=='print'?'':'text-white bg-fbm-blue'}}">Horas</th>
        <th class="p-0 text-center {{$sTipoVista=='print'?'':'text-white bg-fbm-blue'}}">Costo</th>
        <th class="p-0 text-center {{$sTipoVista=='print'?'':'text-white bg-fbm-blue'}}">%</th>
        <th class="p-0 text-center {{$sTipoVista=='print'?'':'text-white bg-fbm-blue'}}">$ Hora venta</th>
        <th class="p-0 text-center {{$sTipoVista=='print'?'':'text-white bg-fbm-blue'}}">Horas venta</th>
        <th class="p-0 text-center {{$sTipoVista=='print'?'':'text-white bg-fbm-blue'}}">Total</th>
      </tr>
    </thead>
    <tbody id="tbody_totales">

    </tbody>
  </table>
</div>
<script>
function calcular_totales()
{
  var fTotalDias = 0;
  var fTotalHoras = 0;
  var fHoraVenta = 0;
  var fTotalHorasVenta = 0;
  var fTotalValor = parseFloat($('#total_valor').val());

  $("#tbody_totales").html('');
  $(".fases").each(function( index ) {
    //console.log( $(this).attr('id') + ": " + $( this ).text() );
    //$(this).data('info')['nombre']
    var aFase = $(this).data('info');
    var iIdFase = aFase['id'];
    var aFaseTOTAL = $("#faseTOTAL"+aFase['id']).data('info');

    var fFaseValor = parseFloat(aFaseTOTAL['valor']);

    fTotalDias += parseFloat(aFase['dias']);
    fTotalHoras += parseFloat(aFaseTOTAL['horas_trabajo']);
    fHoraVenta = fFaseValor/ aFaseTOTAL['horas_trabajo'];
    fTotalHorasVenta += fHoraVenta;


    $("#tbody_totales").append(
      '<tr class="{{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' +
        '<td class="{{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' + (index + 1) + '. ' + aFase['nombre'] + '</td>' +//nombre
        '<td class="text-center {{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' + aFase['dias'] + '</td>' + //duracion dias
        '<td class="text-center {{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' + aFaseTOTAL['horas_trabajo'] + '</td>' + //horas
        '<td class="text-right {{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' + formatter.format(fFaseValor)  + '</td>' + //costo
        '<td class="text-center {{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' + formatter.format(fFaseValor/fTotalValor*100).replace('$','') + '%</td>' + //%
        '<td class="text-right {{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' + formatter.format(fHoraVenta) + '</td>' + //$ hora venta
        '<td class="text-center {{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' + formatter.format(aFaseTOTAL['horas_trabajo']/aFaseTOTAL['numero_roles']).replace('$','') + '</td>' + //Horas venta
        '<td class="text-right {{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' + formatter.format(fFaseValor) + '</td>' + //precio venta
      '</tr>'
    );
  });

  $("#tbody_totales").append(
    '<tr class="{{@$sTipoVista=='print'?'pt-0 pb-0':''}}">' +
      '<td class="text-right {{@$sTipoVista=='print'?'pt-0 pb-0':'text-white bg-fbm-blue'}}"><strong>PRESUPUESTO TOTAL:</strong></td>' +
      '<td class="text-center {{@$sTipoVista=='print'?'pt-0 pb-0':''}}"><strong>' + formatter.format(fTotalDias).replace('$','') + '</strong></td>' + //Total duracion dias
      '<td class="text-center {{@$sTipoVista=='print'?'pt-0 pb-0':''}}"><strong>' + formatter.format(fTotalHoras).replace('$','') + '</strong></td>' + //Total horas
      '<td class="text-right {{@$sTipoVista=='print'?'pt-0 pb-0':''}}"><strong>' + formatter.format(fTotalValor) + '</strong></td>' + //Total costo
      '<td class="text-center {{@$sTipoVista=='print'?'pt-0 pb-0':''}}"><strong>100%</td>' + //Total %
      '<td class="text-right {{@$sTipoVista=='print'?'pt-0 pb-0':''}}"><strong>' + formatter.format(fHoraVenta) + '</strong></td>' + //Total $ hora venta
      '<td class="text-center {{@$sTipoVista=='print'?'pt-0 pb-0':''}}"><strong>' + formatter.format(fTotalHorasVenta).replace('$','') + '</strong></td>' + //Total Horas venta
      '<td class="text-right {{@$sTipoVista=='print'?'pt-0 pb-0':''}}"><strong>' + formatter.format(fTotalValor) + '</strong></td>' + //Total precio venta
    '</tr>'
  );
  var fCostosIndirectos = parseFloat($("#total_costos_indirectos").val());
  var fMargenPercent = parseFloat($("#margen").val());
  var fMargen = (fTotalValor+fCostosIndirectos) * fMargenPercent / 100;
  var fMargenErrorPercent = parseFloat( $("#margen_error").val());
  var fMargenError = (fTotalValor+fCostosIndirectos) * fMargenErrorPercent / 100;
  var fTotal = fTotalValor + fCostosIndirectos + fMargen + fMargenError;
  var fIvaPercent = parseFloat($("#iva").val());
  var fIva = (fIvaPercent>0 ? (fTotal *  fIvaPercent / 100) : 0);

  $("#tbody_totales").append(
    '<tr class="{{@$sTipoVista=='print' ? 'pt-0 pb-0' : '' }}">' +
      '<td colspan="7" class="text-right pt-0 pb-0"><strong>Costos indirectos:</strong></td>' +
      '<td class="text-right pt-0 pb-0"><strong>' + formatter.format(fCostosIndirectos) + '</strong></td>' + //
    '</tr>' +

    '<tr class="{{@$sTipoVista=='print' ? 'pt-0 pb-0' : '' }}">' +
      '<td colspan="7" class="text-right pt-0 pb-0"><strong>Margen (' + formatter.format(fMargenPercent).replace('$','') + '%):</strong></td>' +
      '<td class="text-right pt-0 pb-0"><strong>' + formatter.format(fMargen) + '</strong></td>' + //
    '</tr>' +
    '<tr class="{{@$sTipoVista=='print' ? 'pt-0 pb-0' : '' }}">' +
      '<td colspan="7" class="text-right pt-0 pb-0"><strong>Margen error ('+formatter.format(fMargenErrorPercent).replace('$','')+'%):</strong></td>' +
      '<td class="text-right pt-0 pb-0"><strong>' + formatter.format(fMargenError) + '</strong></td>' + //
    '</tr>' +

    '<tr class="{{@$sTipoVista=='print' ? 'pt-0 pb-0' : 'text-white bg-fbm-blue' }}">' +
      '<td colspan="7" class="text-right pt-0 pb-0"><strong>SUBTOTAL:</strong></td>' +
      '<td class="text-right pt-0 pb-0"><strong>' + formatter.format(fTotalValor + fCostosIndirectos + fMargen + fMargenError ) + '</strong></td>' + //
    '</tr>' +

    '<tr class="{{@$sTipoVista=='print' ? 'pt-0 pb-0' : '' }}">' +
      '<td colspan="7" class="text-right pt-0 pb-0"><strong>IVA ('+formatter.format(fIvaPercent).replace('$','')+'%):</strong></td>' +
      '<td class="text-right pt-0 pb-0"><strong>' + formatter.format(fIva) + '</strong></td>' + //
    '</tr>' +

    '<tr class="{{@$sTipoVista=='print' ? '' : 'text-white bg-fbm-blue' }}">' +
      '<td colspan="7" class="text-right"><strong>PRECIO VENTA:</strong></td>' +
      '<td class="text-right "><strong>' + formatter.format(fTotal + fIva) + '</strong></td>' + //
    '</tr>'
  );
}

</script>
