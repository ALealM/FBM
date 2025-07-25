@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')
<div class="row">
  <div class="col-lg-3 col-md-6 col-sm-6 ml-auto">
    <a href="{{url('costos_fijos')}}" class="card text-dark">
      <img class="card-img-top" src="{{ asset('images/cuadro_costos_1.png')}}"/>
      <div class="card-body">
        <h5 class="card-title">COSTOS FIJOS</h5>
        <p class="card-text">Administra los costos fijos.</p>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 mr-auto">
    <a href="{{url('mano_de_obra')}}" class="card text-dark">
      <img class="card-img-top" src="{{ asset('images/cuadro_costos_3.png')}}"/>
      <div class="card-body">
        <h5 class="card-title">MANO DE OBRA (MOD)</h5>
        <p class="card-text">Administra la mano de obra.</p>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 ml-auto">
    <a href="{{url('materia_prima')}}" class="card text-dark">
      <img class="card-img-top" src="{{ asset('images/cuadro_materia_1.png')}}"/>
      <div class="card-body">
        <h5 class="card-title">MATERIA PRIMA</h5>
        <p class="card-text">Administra la materia prima.</p>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 mr-auto">
    <a href="{{url('costos_indirectos')}}" class="card text-dark">
      <img class="card-img-top" src="{{ asset('images/cuadro_costos_4.png')}}"/>
      <div class="card-body">
        <h5 class="card-title">COSTOS INDIRECTOS</h5>
        <p class="card-text">Administra los costos indirectos.</p>
      </div>
    </a>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 mr-auto">
    <a href="{{url('proveedores')}}" class="card text-dark">
      <img class="card-img-top" src="{{ asset('images/cuadro_proveedor_1.png')}}"/>
      <div class="card-body">
        <h5 class="card-title">PROVEEDORES</h5>
        <p class="card-text">Administra a los proveedores de tu negocio.</p>
      </div>
    </a>
  </div>
</div>
@endsection
