

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='puntoEquilibrio' style="max-height: 400px"></div>

<script>
Highcharts.chart('puntoEquilibrio', {
  colors: ['#f44336','#FF8A33','#43a047'],
  title: {
    text: 'Punto de equilibrio'
  },
  yAxis: {
    title: {
      text: 'Pesos'
    }
  },
  xAxis: {
    categories: [
      @foreach($aPeriodo as $sPeriodo)
      '{{$sPeriodo}}',
      @endforeach
    ]
  },
  legend: {
    verticalAlign: 'bottom'
  },
  plotOptions: {
    series: {
      label: {
        connectorAllowed: false
      }
    }
  },
  series: [{
    name: 'Costos por producto',
    data: [
      @foreach($aPeriodo as $key => $sPeriodo)
      {{ floatval( array_sum($aCostosPeriodoProductos[$key]) ) }},
      @endforeach
    ]
  },{
    name: 'Costos fijos + Mano de obra',
    data: [
      @foreach($aPeriodo as $sPeriodo)
      @php
      $fCosto = $fCostoFijoPeriodo + $fCostoManoObraPeriodo;
      switch ($sTemporalidad) {
        case 'semanal':
          $fCosto = $fCosto / 7;
        break;
        case 'mensual':
          $fCosto = $fCosto / 30;
        break;
        case 'semestral':
          $fCosto = $fCosto / 6;
        break;
        default://anual
          $fCosto = $fCosto / 12;
        break;
      }
      @endphp
      {{ round( $fCosto , 2) }},
      @endforeach
    ]
  }, {
    name: 'Ingresos',
    data: [
      @foreach($aPeriodo as $key => $sPeriodo)
      {{ floatval( array_sum($aIngresosPeriodoProductos[$key]) ) }},
      @endforeach
    ]
  }],
  responsive: {
    rules: [{
      condition: {
        maxWidth: 500
      },
      chartOptions: {
        legend: {
          layout: 'horizontal',
          align: 'center',
          verticalAlign: 'bottom'
        }
      }
    }]
  }
});
</script>
