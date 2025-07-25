@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

<div class="mb-2 mt-2">
  <a href="{{url('proyectos/nuevo')}}" class="btn btn-success {{ ( $iPermitidos <= 0 ? 'disabled' : '' )}}"><i class="fa fa-plus-square mr-2"></i>Nuevo proyecto</a>
</div>

@if ( $iPermitidos <= 0 )
  <span class="font-italic text-danger"><i class="fa fa-exclamation-triangle mr-2"></i><small>El número de proyectos permitidos ha alcanzado su límite.</small></span><br>
  <span class="font-italic color-fbm-blue"><small>Adquiere un nuevo tipo de licencia para crear más proyectos.</small></span>
@endif

@include('costeo.proyectos.table')

@endsection
