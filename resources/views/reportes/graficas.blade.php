<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-info">
            <h4 class="card-title">{{ __('Información de las ventas del periodo del '. date("d/m/Y", strtotime($fechai)). ' al '. date("d/m/Y", strtotime($fechaf))) }}</h4>
            <p class="card-category">{{ __('Punto de equilibrio') }}</p>
          </div>
          <div class="card-body ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='equilibrioG' style="max-height: 400px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-success">
            <h4 class="card-title">{{ __('Ventas del periodo') }}</h4>
            <p class="card-category">{{ __('Información de las unidades vendidas por producto') }}</p>
          </div>
          <div class="card-body ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='ventasG' style="max-height: 400px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-primary">
            <h4 class="card-title">{{ __('Variables') }}</h4>
            <p class="card-category">{{ __('Información de los costos variables') }}</p>
          </div>
          <div class="card-body ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='variablesG' style="max-height: 400px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-info">
            <h4 class="card-title">{{ __('Actividad') }}</h4>
            <p class="card-category">{{ __('Información sobre la actividad') }}</p>
          </div>
          <div class="card-body ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='actividadG' style="max-height: 400px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-warning">
            <h4 class="card-title">{{ __('Costos') }}</h4>
            <p class="card-category">{{ __('Información sobre los costos') }}</p>
          </div>
          <div class="card-body ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='costosG' style="max-height: 400px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header card-header-danger">
            <h4 class="card-title">{{ __('Costos Indirectos') }}</h4>
            <p class="card-category">{{ __('Información sobre los costos indirectos') }}</p>
          </div>
          <div class="card-body ">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='indirectosG' style="max-height: 400px"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
Highcharts.setOptions({
  lang: {
    thousandsSep: ',',
    decimalPoint: '.'
  }
});
var colores = ['#00A8CC','#F5D34B','#2ECC71','#9C70FD','#FF525D','#EF8354','#3444AB','#F0649F','#92C489','#D3BA76','#A2919C','#8BB2AA','#C78493','','',''];
var ventasColor = (function () {
  var colors = [], base = Highcharts.getOptions().colors[2], i;
  for (i = 0; i < 20; i += 1) {colors.push(Highcharts.color(base).brighten((i - 7) / 12).get());}
  return colors;
}());
var varColor = (function () {
  var colors = [], base = '#9a47a9', i;
  for (i = 0; i < 20; i += 1) {colors.push(Highcharts.color(base).brighten((i - 2) / 18).get());}
  return colors;
}());
var actColor = (function () {
  var colors = [], base = '#00bcd4', i;
  for (i = 0; i < 20; i += 1) {colors.push(Highcharts.color(base).brighten((i - 2) / 18).get());}
  return colors;
}());
var costColor = (function () {
  var colors = [], base = '#ff9800', i;
  for (i = 0; i < 20; i += 1) {colors.push(Highcharts.color(base).brighten((i - 1) / 8).get());}
  return colors;
}());
var indColor = (function () {
  var colors = [], base = '#f44336', i;
  for (i = 0; i < 20; i += 1) {colors.push(Highcharts.color(base).brighten((i - 1) / 8).get());}
  return colors;
}());

// Build the chart
Highcharts.chart('ventasG', {
  colors: colores,
  legend: {
    enabled: true,
    align: 'right',
    verticalAlign: 'top',
    layout: 'vertical',
    x: 0,
    y: 100
  },
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  title: false,
  tooltip: {
    pointFormat: '{series.name}: <b>{point.y:f} unidades</b>'
  },
  accessibility: {
    point: {
      valueSuffix: '%'
    }
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b><br>{point.percentage:.1f} %',
        connectorColor: 'silver'
      },
      showInLegend: true
    }
  },
  series: [{
    name: 'Ventas',
    data: [
      @foreach($productos as $producto)
      { name: '{{ $producto->producto }}', y: {{ $producto->unidades }}, dataLabels: {distance: 1} },
      @endforeach
    ]
  }],
  credits: {
    enabled: false
  },
});

