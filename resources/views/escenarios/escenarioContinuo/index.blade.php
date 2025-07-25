@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  @if( $oProductos->count() > 0)
    <form method="GET" action="{{url('escenarios/generar_continuo')}}" accept-charset="UTF-8" class="form-horizontal" id="form-proyecto" enctype="multipart/form-data">

      <h4>Promedio de ventas al mes</h4>
      @include('escenarios.escenarioTemporalidad.table')

      <br>
      <h4>Alza en costos de materia prima</h4>

      <div class="row">
        <label class="col-sm-2 col-form-label">Alza en materia prima <small>(%)</small></label>
        <div class="col-sm-7">
          {!! Form::number('porcentaje_alza_materia_prima',0,['class'=>'form-control inputSlim','required','step'=>.01,'min'=>0,'placeholder'=>'%']) !!}
        </div>
      </div>
      <div class="row">
        <label class="col-sm-2 col-form-label">Mes</label>
        <div class="col-sm-7">
          {!! Form::select('mes_alza_materia_prima',$aMeses,null,['class'=>'form-control inputSlim']) !!}
        </div>
      </div>

      <div class="card-footer">
        <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto text-white"><i class="fa fa-magic mr-2"></i>Generar escenario</button>
      </div>

    </form>
  @else
    <center>Requieres productos para crear escenarios</center>
  @endif

@endsection
