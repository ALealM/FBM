
<h3>Margen de utlidad promedio del {{ (abs( $fMargenUtilidadPromedio ) > 0 ? round($fMargenUtilidadPromedio * 100,2) : 0) }} % </h3>
<div class="table-responsive table-striped">
  <table class="table">
    <thead>
      <th class="text-center">Peso (W)</th>
      <th class="text-center">Nùmero de ventas (N)</th>
      <th class="text-center">Producto</th>
      <th class="text-center">Precio unitario (PU)</th>
      <th class="text-center">Costos (MP + CI)</th>
      <th class="text-center">MOD</th>
      <th class="text-center">Margen (PU - (Costos + MOD))</th>
      <th class="text-center">MCPP (W * Margen)</th>
    </thead>
    <tbody>
      @php
      if ( array_sum( array_column($aPesosProductos, 'numero_ventas') ) > 0) {
        $fMODUnitario = ($fCostoManoObraPeriodo) / array_sum( array_column($aPesosProductos, 'numero_ventas') );
      }else {
        $fMODUnitario = 0;
      }
      @endphp

      @foreach($aPesosProductos as $key => $aPesoProducto )
      @php
      $fCostoUnitario = $aPesoProducto['costo'] / $aPesoProducto['numero_ventas'];
      $fMargen = $aPesoProducto['precio_venta']  - ( $fCostoUnitario + $fMODUnitario );
      @endphp
      <tr>
        <td class="text-center">{{ number_format( $aPesoProducto['peso'] * 100 ,2,".",",")  }} %</td>
        <td class="text-center">{{ number_format( $aPesoProducto['numero_ventas'] ,2,".",",") }}</td>
        <td>
          {{ $aPesoProducto['producto'] }}
          @if ( @count($aDescuentosMayoreo[$aPesoProducto['id']]) > 0 )
            <br><small>Descuentos por mayoreo:</small>
            <ul>
              @foreach ($aDescuentosMayoreo[ $aPesoProducto['id'] ] as $aDescuento)
                <li><small>{{$aDescuento['sNombreMateria']}} {{$aDescuento['fDescuento']}}%.</small></li>
              @endforeach
            </ul>
          @endif
        </td>
        <td class="text-right"><small>$</small>{{ number_format( $aPesoProducto['precio_venta'] ,2,".",",")  }}</td>
        <td class="text-right"><small>$</small>{{ number_format( $fCostoUnitario ,2,".",",")  }}</td>
        <td class="text-right"><small>$</small>{{ number_format( $fMODUnitario ,2,".",",")  }}</td>
        <td class="text-right"><small>$</small>{{ number_format( $fMargen ,2,".",",")  }}</td>
        <td class="text-right"><small>$</small>{{ number_format( $aPesoProducto['peso'] * $fMargen ,2,".",",")  }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
