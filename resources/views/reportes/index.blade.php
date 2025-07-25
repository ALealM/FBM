@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
<!--script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-3d.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script-->

{!! Html::script('js/highcharts.js') !!}
{!! Html::script('js/highcharts-3d.js') !!}
{!! Html::script('js/modules/data.js') !!}
{!! Html::script('js/modules/exporting.js') !!}
{!! Html::script('js/modules/export-data.js') !!}
{!! Html::script('js/modules/accessibility.js') !!}


<div class="row">
  <label class="col-sm-2 col-form-label">{{ __('Fecha inicial') }}</label>
  <div class="col-sm-7">
    <div class="form-group">
      {{Form::date('inicio',date('Y-m-d'),['class'=>'form-control','id'=>'inicio'])}}
    </div>
  </div>
</div>
<div class="row">
  <label class="col-sm-2 col-form-label">{{ __('Fecha final') }}</label>
  <div class="col-sm-7">
    <div class="form-group">
      {{Form::date('fin',date('Y-m-d'),['class'=>'form-control','id'=>'fin'])}}
    </div>
  </div>
</div>

<div class="card-footer">
  <a class="btn btn-success btn-sm ml-auto mr-auto text-white" onclick="reporte()"><i class="fa fa-eye mr-2"></i>Consultar</a>
</div>

<div id="ventas"></div>

<script>
function reporte()
{
  var BASE_URL = window.location.protocol + "//" + window.location.host + "/";
  var inicio = document.getElementById('inicio').value;
  var fin = document.getElementById('fin').value;
  $("#ventas").empty();
  $.get(BASE_URL + "reportes/getReporte", {'inicio': inicio,'fin': fin}, function (r) {
    if(r == 1){
      $("#ventas").append('<div class="container-fluid">'
      +'<div class="row">'
      +'<div class="col-md-12">'
      +'<div class="card ">'
      +'<div class="card-header card-header-danger">'
      +'<h4 class="card-title text-center">No hay ventas registradas dentro del periodo consultado</h4>'
      +'</div>'
      +'<div class="card-body row">'
      +'</div></div></div></div></div>'
    );
  }
  else{
    $("#ventas").append(r);
  }
});
}
</script>
@endsection
