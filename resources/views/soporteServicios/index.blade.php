@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')
<div class="row" style="justify-content: center">
  <div class="col-lg-3 col-md-6 col-sm-6">
    <a href="{{url('soporte_servicios/asesoramiento')}}" class="card text-dark" style="height:90%">
      <img class="card-img-top" src="{{ asset('images/cuadro_4.png')}}"/>
      <div class="card-body">
        <h5 class="card-title">ASESORÍA</h5>
        <p class="card-text">Solicita una asesoría por parte del equipo de FBM.</p>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6">
    <a href="{{url('soporte_servicios/licenciamiento')}}" class="card text-dark" style="height:90%">
      <img class="card-img-top" src="{{ asset('images/cuadro_2.png')}}"/>
      <div class="card-body">
        <h5 class="card-title">LICENCIAMIENTO</h5>
        <p class="card-text">Verificar el tipo de servicio adquirido y licencias disponibles.</p>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6">
    <a href="{{url('soporte_servicios/tickets')}}" class="card text-dark" style="height:90%">
      <img class="card-img-top" src="{{ asset('images/cuadro_1.png')}}"/>
      <div class="card-body">
        <h5 class="card-title">TICKETS</h5>
        <p class="card-text">Reportar un problema o acotencimiento del cual se requiera atención de parte de un asesor o técnico.</p>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6">
    <a href="{{url('soporte_servicios/preguntas_frecuentes')}}" class="card text-dark" style="height:90%">
      <img class="card-img-top" src="{{ asset('images/cuadro_3.png')}}"/>
      <div class="card-body">
        <h5 class="card-title">PREGUNTAS FRECUENTES</h5>
        <p class="card-text">Preguntas comunes acerca de FBM.</p>
      </div>
    </a>
  </div>
</div>
@endsection
