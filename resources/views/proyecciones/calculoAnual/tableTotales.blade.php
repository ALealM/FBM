<div class="table-responsive table-striped">
  <table class="table">
    <thead>
      <tr>
        <th>Costos materia prima (MP)</th>
        <th>Costos indirectos (CI)</th>
        <th>Mano de obra (MOD)</th>
        <th>Costos fijos (CF)</th>
        <th>Egresos (MP + CI + MOD + CF)</th>
        <th>Ingresos</th>
        <th>Total de ganancias (Ingresos - Total de costos )</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-center text-danger">
          <small>$</small>{{ number_format( array_sum( $aCostosProductos ) - $fTotalCostosIndirectos ,2,".",",") }}
        </td>
        <td class="text-center text-danger">
          <small>$</small>{{ number_format( $fTotalCostosIndirectos ,2,".",",") }}
        </td>
        <td class="text-center text-danger">
          <small>$</small>{{ number_format( $fCostoManoObraMensual * 12 ,2,".",",") }}
        </td>
        <td class="text-center text-danger">
            <small>$</small>{{ number_format( $fCostoFijoMensual * 12 ,2,".",",") }}
        </td>
        <td class="text-center text-danger">
          <small>$</small>{{ number_format( $fTotalEgresos ,2,".",",") }}
        </td>
        <td class="text-center text-success">
          <small>$</small>{{ number_format( $fTotalIngresos ,2,".",",") }}
        </td>
        <td class="text-center text-{{ ( $fTotalIngresos - $fTotalEgresos <= 0  ) ? 'danger' : 'success' }}">
          <small>$</small>{{ number_format( $fTotalIngresos - $fTotalEgresos ,2,".",",") }}
        </td>
      </tr>

    </tbody>
  </table>
</div>



<!--div class="table-responsive table-striped">
<table class="table">
<thead>
<tr>
<th></th>
<th></th>
<th></th>
</tr>
</thead>
<tbody>
<tr>
<td class="text-right">COSTOS MATERIA PRIMA (MP)</td>
<td colspan="2" class="text-center"><strong>AL AÑO</strong></td>
</tr>
<tr>
<td></td>
<td colspan="2" class="text-center text-danger"><small>$</small>{{ number_format( array_sum( $aCostosProductos ) - $fTotalCostosIndirectos ,2,".",",") }}</td>
</tr>
<tr>
<td class="text-right">COSTOS INDIRECTOS (CI)</td>
<td colspan="2" class="text-center"><strong>AL AÑO</strong></td>
</tr>
<tr>
<td></td>
<td colspan="2" class="text-center text-danger"><small>$</small>{{ number_format( $fTotalCostosIndirectos ,2,".",",") }}</td>
</tr>
<tr>
<td class="text-right">MANO DE OBRA (MOD)</td>
<td class="text-center"><strong>AL MES</strong></td>
<td class="text-center"><strong>AL AÑO</strong></td>
</tr>
<tr>
<td></td>
<td class="text-center text-danger"><small>$</small>{{ number_format( $fCostoManoObraMensual ,2,".",",") }} </td>
<td class="text-center text-danger"><small>$</small>{{ number_format( ($fCostoManoObraMensual * 12) ,2,".",",") }}</td>
</tr>
<tr>
<td class="text-right">COSTOS FIJOS (CF)</td>
<td class="text-center"><strong>AL MES</strong></td>
<td class="text-center"><strong>AL AÑO</strong></td>
</tr>
<tr>
<td></td>
<td class="text-center text-danger"><small>$</small>{{ number_format( $fCostoFijoMensual ,2,".",",") }} </td>
<td class="text-center text-danger"><small>$</small>{{ number_format( ($fCostoFijoMensual * 12) ,2,".",",") }}</td>
</tr>
<tr>
<td class="text-right"><strong>TOTAL DE COSTOS (MP + CI + MOD + CF)</strong></td>
<td colspan="2" class="text-center"><strong>AL AÑO</strong></td>
</tr>
<tr>
<td></td>
<td colspan="2" class="text-center text-danger"><small>$</small>{{ number_format( array_sum( $aCostosProductos ) + ($fCostoFijoMensual * 12) + ($fCostoManoObraMensual * 12) ,2,".",",") }}</td>
</tr>
<tr>
<td class="text-right"><strong>TOTAL DE INGRESOS</strong></td>
<td colspan="2" class="text-center"><strong>AL AÑO</strong></td>
</tr>
<tr>
<td></td>
<td colspan="2" class="text-center text-success"><small>$</small>{{ number_format( $fTotalIngresos ,2,".",",") }}</td>
</tr>
<tr>
<td class="text-right"><strong>TOTAL DE GANANCIAS (INGRESOS - TOTAL DE COSTOS )</strong></td>
<td colspan="2" class="text-center"><strong>AL AÑO</strong></td>
</tr>
<tr>
<td></td>
<td colspan="2" class="text-center {{ ( $fTotalGanancia - ($fCostoFijoMensual * 12) <= 0  ) ? 'text-danger' : 'text-success' }}">
<small>$</small>{{ number_format( $fTotalGanancia - ($fCostoFijoMensual * 12) - ($fCostoManoObraMensual * 12) ,2,".",",") }}
</td>
</tr>
</tbody>
</table>
</div-->
