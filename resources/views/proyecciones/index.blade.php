@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

<center>
  <a href="javascript:;" class="btn btn-success" onclick="generar_valuacion()"><i class="fa fa-magic mr-2"></i>Generar valuación</a>
</center>

<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 ml-auto mr-auto">
      <a href="{{url('proyecciones/calculo_anual')}}" class="card text-dark">
        <img class="card-img-top" src="{{ asset('images/cuadro_1_proyecto.png')}}">
        <div class="card-body">
          <h5 class="card-title">POR NÚMERO DE VENTAS AL MES</h5>
          <p class="card-text">Calcula tus costos y ganancias del año por número de ventas.</p>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 ml-auto mr-auto">
      <a href="{{url('proyecciones/calculo_anual_incremento')}}" class="card text-dark">
        <img class="card-img-top" src="{{ asset('images/cuadro_costos_2.png')}}">
        <div class="card-body">
          <h5 class="card-title">POR NÚMERO DE VENTAS AL MES CON INCREMENTO</h5>
          <p class="card-text">Porcentaje de incremento de ventas al mes.</p>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 ml-auto mr-auto">
      <a href="{{url('proyecciones/calculo_anual_inverso')}}" class="card text-dark">
        <img class="card-img-top" src="{{ asset('images/cuadro_costos_1.png')}}">
        <div class="card-body">
          <h5 class="card-title">POR GANANCIA ESTABLECIDA</h5>
          <p class="card-text">Ingresar la ganancia deseada para calcular el número de ventas a realizar.</p>
        </div>
      </a>
    </div>
</div>

@include('proyecciones.valuacion.valuacionJs')

@endsection
