@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

  <div id="accordion">
    <div class="card">
      <a href="javascript:;" class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <strong class="color-fbm-blue">DATOS DE LA MANO DE OBRA</strong>
      </a>
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"><!--data-parent="#accordion"-->

        <div class="card-body" id="manoObra{{@$oManoDeObra->id}}" data-info="{{@$oManoDeObra}}" data-pagos="{{collect(@$aPagos)}}" data-descripcion="{{@$oManoDeObra->concepto}} ${{ number_format(@$oManoDeObra->costo,2,".",",") }}">
          {{Form::model(@$oManoDeObra,['url' =>[ ( $sTipoVista == 'crear' ? 'mano_de_obra/store' : 'mano_de_obra/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
          {{ csrf_field() }}
          <div class="row">
            <label class="col-sm-2 col-form-label">Nombre del trabajador</label>
            <div class="col-sm-7">
              {!! Form::text('nombre',null,['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese nombre del trabajador']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Puesto</label>
            <div class="col-sm-7">
              {!! Form::text('concepto',null,['id' => 'concepto','class'=>'form-control inputSlim','required','placeholder'=>'Ingrese nombre del puesto']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Grupo</label>
            <div class="col-sm-7">
              {!! Form::select('id_grupo',@$oManoObraGrupos,null,['class'=>'form-control inputSlim','placeholder'=>'-Sin grupo-']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Tipo de contratación</label>
            <div class="col-sm-7">
              {!! Form::select('tipo_contratacion',$aTiposContratacion,null,['id'=>'tipo_contratacion','class'=>'form-control inputSlim','required','onchange'=>'cambio_tipo_contratacion()']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Contratación</label>
            <div class="col-sm-7">
              {!! Form::select('tipo',$aContratacion,null,['id'=>'tipo','class'=>'form-control inputSlim','required','onchange'=>'cambio_tipo()']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Sueldo <small>($)</small></label>
            <div class="col-sm-7">
              {!! Form::number('costo',null,['id'=>'costo','class'=>'form-control inputSlim','required','step'=>.01,'min'=>.01]) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Periodo</label>
            <div class="col-sm-7">
              {!! Form::select('periodo',$periodos,null,['class'=>'form-control inputSlim','required','placeholder'=>'Seleccione el periodo...']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Inicio de labor</label>
            <div class="col-sm-7">
              {!! Form::date('inicio',( @$oManoDeObra != null ? null : date('Y-m-d') ),['class'=>'form-control inputSlim','required']) !!}
            </div>
          </div>
          <div class="row fin">
            <label class="col-sm-2 col-form-label">Fin de labor</label>
            <div class="col-sm-7">
              {!! Form::date('fin',null,['id'=>'fin','class'=>'form-control inputSlim']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Fecha inicial para calculo de pagos</label>
            <div class="col-sm-7">
              {!! Form::date('fecha_inicio_pagos',( @$oManoDeObra != null ? null : date('Y-m-d') ),['class'=>'form-control inputSlim','required']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Prima vacacional <small>(%)</small></label>
            <div class="col-sm-7">
              {!! Form::number('prima_vacacional',(@$oManoDeObra != null ? null : 25 ),['class'=>'form-control inputSlim','required','step'=>.01,'min'=>.01,'placeholder'=>'Prima vacacional']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Días de aguinaldo</label>
            <div class="col-sm-7">
              {!! Form::number('dias_aguinaldo',(@$oManoDeObra != null ? null : 15 ),['class'=>'form-control inputSlim','required','step'=>1,'min'=>0,'placeholder'=>'Dìas de aguinaldo']) !!}
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
    <div id="cardCalculoImpuestos" class="card">
      <a href="javascript:;" class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
        <strong class="color-fbm-blue">CALCULO DE IMPUESTOS</strong>
      </a>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"><!--data-parent="#accordion"-->
        <div class="card-body">
          <div id="divHonorarios">
            <div class="table-responsive">
              <table class="table table-sm table-striped table-hover">
                <thead>
                  <th></th>
                  <th></th>
                </thead>
                <tbody>
                  <tr>
                    <td>Honorarios</td>
                    <td id="value_honorarios" class="text-right"></td>
                  </tr>
                  <tr>
                    <td>IVA</td>
                    <td id="value_iva" class="text-right"></td>
                  </tr>
                  <tr>
                    <td>Subtotal</td>
                    <td id="value_subtotal" class="text-right"></td>
                  </tr>
                  <tr>
                    <td>Retención ISR</td>
                    <td id="value_retencion_isr" class="text-right"></td>
                  </tr>
                  <tr>
                    <td>Retención IVA</td>
                    <td id="value_retencion_iva" class="text-right"></td>
                  </tr>
                  <tr>
                    <td>Total</td>
                    <td id="value_total" class="text-right"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div id="divImpuestosImss">
            @if (@$aImpuestos != null)
              <center>
                <h3>
                  Sueldo diario <strong class="text-success">${{number_format($aImpuestos['fSueldoDiario'],2,'.',',')}}</strong><br>
                  Antigüedad de <strong class="color-fbm-blue">{{$aImpuestos['sAntiguedad']}}</strong><br>
                  SBC <strong class="color-fbm-blue">{{number_format($aImpuestos['aFactor']['fSBC'],2,'.',',')}}</strong><br>
                </h3>
              </center>
              <div class="table-responsive">
                <table class="table table-sm table-striped table-hover">
                  <thead>
                    <th></th>
                    <th></th>
                  </thead>
                  <tbody>
                    @foreach ($aImpuestos['aImpuestosMeses'] as $key => $aMes)
                      <tr>
                        <td class="{{( $key == date('m') ? 'text-success' : '')}}">{{$aMes['sMes']}} {{( $key == date('m') ? '(Actual)' : '')}}</td>
                        <td>
                          ${{number_format($aMes['fImpuestos'],2,'.',',')}}
                          @if ($key == 2)
                            - ${{number_format($aMes['fImpuestosBisiesto'],2,'.',',')}} (En año bisiesto)
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
    @if ($sTipoVista=='editar')
      <div class="card">
        <a href="javascript:;" class="card-header" id="heading3" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
          <strong class="color-fbm-blue">PAGOS</strong>
        </a>
        <div id="collapse3" class="collapse" aria-labelledby="heading3"><!--data-parent="#accordion"-->
          <div class="card-body">
            <div class="mb-2 mt-2">
              <a href="javascript:;" onclick="pago('{{$oManoDeObra->id}}',0,'manoObra')" class="btn btn-success btn-sm"><i class="fa fa-money mr-2"></i>Pagar</a>
            </div>
            @php $sTipoPago = 'manoObra'; @endphp
            @include('catalogos.pagos.table')
          </div>
        </div>
      </div>
    @endif
  </div>
  <div class="card-footer justify-content-center">
    <a class="btn btn-secondary btn-sm" href="{{url('/mano_de_obra')}}"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
  </div>
<script>

setTimeout(function(){
  if (window.location.hash == '#calculo_impuestos') {
    $('.collapse').collapse('hide');
    $('#collapseTwo').collapse('show');
  }else if (window.location.hash == '#pagos') {
    $('.collapse').collapse('hide');
    $('#collapse3').collapse('show');
  }
}, 1000);

cambio_tipo_contratacion();
function cambio_tipo_contratacion()
{
  //'1'=>'Contrato indefinido','2'=>'Contrato temporal'
  if ( $("#tipo_contratacion").val() == 1 ) {
    $(".fin").slideUp();
    $("#fin").prop('required',false);
  }else {
    $(".fin").slideDown();
    $("#fin").prop('required',true);
  }
}

cambio_tipo();
function cambio_tipo()
{
  //['1'=>'Asalariado','2'=>'Asimilado','3'=>'Honorarios','4'=>'Prácticas']
  if ( $("#tipo").val() == 4 || '{{$sTipoVista}}'  == 'crear' ) {
    $("#cardCalculoImpuestos").hide();
  }else {
    $("#cardCalculoImpuestos").show();
    if ( $("#tipo").val() == 1 ) {
      $("#divImpuestosImss").show();
      $("#divHonorarios").hide();
    }else if ( $("#tipo").val() == 2 ) {
      //ISR
      $("#divImpuestosImss").hide();
      $("#divHonorarios").hide();
    }else if ( $("#tipo").val() == 3 ) {
      calcular_impuestos_honorarios();
      $("#divImpuestosImss").hide();
      $("#divHonorarios").show();
    }
  }
}

$('#costo').on('keyup',function ( evt ) {
  if ( $("#tipo").val() == 3 ) {
    calcular_impuestos_honorarios();
  }
});

function calcular_impuestos_honorarios()
{
  var fHonorarios = parseFloat($("#costo").val()).toFixed(2);
  var fIva = parseFloat(fHonorarios * .16).toFixed(2);
  var fRetencionIsr = parseFloat(fHonorarios * .10).toFixed(2);
  var fIvaRetenido = parseFloat(fIva * 2 / 3).toFixed(2);
  var fSubtotal = parseFloat(Number(fHonorarios) + Number(fIva)).toFixed(2);
  var fTotal = parseFloat(Number(fHonorarios) + Number(fIva) - Number(fRetencionIsr) - Number(fIvaRetenido)).toFixed(2);
  $("#value_honorarios").html( "$" + fHonorarios );
  $("#value_iva").html( "$" + fIva );
  $("#value_subtotal").html( "$" + fSubtotal );
  $("#value_retencion_isr").html( "$" + fRetencionIsr );
  $("#value_retencion_iva").html( "$" + fIvaRetenido );
  $("#value_total").html( "$" + fTotal );

}
</script>
@endsection
