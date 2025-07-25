@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

<div class="row">
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-icon">
        <div class="card-icon bg-fbm-blue">
          <i class="fa fa-shopping-cart"></i>
        </div>
        <p class="card-category">Total de ventas hoy</p>
        <h3 class="card-title">$ {{number_format( $fTotalVentasHoy ,2,'.',',' )}}</h3>
      </div>
      <div class="card-footer">
        <div class="stats">
          <i class="material-icons">date_range</i> Hoy
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6">
    <div class="card card-stats">
      <div class="card-header card-header-icon">
        <div class="card-icon bg-fbm-blue">
          <i class="material-icons">local_mall</i>
        </div>
        <p class="card-category">Total de productos en venta</p>
        <h3 class="card-title">{{$iNumeroProductos}}</h3>
      </div>
      <div class="card-footer">
        <div class="stats">
          <i class="material-icons">update</i> Registrados
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card card-chart">
      <div class="card-header">
        <!--div class="ct-chart" id="dailySalesChart"></div-->
        @include('inicio.grafica')
      </div>
      <div class="card-body">
        <h4 class="card-title">Ventas</h4>
        <p class="card-category">
          @if( $aVentas[7]['unidades_vendidas'] >  $aVentas[6]['unidades_vendidas'] )
          <span class="text-success">
            <i class="fa fa-long-arrow-up"></i> {{ round( ($aVentas[7]['unidades_vendidas'] / ($aVentas[6]['unidades_vendidas'] > 0 ? $aVentas[6]['unidades_vendidas'] : 1 ))*100 ) }}%
          </span>
          incremento en ventas.
          @else
          <span>
            Sin incremento en ventas
          </span>
          @endif
        </p>
      </div>
      <div class="card-footer">
        <div class="stats">
          <i class="material-icons">access_time</i> Útimos 7 días
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
