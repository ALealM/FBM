{{Form::model(null,['url' =>[ 'almacen/store' ],'method' => 'POST', 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
{{ csrf_field() }}
<div class="row">
  <label class="col-sm-3 col-form-label">Materia</label>
  <div class="col-sm-9">
    {!! Form::select('id_materia', @$oMateriasPrima, @$oMateriaPrima->id, ['class'=>'form-control inputSlim','required','placeholder'=>'Seleccione la materia prima...','id'=>'materia','onchange'=>'getMedida()', 'disabled' => ( @$oMateriaPrima->id != null ? true : false ) ]) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label">Medida</label>
  <div class="col-sm-9">
    {!! Form::text('medida', @$oMateriaPrima->medida, ['class'=>'form-control inputSlim','readonly','id'=>'medida']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label">Cantidad <small>(unidades)</small></label>
  <div class="col-sm-9">
    {!! Form::number('cantidad', null, ['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese la cantidad']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label">Precio unitario <small>($)</small></label>
  <div class="col-sm-9">
    {!! Form::number('precio', @$oMateriaPrima->precio, ['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese el costo']) !!}
  </div>
</div>
<br>
<br>
@if( @$sTipo != 'salida')
<div class="row">
  <label class="col-sm-3 col-form-label">Proveedor</label>
  <div class="col-sm-9">
    {!! Form::select('id_proveedor', $oProveedores, null, ['class'=>'form-control inputSlim','placeholder'=>'Seleccione al proveedor...' ]) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label">Costo <small>($)</small></label>
  <div class="col-sm-9">
    {!! Form::number('costo', null, ['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese el costo']) !!}
  </div>
</div>
@else
{{Form::hidden('id_proveedor', null )}}
{{Form::hidden('precio', 0 )}}
@endif
<div class="row">
  <label class="col-sm-3 col-form-label">Fecha del movimiento</label>
  <div class="col-sm-9">
    {!! Form::date('fecha', date('Y-m-d'), ['class'=>'form-control inputSlim','required']) !!}
  </div>
</div>
<div class="row">
  <label class="col-sm-3 col-form-label">Observaciones</label>
  <div class="col-sm-9">
    {!! Form::textarea('observaciones', null, ['class'=>'form-control', 'rows' => 2 ]) !!}
  </div>
</div>
<div class="card-footer text-center">
  @if( @$iId > 0)
  {{Form::hidden('id_materia', @$iId )}}
  @endif
  {{Form::hidden( 'tipo', $sTipo )}}
  <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-floppy-o mr-2"></i>Agregar</button>
  <a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
</div>
{!! Form::close() !!}
