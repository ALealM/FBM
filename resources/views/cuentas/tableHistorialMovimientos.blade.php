@if($oMovimientos->isEmpty())
  <div class="text-center">No hay registros para mostrar</div>
@else
  <table class="table tile table-hover" role='grid'>
    <thead>
      <tr>
        <th class="text-center">Fecha</th>
        <th class="text-center">Descripción</th>
        <th class="text-center">Cargo</th>
        <th class="text-center">Abono</th>
      </tr>
    </thead>
    <tbody>
      @foreach($oMovimientos as $key => $oMovimiento)
        <tr id="movimiento{{$oMovimiento->id}}" data-info="{{ $oMovimiento }}">
          <td class="text-center">{{ date("d/m/Y", strtotime($oMovimiento->fecha)) }}</td>
          <td>
            {{ $oMovimiento->concepto }}<br>
            <small>{!! $oMovimiento->get_descripcion() !!}</small>
          </td>
          <td class="text-right">${!! ($oMovimiento->tipo == 0 ? number_format( $oMovimiento->monto ,2,'.',',') : '0.00')!!}</td>
          <td class="text-right">${!! ($oMovimiento->tipo == 1 ? number_format( $oMovimiento->monto ,2,'.',',') : '0.00')!!}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2"><strong>TOTALES</strong></td>
        <td class="text-right"><strong>${{ number_format( $oMovimiento->totalCargos($id_cuenta) ,2,'.',',') }}</strong></td>
        <td class="text-right"><strong>${{ number_format( $oMovimiento->totalAbonos($id_cuenta) ,2,'.',',') }}</strong></td>
      </tr>
      <tr>
        <td colspan="2"><strong>SALDO</strong></td>
        @php
          $fTotal = $oMovimiento->getTotal($id_cuenta);
        @endphp
        <td colspan="2" class="text-right text-{{( $fTotal > 0 ? 'success' : 'danger')}}"><strong>${{ number_format( $fTotal ,2,'.',',') }}</strong></td>
      </tr>
    </tfoot>
  </table>
@endif
