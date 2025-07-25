@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')
<div id="accordion">
  <div class="card">
    <a href="javascript:;" class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
      <strong class="color-fbm-blue">DATOS DEL COSTO INDIRECTO</strong>
    </a>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">
        {{Form::model(@$oCostoIndirecto,['url' =>[ ( $sTipoVista == 'crear' ? 'costos_indirectos/store' : 'costos_indirectos/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
        {{ csrf_field() }}
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
            {!! Form::number('unidades',null,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'Ingrese el número de unidades']) !!}
          </div>
        </div>
        <div class="row">
          <label class="col-sm-2 col-form-label">Costo <small>($)</small></label>
          <div class="col-sm-7">
            {!! Form::number('costo',null,['class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'Costo']) !!}
          </div>
        </div>
        <div class="card-footer">
          {{Form::hidden('id',@$oCostoIndirecto->id)}}
          <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  <a class="btn btn-secondary btn-sm mr-auto ml-auto" href="{{url('/costos_indirectos')}}"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
</div>
@endsection
