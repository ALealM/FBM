@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

  @if( @$oProductos != null )
    <form method="GET" action="{{url('proyecciones/generar_anual_inverso')}}" accept-charset="UTF-8" class="form-horizontal" id="form-proyecto" enctype="multipart/form-data">
      {{ csrf_field() }}

      <div class="row">
        <div class="col-6">
          @include('proyecciones.calculoAnualInverso.table')
        </div>
        <div class="col-6">
          <label class="col-sm-12 col-form-label h3">Porcentaje de ventas completado</label>
          <center><span id="porcentaje_total_cumplido" class="h1">0 %</span></center>

          <div class="row">
            <label class="col-sm-12 col-form-label">¿Cuantas ganancias deseas tener en el año?</label>
            <div class="col-sm-12">
              {!! Form::number('ganancias',null,['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese la ganancia deseada','step'=>'any','min'=>0, 'onchange' => 'cambiar_porcentaje_ventas()']) !!}
            </div>
          </div>
        </div>
      </div>

      <br>
      <div class="card-footer">
        <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto text-white" onclick="reporte()"><i class="fa fa-magic mr-2"></i>Generar proyección</button>
      </div>
    </form>



  @elseif( @$aCostosProductos != null)
    <div class="mb-2 mt-2">
      <a href="javascript:;" class="btn btn-success" onclick="imprimir()"><i class="fa fa-print mr-2"></i>Imprimir</a>
    </div>
    <h3>En el año tendrás los siguientes números</h3>
    @include('proyecciones.calculoAnualInverso.tableTotales')
    <br>
    <center><a href="javascript:;" class="btn btn-success" onclick="generar_valuacion()"><i class="fa fa-magic mr-2"></i>Generar valuación</a></center>
    <br>
    <h3>Para conseguir ganancias de <span class="text-success">${{ number_format($fTotalGananciaReal ,2,".",",") }}</span> debes de realizar las siguientes ventas en el año </h3>
    <h3>Con margen de utlidad promedio del {{round($fMargenUtilidadPromedio * 100,2)}} %</H3>
    @include('proyecciones.calculoAnualInverso.tableDetalleProductos')

    @include('proyecciones.valuacion.valuacionJs')
  @else
    <center>Requieres productos para realizar proyecciones</center>
  @endif



<script>
  function cambiar_porcentaje_ventas()
  {
    var fVentas = 0;
    @if( @$oProductos != null)
    @foreach($oProductos as $key => $oProducto)
    fVentas += parseFloat( $("#porcentaje" + "{{$oProducto->id}}").val() );
    @endforeach
    @endif

    $("#porcentaje_total_cumplido").html('<span class="' + (fVentas === 100 ? 'text-success' : 'text-danger') + '">' + ( isNaN(fVentas) ? 0 : fVentas ) + ' %</span>');
  }
</script>
@endsection
