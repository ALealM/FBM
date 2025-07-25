
<center><h3>Costos fijos ${{number_format($fCostoFijoPeriodo ,2,'.',',')}}</h3></center>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='costosFijosG' style="max-height: 400px"></div>

<script>
Highcharts.chart('costosFijosG', {
  colors: ['#FF8A33','#C6C5C5','#f44336','#43a047'],
  chart: {
    type: 'column',
    inverted: true
  },
  title: {
    text: ''
  },
  yAxis: {
    title: {
      text: 'Pesos'
    }
  },
  xAxis: {
    categories: [
      @foreach($oCostosFijos as $oCostoFijo)
      '{{$oCostoFijo->concepto}}',
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
  series: [
    {
      name: 'Costo',
      data: [
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
          {{$fCostoPeriodo}},
        @endforeach
      ]
    }
  ],
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
