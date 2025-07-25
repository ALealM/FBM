@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

<div class="mb-2 mt-2">
  <a href="{{url('materia_prima/nuevo')}}" class="btn btn-success"><i class="fa fa-plus-square mr-2"></i>Nueva materia prima</a>
</div>

@include('catalogos.materiaPrima.table')

@endsection
