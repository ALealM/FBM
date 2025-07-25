@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

<div class="mb-2 mt-2">
  <a href="{{url('costos_fijos/nuevo')}}" class="btn btn-success"><i class="fa fa-plus-square mr-2"></i>Nuevo costo fijo</a>
</div>

@include('catalogos.costosFijos.table')

@endsection
