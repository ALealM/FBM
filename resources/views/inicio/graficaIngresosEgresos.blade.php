<center><h3>Ingresos y egresos</h3></center>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='equilibrioIngEgre' style="max-height: 400px"></div>
<script>
Highcharts.chart('equilibrioIngEgre', {
  colors: ['#43a047','#f44336','#FF8A33'],
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
      '',''
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
      name: 'Ingresos',
      data: [
        {{floatval(@$fIngresos)}}
      ]
    },
    {
      name: 'Egresos',
      data: [
        {{floatval(@$fEgresos)}}
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
