@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')
<div id="accordion">
  <div class="card">
    <a href="javascript:;" class="card-header" id="heading1" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
      <strong class="color-fbm-blue">DATOS DEL COSTO INDIRECTO</strong>
    </a>
    <div id="collapse1" class="collapse show" aria-labelledby="heading1"><!--data-parent="#accordion"-->
      @php
        //$oCostoIndirecto->costo_total = (@$oCostoIndirectoProyecto->unidades > 0 ? $oCostoIndirectoProyecto->unidades : 1 ) * $oCostoIndirecto->costo;
        if (@$oCostoIndirecto != null ) {
          $oCostoIndirectoProyecto = $oCostoIndirecto->get_costo_indirecto_proyecto();
          $oCostoIndirecto->costo_total = $oCostoIndirecto->costo;
          $oCostoIndirecto->id_costo_indirecto_proyecto = @$oCostoIndirectoProyecto->id;
          $aPagos = ( $oCostoIndirectoProyecto != null ? $oCostoIndirecto->pagos() : [] );
        }

      @endphp
      <div class="card-body" id="costoIndirecto{{@$oCostoIndirecto->id}}" data-descripcion="{{ number_format(@$oCostoIndirecto->unidades,2,".",",") }} unidades de {{ @$oCostoIndirecto->concepto }}" data-info="{{ @$oCostoIndirecto }}" data-pagos="{{ @$oPagos }}">
        {{Form::model(@$oCostoIndirecto,['url' =>[ ( $sTipoVista == 'crear' ? 'costos_indirectos/store' : 'costos_indirectos/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
        {{ csrf_field() }}
        <div class="row">
          <label class="col-sm-2 col-form-label">Concepto</label>
          <div class="col-sm-7">
            {!! Form::text('concepto',null,['id' => 'concepto','class'=>'form-control inputSlim','required','placeholder'=>'Ingrese nombre del concepto']) !!}
          </div>
        </div>
        <div class="row">
          <label class="col-sm-2 col-form-label">Tipo</label>
          <div class="col-sm-7">
            {!! Form::select('tipo',$aTipos,null,['class'=>'form-control inputSlim','required']) !!}
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
        <div class="row">
          <label class="col-sm-2 col-form-label">Estado inicial</label>
          <div class="col-sm-7">
            {!! Form::select('comprado',$aEstadoIncial,null,['class'=>'form-control inputSlim','required']) !!}
          </div>
        </div>
        @include('catalogos.costosIndirectosProyectos.buscadorFasesProyecto')
        <div class="card-footer">
          {{Form::hidden('id',@$oCostoIndirecto->id)}}
          {{Form::hidden('id_costo_indirecto_proyecto',@$oCostoIndirectoProyecto->id)}}
          <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
  @if ( @$oCostoIndirecto->id != null )
    <div class="card">
      <a href="javascript:;" class="card-header" id="heading2" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
        <strong class="color-fbm-blue">PAGOS</strong>
      </a>
      <div id="collapse2" class="collapse" aria-labelledby="heading2">
        <div class="card-body">
          <div class="mb-2 mt-2">
            <a href="javascript:;" onclick="pago('{{$oCostoIndirecto->id}}',0,'costoIndirecto')" class="btn btn-success btn-sm"><i class="fa fa-money mr-2"></i>Pagar</a>
          </div>
          @php $sTipoPago = 'costoIndirecto'; @endphp
          @include('catalogos.pagos.table')
        </div>
      </div>
    </div>
  @endif
</div>
<div class="card-footer">
  <a class="btn btn-secondary btn-sm mr-auto ml-auto" href="{{url('/costos_indirectos')}}"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
</div>
@endsection
<script>
setTimeout(function(){
  if (window.location.hash == '#pagos') {
    $('.collapse').collapse("hide");
    $('#collapse2').collapse("show");
  }
}, 1000);
</script>
