{{Form::model(@$oVariable,['url' =>[ ( $sTipoVista == 'crear' ? 'proyectos/store_costo_variable' : 'proyectos/update_costo_variable' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
{{ csrf_field() }}
<div class="col-md-12 col-sm-12 col-xs-12 text-center">
  MOD Nómina <hr style="margin-bottom: 10px; margin-top: 5px">
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right" style="text-align: right">{{ __('Tipo contratación') }}</label>
  <div class="col-sm-7">
    {!! Form::select('tipo_contrato',['1'=>'Honorarios','2'=>'Asimilables','3'=>'Confianza'],null,['class'=>'form-control','required','placeholder'=>'Seleccione tipo de contratación...']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('$') }}</label>
  <div class="col-sm-7">
    {!! Form::number('precio_contrato',null,['class'=>'form-control inputSlim','required']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('Duración en meses durante el proyecto') }}</label>
  <div class="col-sm-7">
    {!! Form::number('duracion_contrato',null,['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese duración de contratación']) !!}
  </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 text-center">
  Materia prima directa <hr style="margin-bottom: 10px; margin-top: 5px">
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('Concepto') }}</label>
  <div class="col-sm-7">
    {!! Form::text('concepto_directa',null,['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese concepto']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('Unidad de medida') }}</label>
  <div class="col-sm-7">
    {!! Form::select('unidad_directa',['1'=>'Pieza','2'=>'Kilo','3'=>'Litro'],null,['class'=>'form-control','required','placeholder'=>'Seleccione unidad de medida...']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('Volumen') }}</label>
  <div class="col-sm-7">
    {!! Form::number('volumen_directa',null,['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese volumen']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('$') }}</label>
  <div class="col-sm-7">
    {!! Form::number('precio_directa',null,['class'=>'form-control inputSlim','required']) !!}
  </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 text-center" style="margin-top: 15px">
  Materia prima indirecta <hr style="margin-bottom: 10px; margin-top: 5px">
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('Concepto') }}</label>
  <div class="col-sm-7">
    {!! Form::text('concepto_indirecta',null,['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese concepto']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('Unidad de medida') }}</label>
  <div class="col-sm-7">
    {!! Form::select('unidad_indirecta',['1'=>'Pieza','2'=>'Kilo','3'=>'Litro'],null,['class'=>'form-control','required','placeholder'=>'Seleccione unidad de medida...']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('Volumen') }}</label>
  <div class="col-sm-7">
    {!! Form::number('volumen_indirecta',null,['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese volumen']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('$') }}</label>
  <div class="col-sm-7">
    {!! Form::number('precio_indirecta',null,['class'=>'form-control inputSlim','required']) !!}
  </div>
</div>
<div class="col-md-12 col-sm-12 col-xs-12 text-center" style="margin-top: 15px">
  Otros <hr style="margin-bottom: 10px; margin-top: 5px">
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('Concepto') }}</label>
  <div class="col-sm-7">
    {!! Form::text('concepto_otros',null,['class'=>'form-control inputSlim','placeholder'=>'Ingrese concepto']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label" style="text-align: right">{{ __('$') }}</label>
  <div class="col-sm-7">
    {!! Form::number('precio_otros',null,['class'=>'form-control inputSlim']) !!}
  </div>
</div>
<div class="card-footer">
  {{Form::hidden('id_proyecto',@$iIdProyecto)}}
  {{Form::hidden('id',@$oVariable->id)}}
  <button type="submit" class="btn btn-success btn-sm ml-auto"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
  <a href="javascript:;" class="btn btn-secondary btn-sm mr-auto" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>
</div>
{!! Form::close() !!}
