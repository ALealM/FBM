

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='equilibrioG' style="max-height: 400px"></div>

<script>
Highcharts.chart('equilibrioG', {
  colors: ['#43a047','#FF8A33','#f44336'],
  chart: {
    type: 'column'
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
  series: [
    {
      name: 'Ventas',
      data: [
        @foreach($aPeriodo as $key => $sPeriodo)
        {{ floatval( array_sum($aIngresosPeriodoProductos[$key]) ) }},
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
