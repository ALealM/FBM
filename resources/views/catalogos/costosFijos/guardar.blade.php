@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

<div id="accordion">
  <div class="card">
    <a href="javascript:;" class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
      <strong class="color-fbm-blue">DATOS DEL COSTO FIJO</strong>
    </a>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body" id="costoFijo{{@$oCostoFijo->id}}" data-info="{{@$oCostoFijo}}" data-descripcion="{{ @$oCostoFijo->concepto }} ${{ number_format(@$oCostoFijo->costo,2,".",",") }}" data-pagos="{{collect(@$aPagos)}}">

        {{Form::model(@$oCostoFijo,['url' =>[ ( $sTipoVista == 'crear' ? 'costos_fijos/store' : 'costos_fijos/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
        {{ csrf_field() }}
        <div class="row">
          <label class="col-sm-2 col-form-label">Concepto</label>
          <div class="col-sm-7">
            {!! Form::text('concepto',null,['id' => 'concepto','class'=>'form-control inputSlim','required','placeholder'=>'Ingrese nombre de costo fijo']) !!}
          </div>
        </div>
        <div class="row">
          <label class="col-sm-2 col-form-label">IVA <small>(%)</small></label>
          <div class="col-sm-7">
            {!! Form::number('iva',(@$oCostoFijo->iva != null ? $oCostoFijo->iva : 16 ),['class'=>'form-control inputSlim','min'=>0,'max'=>100,'step'=>.01,'required']) !!}
          </div>
        </div>
        <div class="row">
          <label class="col-sm-2 col-form-label">Costo <small>($)</small></label>
          <div class="col-sm-7">
            {!! Form::number('costo',null,['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese su monto']) !!}
          </div>
        </div>
        <div class="row">
          <label class="col-sm-2 col-form-label">Periodo</label>
          <div class="col-sm-7">
            {!! Form::select('periodo',$periodos,null,['class'=>'form-control inputSlim','required','placeholder'=>'Seleccione el periodo...']) !!}
          </div>
        </div>
        <div class="row">
          <label class="col-sm-2 col-form-label">Fecha inicial para calculo de pagos</label>
          <div class="col-sm-7">
            {!! Form::date('inicio',( @$oCostoFijo != null ? null : date('Y-m-d') ),['class'=>'form-control inputSlim','required']) !!}
          </div>
        </div>
        <div class="card-footer">
          {{Form::hidden('id',@$oCostoFijo->id)}}
          <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
        </div>
        {!! Form::close() !!}

      </div>
    </div>
  </div>
  @if ($sTipoVista=='editar')
    <div class="card">
      <a href="javascript:;" class="card-header" id="heading2" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
        <strong class="color-fbm-blue">PAGOS</strong>
      </a>
      <div id="collapse2" class="collapse" aria-labelledby="heading2"><!--data-parent="#accordion"-->
        <div class="card-body">
          <div class="mb-2 mt-2">
            <a href="javascript:;" onclick="pago('{{$oCostoFijo->id}}',0,'costoFijo')" class="btn btn-success btn-sm"><i class="fa fa-money mr-2"></i>Pagar</a>
          </div>
          @php $sTipoPago = 'costoFijo'; @endphp
          @include('catalogos.pagos.table')
        </div>
      </div>
    </div>
  @endif
</div>

<div class="card-footer">
  <a class="btn btn-secondary btn-sm mr-auto ml-auto" href="{{url('/costos_fijos')}}"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
</div>


<script>
setTimeout(function(){
if (window.location.hash == '#pagos') {
    $('.collapse').collapse('hide');
    $('#collapse2').collapse('show');
  }
}, 1000);
</script>
@endsection
