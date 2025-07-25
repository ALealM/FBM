

<center><h3>Costos variables ${{number_format( array_sum( $aCostosProductos )  + $fCostoManoObraPeriodo  ,2,'.',',')}}</h3></center>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='costosVariablesG' style="max-height: 400px"></div>

<script>
Highcharts.chart('costosVariablesG', {
  colors: ['#C6C5C5','#FF8A33','#f44336','#43a047'],
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
      @foreach($aPesosProductos as $aPesoProducto)
      '{{$aPesoProducto['producto']}}',
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
        @foreach($aPesosProductos as $aPesoProducto)
          @php
          $fCosto = round($aPesoProducto['costo'] + ($aPesoProducto['numero_ventas'] * $fMODUnitario),2);
          @endphp
          {{$fCosto}},
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
