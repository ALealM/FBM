{!! Html::script('js/highcharts.js') !!}
{!! Html::script('js/highcharts-3d.js') !!}
{!! Html::script('js/modules/data.js') !!}
{!! Html::script('js/modules/exporting.js') !!}
{!! Html::script('js/modules/export-data.js') !!}
{!! Html::script('js/modules/accessibility.js') !!}

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='equilibrioG' style="max-height: 400px"></div>

<script>
Highcharts.chart('equilibrioG', {
  colors: ['#f44336','#43a047'],
  title: {
    text: 'Proyección del año'
  },
  yAxis: {
    title: {
      text: 'Pesos'
    }
  },
  xAxis: {
    categories: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']
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
    name: 'Costos',
    data: [
      @foreach($aMeses as $sMes)
      {{ floatval( array_sum($aCostosMesProductos[$sMes]) ) + $fCostoFijoMensual }},
      @endforeach
    ]
  }, {
    name: 'Ingresos',
    data: [
      @foreach($aMeses as $sMes)
      {{ floatval( array_sum($aIngresosPeriodoProductos[$sMes]) ) }},
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
