@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')
  {{Form::model(@$oMateriaPrima,['url' =>[ ( $sTipoVista == 'crear' ? 'materia_prima/store' : 'materia_prima/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ),'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
  {{ csrf_field() }}
  <div id="accordion">
    <div class="card">
      <a href="javascript:;" class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <strong class="color-fbm-blue">DATOS DE LA MATERIA PRIMA</strong>
      </a>
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"><!--data-parent="#accordion"-->
        <div class="card-body">

          <div class="row">
            <label class="col-sm-2 col-form-label">Concepto</label>
            <div class="col-sm-7">
              {!! Form::text('concepto',null,['id' => 'concepto','class'=>'form-control inputSlim','required','placeholder'=>'Ingrese nombre del concepto']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Medida</label>
            <div class="col-sm-7">
              {!! Form::select('id_medida',$oMedidas,null,['class'=>'form-control inputSlim','required','placeholder'=>'Seleccione la medida...']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Unidades por medida</label>
            <div class="col-sm-7">
              {!! Form::number('unidades',(@$oMateriaPrima->unidades!=null?$oMateriaPrima->unidades:1),['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'Ingrese el número de unidades']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Costo <small>($)</small></label>
            <div class="col-sm-7">
              {!! Form::number('costo',null,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'Costo']) !!}
            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="card">
      <a href="javascript:;" class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
        <strong class="color-fbm-blue">SENSIBILIDADES</strong>
      </a>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo">
        <div class="card-body">

          <div class="row">
            <label class="col-sm-4 col-form-label">Incremento del costo al año <small>(%)</small></label>
            <div class="col-sm-5">
              {!! Form::number('incremento_anual',(@$oMateriaPrima->incremento_anual != null ? $oMateriaPrima->incremento_anual : 0),['class'=>'form-control inputSlim','required','step'=>'any','min'=>0,'max'=>100,'placeholder'=>'0%']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-4 col-form-label">Cantidad en mayoreo</label>
            <div class="col-sm-5">
              {!! Form::number('unidades_mayoreo',(@$oMateriaPrima->unidades_mayoreo != null ? $oMateriaPrima->unidades_mayoreo : 0),['class'=>'form-control inputSlim','required','step'=>'any','min'=>0,'placeholder'=>'0']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-4 col-form-label">Descuento por mayoreo <small>(%)</small></label>
            <div class="col-sm-5">
              {!! Form::number('descuento_mayoreo',(@$oMateriaPrima->descuento_mayoreo != null ? $oMateriaPrima->descuento_mayoreo : 0),['class'=>'form-control inputSlim','required','step'=>'any','min'=>0,'max'=>100,'placeholder'=>'0%']) !!}
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <div class="card-footer">
    {{Form::hidden('id',@$oMateriaPrima->id)}}
    <button type="submit" class="btn btn-success btn-sm ml-auto"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
    <a class="btn btn-secondary btn-sm mr-auto" href="{{url('/materia_prima')}}"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
  </div>
  {!! Form::close() !!}

  <!--script>
  $('#form').submit(function (e) {
    e.preventDefault();
    $.ajax({
      url:  ($("#id").val() > 0 ? '{{url('materia_prima/update')}}' : '{{url('materia_prima/store')}}'),
      type: ($("#id").val() > 0 ? 'PUT' : 'POST'),
      data: $('#form').serializeArray(),
      dataType: "json",
      success: function (result) {
        notificacion( (result.estatus === 1 ? 'Guardado exitoso!' : 'Alerta'),result.mensaje, (result.estatus === 1 ? 'success' : 'error'));
      }
    });
  });
  </script-->
@endsection
