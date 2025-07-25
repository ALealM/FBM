@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  @if( @$oProductos != null)
    @include('proyecciones.calculoAnualIncremento.table')
  @endif

  @if( @$aCostosProductos != null)

    <h3>En el año tendrás los siguientes números</h3>
    @include('proyecciones.calculoAnual.tableTotales')
    <br>
    <center><a href="javascript:;" class="btn btn-success" onclick="generar_valuacion()"><i class="fa fa-magic mr-2"></i>Generar valuación</a></center>
    <br>
    <h3>Con las siguientes ventas</h3>
    @include('proyecciones.calculoAnual.graficaCostosProductos')
    @include('proyecciones.calculoAnual.tableDetalleProductos')

    @include('proyecciones.valuacion.valuacionJs')
  @endif



@endsection
