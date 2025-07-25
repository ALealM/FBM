
<h3>Estado de resultados</h3>
<div class="table-responsive ">
  <table class="table">
    <tbody>
      <tr class="bg-light">
        <td class=""><strong>Ventas</strong></td>
        <td class="text-right text-success"><strong>$ {{ number_format($fTotalIngresos ,2,".",",")  }}</strong></td>
      </tr>
      <tr class="bg-light">
        <td class=""><strong>Costo producción</strong></td>
        <td class="text-right text-danger"><strong>$ {{ number_format($fTotalEgresos ,2,".",",")  }}</strong></td>
      </tr>
      @foreach($aCarritoMateriaPrima as $aMateriaPrima)
        @php
        $fCosto = $aMateriaPrima['fCosto'] * (1 - ($aMateriaPrima['fDescuentoMayoreo']/100)) *  $aMateriaPrima['fCantidadTotal'] ;
        @endphp
        <tr>
          <td class=""> - {{$aMateriaPrima['sNombreMateria']}}</td>
          <td class="text-right text-danger">$ {{ number_format( $fCosto ,2,".",",")  }} </td>
        </tr>
      @endforeach
      <tr>
        <td class=""> - Costos indirectos</td>
        <td class="text-right text-danger">$ {{ number_format( $fTotalCostosIndirectos ,2,".",",")  }} </td>
      </tr>
      <tr>
        <td class=""> - Mano de obra</td>
        <td class="text-right text-danger">$ {{ number_format( $fCostoManoObraPeriodo ,2,".",",")  }} </td>
      </tr>
      <!--tr>
        <td class=""><strong>Costos fijos</strong></td>
        <td class="text-right text-danger"><strong>$ {{ number_format($fCostoFijoPeriodo ,2,".",",")  }}</strong></td>
      </tr-->
      @foreach($oCostosFijos as $oCostoFijo)
        @php
        $fCostoPeriodo = 0;
        switch ($sTemporalidad) {
          case 'semanal':
            $fCostoPeriodo = $oCostoFijo->costo_mensual / 4;
          break;
          case 'mensual':
            $fCostoPeriodo = $oCostoFijo->costo_mensual;
          break;
          case 'semestral':
            $fCostoPeriodo = $oCostoFijo->costo_mensual * 6;
          break;
          default://anual
            $fCostoPeriodo = $oCostoFijo->costo_mensual * 12;
          break;
        }
        @endphp
        <tr>
          <td class=""> - {{$oCostoFijo->concepto}}</td>
          <td class="text-right text-danger">$ {{ number_format( $fCostoPeriodo ,2,".",",")  }} </td>
        </tr>
      @endforeach
      <tr class="bg-light">
        <td class="h4">Utilidad bruta</td>
        <td class="text-right">
          <h2 class="text-{{ ( $fTotalIngresos - $fTotalEgresos <= 0  ) ? 'danger' : 'success' }}">
            ${{ number_format( $fTotalIngresos - $fTotalEgresos ,2,".",",") }}
          </h2>
        </td>
      </tr>
    </tbody>
  </table>
</div>
