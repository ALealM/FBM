@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  {!! Html::script('js/highcharts.js') !!}
  {!! Html::script('js/highcharts-3d.js') !!}
  {!! Html::script('js/modules/data.js') !!}
  {!! Html::script('js/modules/exporting.js') !!}
  {!! Html::script('js/modules/export-data.js') !!}
  {!! Html::script('js/modules/accessibility.js') !!}

  {!! Html::style('css/sweet-alert.css') !!}
  {!! Html::script('js/sweet-alert.min.js') !!}

  <center>
    <h3>
      @if ( $oEmpresa->tipo_sistema == 1 )<!--Productos-->
        Ventas
      @else<!--Proyectos-->
        <!--Indicadores-->
      @endif
    </h3>
    <h4 id="h4fechas"></h4>
  </center>

  @if ( $oEmpresa->tipo_sistema == 1 )

    <div class="mb-2 mt-2 text-center">
      <a id="btn-semanal" href="javascript:;" class="boton-temporalidad btn btn-success" onclick="cambio_temporalidad('semanal')">Semana</a>
      <a id="btn-mensual" href="javascript:;" class="boton-temporalidad btn btn-secondary" onclick="cambio_temporalidad('mensual')">Mes</a>
      <a id="btn-semestral" href="javascript:;" class="boton-temporalidad btn btn-secondary" onclick="cambio_temporalidad('semestral')">Semestre</a>
      <a id="btn-anual" href="javascript:;" class="boton-temporalidad btn btn-secondary" onclick="cambio_temporalidad('anual')">Año</a>
      <a id="btn-fechas" href="javascript:;" class="boton-temporalidad btn btn-secondary" onclick="select_fechas('fechas')"><i class="material-icons">date_range</i></a>
    </div>

    <div id="divGraficaVentas" class="row">
      @include('inicio.graficaVentas')
    </div>
  @endif

  <div class="row">

    @if ( $oEmpresa->tipo_sistema == 2 )
      <div id="divGraficaIngresosEgresos" class="col-lg-6">
        @include('inicio.graficaIngresosEgresos')
      </div>
      <div id="divTablesImpuestos" class="col-lg-6" style="max-height:600px; overflow: scroll;">
        @include('inicio.tableImpuestos')
      </div>
      <div id="divTablesFacturacion" class="col-lg-12" style="max-height:600px; overflow: scroll;">
        @include('inicio.tableFacturacion')
      </div>
      <!--div id="divTablesCostosFijos" class="col-lg-6" style="max-height:600px; overflow: scroll;">
        include('inicio.tableCostosFijos')
      </div-->

    @endif

    @if ( $oEmpresa->tipo_sistema == 1 )
      <div id="divGraficaCostosFijos" class="col-lg-6">
        @include('inicio.graficaCostosFijos')
      </div>
    @elseif ( $oEmpresa->tipo_sistema == 2 )

    @endif

    @if ( $oEmpresa->tipo_sistema == 1 )
      <div id="divGraficaDetalleCostosVariables" class="col-lg-6">
        @include('inicio.graficaDetalleCostosVariables')
      </div>

      <div id="divGraficaPuntoEquilibrio" class="col-lg-12">
        @include('inicio.graficaPuntoEquilibrio')
      </div>

      <div id="divTableDetalleProductos" class="col-lg-12">
        @include('inicio.tableDetalleProductos')
      </div>

      <div id="divTableEstadoResultados" class="col-lg-12">
        @include('inicio.tableEstadoResultados')
      </div>
    @endif
  </div>

  <script>
  //cambio_temporalidad('semanal');
  function cambio_temporalidad(sTemporalidad)
  {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "GET",
      url: "{{ asset ('dashboard/generar_calculo_por_ventas') }}",
      data: {
        'sTemporalidad' : sTemporalidad,
        'fecha_inicio' : $("#fecha_inicio").val(),
        'tipo_fechas' : $("#tipo_fechas").val(),
        'sTipo' : 'json'
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){
          $("#divGraficaVentas").html(result.sHtmlGraficaVentas);
          $("#divGraficaCostosFijos").html(result.sHtmlGraficaCostosFijos);
          $("#divGraficaDetalleCostosVariables").html(result.sHtmlGraficaDetalleCostosVariables);
          $("#divGraficaPuntoEquilibrio").html(result.sHtmlGraficaPuntoEquilibrio);
          $("#divTableDetalleProductos").html(result.sHtmlTableDetalleProductos);
          $("#divTableEstadoResultados").html(result.sHtmlTableEstadoResultados);

          $("#h4fechas").html("Del " + result.sFechaInicial + " al " + result.sFechaFinal );

          $(".boton-temporalidad").addClass('btn-secondary').removeClass('btn-success');
          $("#btn-" + sTemporalidad).addClass('btn-success').removeClass('btn-secondary');
        }else {
          notificacion('Alerta',result.mensaje, 'warning');
          $("#divGraficaVentas").html('');
          $("#divGraficaCostosFijos").html(result.sHtmlGraficaCostosFijos);
          $("#divGraficaDetalleCostosVariables").html(result.sHtmlGraficaDetalleCostosVariables);
          $("#divGraficaPuntoEquilibrio").html(result.sHtmlGraficaPuntoEquilibrio);
          $("#divTableDetalleProductos").html('');
          $("#divTableEstadoResultados").html(result.sHtmlTableEstadoResultados);

          $("#h4fechas").html("Del " + result.sFechaInicial + " al " + result.sFechaFinal );

          $(".boton-temporalidad").addClass('btn-secondary').removeClass('btn-success');
          $("#btn-" + sTemporalidad).addClass('btn-success').removeClass('btn-secondary');
        }
      },error: function (result) {console.log("error");}
    });
  }

  function select_fechas(sTemporalidad)
  {
    $("#myModalLabel").html('Selecciona la fecha a consultar');
    $("#myModalBody").html(
        '<br>A partir del:<br>' +
        '{{Form::date('fecha_inicio',date('Y-m-d'),['class'=>'form-control','id'=>'fecha_inicio'])}}' +
        '<br>Mostrar:<br>' +
        '{!! Form::select('tipo_fechas',['semanal'=>'Semana (7 días)', 'mensual'=>'Mes (4 semanas)','anual'=>'Año (12 meses)'],null,['id'=>'tipo_fechas','class'=>'form-control','required']) !!}' +

      '<div class="card-footer text-center">' +
        '<button class="btn btn-success btn-sm" data-dismiss="modal" aria-label="Close" onclick="cambio_temporalidad(\'fechas\')"><i class="fa fa-check mr-2"></i>Generar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>'
    );
    $("#myModal").modal();
  }

  </script>
@endsection
