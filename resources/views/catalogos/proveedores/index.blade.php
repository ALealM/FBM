@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

<div class="mb-2 mt-2">
  <a href="{{url('proveedores/nuevo')}}" class="btn btn-success"><i class="fa fa-plus-square mr-2"></i>Nuevo proveedor</a>
</div>

@include('catalogos.proveedores.table')

@endsection
