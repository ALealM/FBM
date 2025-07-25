@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  @if( $oProductos->count() > 0)
    <form method="GET" action="{{url('escenarios/generar_temporalidad')}}" accept-charset="UTF-8" class="form-horizontal" id="form-proyecto" enctype="multipart/form-data">

      <h4>Promedio de ventas al mes</h4>

      @include('escenarios.escenarioTemporalidad.table')

      <div class="row">
        <label class="col-sm-2 col-form-label">Porcentaje de variación de ventas <small>(%)</small></label>
        <div class="col-sm-7">
          {{ Form::number('procentaje_variacion', null, [ 'class' => 'form-control mr-auto ml-auto', 'required','step'=>'any','min'=>0]) }}
        </div>
      </div>


      <br>
      <h4>Demanda aproximada</h4>

      <div class="row">
        <div class="col-8 mr-auto ml-auto">
        @foreach ($aMeses as $key => $sMes)
          <div class="row">
            <div class="col-lg-4 text-left"><strong>{{$sMes}}</strong></div>
            <div class="col-lg-2 col-md-4 col-sm-4">
              {!! Form::checkbox('mes_zero_' . $key,true,false,['id'=>'mes_zero_' . $key,"class"=>'d-none check'.$key,'onchange'=>'cambio_meses_demanda("zero",' . $key . ')']) !!}
              <a id="boton_mes_zero_{{$key}}" class="boton{{$key}} btn btn-sm bg-white text-secondary" onclick="cambio_meses_demanda('zero',{{$key}})">Sin ventas</a>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4">
              {!! Form::checkbox('mes_bajo_' . $key,true,false,['id'=>'mes_bajo_' . $key,"class"=>'d-none check'.$key,'onchange'=>'cambio_meses_demanda("bajo",' . $key . ')']) !!}
              <a id="boton_mes_bajo_{{$key}}" class="boton{{$key}} btn btn-sm bg-white text-secondary" onclick="cambio_meses_demanda('bajo',{{$key}})">Baja</a>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-4">
              {!! Form::checkbox('mes_alto_' . $key,true,false,['id'=>'mes_alto_' . $key,"class"=>'d-none check'.$key,'onchange'=>'cambio_meses_demanda("alto",' . $key . ')']) !!}
              <a id="boton_mes_alto_{{$key}}" class="boton{{$key}} btn btn-sm bg-white text-secondary" onclick="cambio_meses_demanda('alto',{{$key}})">Alta</a>
            </div>
          </div>
        @endforeach
        </div>
      </div>

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

  <script>
  function cambio_meses_demanda(sTipo,iMes)
  {
    if ( $("#mes_" + sTipo + "_" + iMes).is(':checked') ) {
      $(".check"+iMes).prop('checked', false);
      $(".boton"+iMes).removeClass('bg-fbm-blue text-white').addClass('bg-white text-secondary');
    }else {
      $(".check"+iMes).prop('checked', false);
      $("#mes_"+sTipo+"_"+iMes).prop('checked', true);
      $(".boton"+iMes).removeClass('bg-fbm-blue text-white').addClass('bg-white text-secondary');
      $("#boton_mes_"+sTipo+"_" + iMes).removeClass('bg-white text-secondary').addClass('bg-fbm-blue text-white');
    }
  }
  </script>
@endsection
