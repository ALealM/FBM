<center><h3>Impuestos</h3></center>
<div class="table-responsive table-striped">
  <table class="table">
    <thead>
      <th class="text-center">Mes</th>
      <th class="text-center">IVA a favor</th>
      <th class="text-center">IVA por pagar</th>
    </thead>
    <tbody>
      @php
        $aMesesLabel = [
          1 => 'Enero',
          2 => 'Febrero',
          3 => 'Marzo',
          4 => 'Abril',
          5 => 'Mayo',
          6 => 'Junio',
          7 => 'Julio',
          8 => 'Agosto',
          9 => 'Septiembre',
          10 => 'Octubre',
          11 => 'Noviembre',
          12 => 'Diciembre'
        ];
        $aMeses = [
          date('Y-m',strtotime("-12 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-11 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-10 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-9 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-8 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-7 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-6 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-5 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-4 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-3 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-2 month",strtotime(date('Y-m-d')))),
          date('Y-m',strtotime("-1 month",strtotime(date('Y-m-d')))),
          date('Y-m')
        ];
        $fTotalContra = 0;
        $fTotalFavor = 0;
      @endphp

      @foreach($aMeses as $key => $sMes )
        @php
          $fMontoFavor = @array_sum(@array_column(@$aImpuestosFavor[$sMes],'iva_total'));
          $fMontoContra = @array_sum(@array_column(@$aImpuestosContra[$sMes],'iva_total'));
          $fTotalContra += $fMontoContra;
          $fTotalFavor += $fMontoFavor;
        @endphp
        <tr class="m-0 p-0">
          <td class="m-0 p-0">
            {{$aMesesLabel[ date('m',strtotime($sMes.'-01')) * 1 ] }} <small>{{date('Y',strtotime($sMes.'-01'))}}</small>
          </td>
          <td class="m-0 p-0 text-right {{($fMontoFavor>0?'text-success':'')}}">
            $ {{ number_format( $fMontoFavor ,2,".",",") }}
          </td>
          <td class="m-0 p-0 text-right {{($fMontoContra>0?'text-danger':'')}}">
            $ {{ number_format( $fMontoContra ,2,".",",") }}
          </td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td class="text-right"><strong>TOTAL</strong></td>
        <td class="text-right text-success"><strong>${{ number_format( $fTotalFavor,2,'.',',') }}</strong></td>
        <td class="text-right text-danger"><strong>${{ number_format( $fTotalContra,2,'.',',') }}</strong></td>
      </tr>
      <tr>
        <td class="text-right" colspan="2"></td>
        <td class="text-right text-{{($fTotalFavor >= $fTotalContra ? 'success' : 'danger')}}"><strong>${{ number_format( abs($fTotalFavor - $fTotalContra),2,'.',',') }}</strong></td>
      </tr>
    </tfoot>
  </table>
</div>
