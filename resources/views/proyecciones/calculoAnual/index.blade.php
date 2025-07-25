@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  @if( @$oProductos != null)
    @include('proyecciones.calculoAnual.table')
  @endif

  @if( @$aCostosProductos != null)
    <div class="mb-2 mt-2">
      <a href="javascript:;" class="btn btn-success" onclick="imprimir()"><i class="fa fa-print mr-2"></i>Imprimir</a>
    </div>
    <h3>En el año tendrás los siguientes números</h3>
    @include('proyecciones.calculoAnual.tableTotales')
    <br>
    <center><a href="javascript:;" class="btn btn-success" onclick="generar_valuacion()"><i class="fa fa-magic mr-2"></i>Generar valuación</a></center>
    <br>
    <h3>Con las siguientes ventas</h3>
    <div class="">
      @include('proyecciones.calculoAnual.graficaCostosProductos')
    </div>
    <br>
    <h3>Margen de utlidad promedio del {{round($fMargenUtilidadPromedio * 100,2)}} %</h3>

    @include('proyecciones.calculoAnual.tableDetalleProductos')

    @include('proyecciones.valuacion.valuacionJs')
  @endif
@endsection
<script>
function imprimir()
{
  window.load = function (){
    //setTimeout(() => { window.print(); }, 2000);
    window.print();

  }();

}
</script>
