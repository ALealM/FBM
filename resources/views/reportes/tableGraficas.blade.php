<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr></div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='ventasG' style="max-height: 400px"></div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr></div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='variablesG' style="max-height: 400px"></div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr></div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id='actividadG' style="max-height: 400px"></div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id='indirectosG' style="max-height: 400px"></div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><hr></div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id='costosG' style="max-height: 400px"></div>
<script>
Highcharts.setOptions({
    lang: {
            thousandsSep: ',',
            decimalPoint: '.'
        }
});

// Build the chart
Highcharts.chart('ventasG', {
    colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
        return {
            radialGradient: {
                cx: 0.5,
                cy: 0.3,
                r: 0.7
            },
            stops: [
                [0, color],
                [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    }),
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
    title: {
        text: 'VENTAS'
    },
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
                format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                connectorColor: 'silver'
            },
            showInLegend: true
        }
    },
    series: [{
        name: 'Ventas',
        data: [
            @foreach($ventas as $venta)
            { name: '{{ $venta->producto()->producto }}', y: {{ $venta->unidades_vendidas }}, dataLabels: {distance: 1} },
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
    title: {
        text: 'ACTIVIDAD'
    },
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
                    y: {{$costosF->sum('costo')}}
                },
                {
                    name: "UTILIDAD OPERACIÓN",
                    y: {{$totV-$totD-$costosF->sum('costo')}}
                },
                {
                    name: "MOD",
                    y: {{$mo->sum('costo')}}
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
    title: {
        text: 'VARIABLES'
    },
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
            { name: '{{ $producto->producto }}', y: {{ ($producto->venta()->unidades_vendidas*($producto->indirectos($producto->id)+$mod+$producto->material($producto->id))) }}, dataLabels: {distance: 1} },
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
            { name: 'Costos fijos', y: {{$costosF->sum('costo')}}, dataLabels: {distance: 30} }
        ]
    }],
    credits: {
        enabled: false
    },
});
</script>