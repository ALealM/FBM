@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

<div class="row">
  <div class="col-lg-3 col-md-6 col-sm-6 ml-auto">
    <a href="{{url('proyectos')}}" class="card text-dark">
      <img class="card-img-top" src="{{ asset('images/cuadro_1_proyecto.png')}}">
      <div class="card-body">
        <h5 class="card-title">POR PROYECTO</h5>
        <p class="card-text">Administra el costeo por proyecto.</p>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 mr-auto">
    <a href="{{url('productos')}}" class="card text-dark">
      <img class="card-img-top" src="{{ asset('images/cuadro_2_producto.png')}}">
      <div class="card-body">
        <h5 class="card-title">POR PRODUCTO</h5>
        <p class="card-text">Administra el costeo por producto.</p>
      </div>
    </a>
  </div>
</div>

@endsection
