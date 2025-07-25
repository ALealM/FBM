

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='equilibrioG' style="max-height: 400px"></div>

<script>
Highcharts.chart('equilibrioG', {
  colors: ['#43a047'],
  title: {
    text: ''
  },
  yAxis: {
    title: {
      text: 'Ventas'
    }
  },
  xAxis: {
    categories: [
      @foreach( array_column($aVentas,'dia') as $sDia)
      '{{$sDia}}',
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
    name: 'Ventas',
    data: [
      @foreach( array_column($aVentas,'unidades_vendidas') as $fUnidades)
      {{ floatval( $fUnidades ) }},
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
