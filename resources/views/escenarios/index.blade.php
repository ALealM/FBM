@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 ml-auto">
      <a href="{{url('escenarios/calculo_continuo')}}" class="card text-dark">
        <img class="card-img-top" src="{{ asset('images/cuadro_escenario_2.png')}}">
        <div class="card-body">
          <h5 class="card-title">CONTINUO</h5>
          <p class="card-text">Parámetros continuos durante el año.</p>
        </div>
      </a>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 mr-auto">
      <a href="{{url('escenarios/calculo_temporalidad')}}" class="card text-dark">
        <img class="card-img-top" src="{{ asset('images/cuadro_escenario_1.png')}}">
        <div class="card-body">
          <h5 class="card-title">TEMPORALIDAD</h5>
          <p class="card-text">Escenario con parámetros variables en el año.</p>
        </div>
      </a>
    </div>

  </div>

@endsection