Highcharts.chart('actividadG', {
  chart: {
    type: 'column'
  },
  colors: colores,
  title: false,
  accessibility: {
    announceNewData: {
      enabled: true
    }
  },
  xAxis: {
    type: 'category'
  },
  legend: {
    enabled: false
  },
  plotOptions: {
    series: {
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: '${point.y:,.1f}'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>${point.y:,.2f}</b><br/>'
  },

  series: [
    {
      name: "Concepto",
      colorByPoint: true,
      data: [
        {
          name: "VENTAS",
          y: {{$totV}}
        },
        {
          name: "COSTO VARIABLE",
          y: {{$totD}}
        },
        {
          name: "UTILIDAD BRUTA",
          y: {{$totV-$totD}}
        },
        {
          name: "COSTO FIJO",
          y: {{$costosF->sum('costo')*$cant}}
        },
        {
          name: "UTILIDAD OPERACIÓN",
          y: {{$totV-$totD-$costosF->sum('costo')*$cant}}
        },
        {
          name: "MOD",
          y: {{$mo->sum('costo')*$cant}}
        }
      ]
    }
  ],
  credits: {
    enabled: false
  },
});

Highcharts.chart('variablesG', {
  chart: {
    type: 'column'
  },
  colors: colores,
  title: false,
  accessibility: {
    announceNewData: {
      enabled: true
    }
  },
  xAxis: {
    type: 'category'
  },
  legend: {
    enabled: false
  },
  plotOptions: {
    series: {
      borderWidth: 0,
      dataLabels: {
        enabled: true,
        format: '${point.y:,.1f}'
      }
    }
  },

  tooltip: {
    headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
    pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>${point.y:,.2f}</b><br/>'
  },

  series: [
    {
      name: "Concepto",
      colorByPoint: true,
      data: [
        @foreach($productos as $producto)
        { name: '{{ $producto->producto }}', y: {{ ($producto->unidades*($producto->indirectos($producto->id)+$mod+$producto->material($producto->id))) }}, dataLabels: {distance: 1} },
        @endforeach
      ]
    }
  ],
  credits: {
    enabled: false
  },
});


Highcharts.chart('indirectosG', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  colors: colores,
  title: {
    text: 'INDIRECTOS'
  },
  tooltip: {
    pointFormat: '<b>{point.percentage:,.1f} %</b>'
  },
  accessibility: {
    point: {
      valueSuffix: '%'
    }
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: ${point.y:.21f}',
        connectorColor: 'silver'
      }
    }
  },
  series: [{
    name: 'Costo',
    data: [
      @foreach($indirectos as $indirecto)
      { name: '{{ $indirecto->concepto }}', y: {{ $indirecto->costo*$indirecto->totPV }}, dataLabels: {distance: 30} },
      @endforeach

    ]
  }],
  credits: {
    enabled: false
  },
});

Highcharts.chart('costosG', {
  chart: {
    plotBackgroundColor: null,
    plotBorderWidth: null,
    plotShadow: false,
    type: 'pie'
  },
  colors: colores,
  title: {
    text: 'COSTOS'
  },
  tooltip: {
    pointFormat: '{series.name}: <b>${point.y:,.2f}</b>'
  },
  accessibility: {
    point: {
      valueSuffix: '%'
    }
  },
  plotOptions: {
    pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
        enabled: true,
        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
        connectorColor: 'silver'
      }
    }
  },
  series: [{
    name: 'Costo',
    data: [
      { name: 'Variables + Indirectos', y: {{$totD}}, dataLabels: {distance: 30} },
      { name: 'Costos fijos', y: {{$costosF->sum('costo')*$cant}}, dataLabels: {distance: 30} }
    ]
  }],
  credits: {
    enabled: false
  },
});
</script>

<!--php
  $i = 0;
  $totVentas = [];
  while( $i <= 30 ){
    $dia = ( $i < 10 ) ? "0" . ($i+1) : ($i+1);
    $totVentas[$i] = 0;
    foreach($productos as $producto){
      $totVentas[$i] += $producto->unidades("2020-03-$dia","2020-03-$dia") * $producto->precio_venta;
    }
    $i++;
  }
  $i = 0;
  $totEgresos = [];
  while($i<=30){
    $dia = ( $i < 10 ) ? "0" . ($i+1) : ($i+1);
    $totEgresos[$i]=0;
    foreach($productos as $producto){
      $totEgresos[$i] += $producto->unidades("2020-03-$dia","2020-03-$dia")*$producto->material($producto->id);
    }
    $i++;
  }
phpend-->




<script>
Highcharts.chart('equilibrioG', {
  colors: ['#43a047','#f44336'],

  title: {
    text: 'Proyecciones de Ventas del periodo del {{date("d/m/Y", strtotime($fechai))}} al {{date("d/m/Y", strtotime($fechaf))}}'
  },

  yAxis: {
    title: {
      text: 'Pesos'
    }
  },

  xAxis: {
    categories: ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31',]
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
    name: 'Total de Ventas',
    data: [
      @if( @$totVentas != null )
        @foreach($totVentas as $tve)
        {{round($tve,2)}},
        @endforeach
      @endif

    ]
  }, {
    name: 'Total de Egresos',
    data: [
      @if( @$totVentas != null )
        @foreach($totEgresos as $teg)
        {{round($teg+$costosF->sum('costo'),2)}},
        @endforeach
      @endif
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
