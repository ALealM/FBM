@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  <div class="mb-2 mt-2">
    <a href="javascript:;" class="btn btn-success" onclick="detalle_ticket(0,1)"><i class="fa fa-plus-square mr-2"></i>Nuevo ticket</a>
  </div>

  @include('soporteServicios.tickets.table')
@endsection
