

<center>
  <h3>Costos variables ${{number_format( array_sum( $aCostosProductos )  + $fCostoManoObraPeriodo  ,2,'.',',')}}</h3>
  <a id="btn-concepto" href="javascript:;" class="boton-costosvariables btn btn-sm btn-success" onclick="cambio_costos_variables('concepto')">Concepto</a>
  @if ( $oEmpresa->tipo_sistema == 1 )
    <a id="btn-producto" href="javascript:;" class="boton-costosvariables btn btn-sm btn-secondary" onclick="cambio_costos_variables('producto')">Producto</a>
  @endif
</center>



<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='detalleCostosVariablesG' style="max-height: 400px"></div>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id='costosVariablesG' style="max-height: 400px"></div>


<script>
$("#costosVariablesG").hide();
function cambio_costos_variables(sTipo)
{
  if (sTipo == 'concepto') {
    $("#detalleCostosVariablesG").show();
    $("#costosVariablesG").hide();
  }else {
    $("#detalleCostosVariablesG").hide();
    $("#costosVariablesG").show();
  }
  $(".boton-costosvariables").addClass('btn-secondary').removeClass('btn-success');
  $("#btn-" + sTipo).addClass('btn-success').removeClass('btn-secondary');
}

Highcharts.chart('detalleCostosVariablesG', {
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
      @foreach($aCarritoMateriaPrima as $aMateriaPrima)
      '{{$aMateriaPrima['sNombreMateria']}}',
      @endforeach
      'Costos indirectos',
      'Mano de obra'
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

        @foreach($aCarritoMateriaPrima as $aMateriaPrima)
        @php
        $fCosto = $aMateriaPrima['fCosto'] * (1 - ($aMateriaPrima['fDescuentoMayoreo']/100)) *  $aMateriaPrima['fCantidadTotal'] ;
        @endphp
        {{ round($fCosto,2)}},
        @endforeach
        {{$fTotalCostosIndirectos}},
        {{$fCostoManoObraPeriodo}},
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
