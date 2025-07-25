{!! Html::script('js/highcharts.js') !!}
{!! Html::script('js/highcharts-3d.js') !!}
{!! Html::script('js/modules/data.js') !!}
{!! Html::script('js/modules/exporting.js') !!}
{!! Html::script('js/modules/export-data.js') !!}
{!! Html::script('js/modules/accessibility.js') !!}

<div class="col-lg-9 ml-auto mr-auto" id='equilibrioG' style="max-height: 400px"></div>

<script>
Highcharts.chart('equilibrioG', {
  colors: ['#f44336','#FF8A33','#43a047'],
  title: {
    text: 'Proyección del año'
  },
  yAxis: {
    title: {
      text: 'Pesos'
    }
  },
  xAxis: {
    categories: [
      @foreach($aMeses as $sMes)
      '{{$sMes}}',
      @endforeach
      //'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
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
      @foreach($aMeses as $sMes)
      // floatval( array_sum($aCostosMesProductos[$sMes]) ) + $fCostoFijoMensual ,

      {{ floatval( array_sum($aCostosMesProductos[$sMes]) ) }},
      @endforeach
    ]
  },{
    name: 'Costos fijos',
    data: [
      @foreach($aMeses as $sMes)
      {{ floatval( $fCostoFijoMensual + $fCostoManoObraMensual ) }},
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
