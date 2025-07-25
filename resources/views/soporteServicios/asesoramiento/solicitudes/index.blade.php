@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  @include('soporteServicios.asesoramiento.solicitudes.table')
@endsection
