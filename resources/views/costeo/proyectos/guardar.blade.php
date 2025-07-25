@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

<div id="accordion">
  <!--Datos proyecto-->
  <div class="card">
    <a href="javascript:;" class="card-header" id="heading1" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
      <strong class="color-fbm-blue">DATOS DEL PROYECTO</strong>
    </a>
    <div id="collapse1" class="collapse show" aria-labelledby="heading1" ><!--data-parent="#accordion"-->
      <div class="card-body" id="proyecto{{ @$oProyecto->id }}" data-info="{{ @$oProyecto }}" data-presupuesto="{{collect(@$aPresupuesto)}}" data-cobros="{{collect(@$aCobros)}}">
          {{Form::model(@$oProyecto,['url' =>[ ( $sTipoVista == 'crear' ? 'proyectos/store' : 'proyectos/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
          {{ csrf_field() }}
          <div class="row">
            <label class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-7">
              {!! Form::text('nombre',null,['id' => 'nombre', 'class'=>'form-control inputSlim','required','placeholder'=>'Ingrese nombre de proyecto']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Sub proyecto</label>
            <div class="col-sm-7">
              {!! Form::text('sub_proyecto',null,['id' => 'sub_proyecto', 'class'=>'form-control inputSlim','placeholder'=>'Nombre del sub proyecto']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Alcance</label>
            <div class="col-sm-7">
              {!! Form::textarea('alcance',null,['class'=>'form-control inputSlim','placeholder'=>'Alcance...','rows'=>4]) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Lider del proyecto</label>
            <div class="col-sm-7">
              {{ Form::select('id_lider',$aManoDeObra,null,['placeholder'=>'Seleccione a un lider...','class'=>'form-control']) }}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Fecha de inicio</label>
            <div class="col-sm-7">
              {!! Form::date('fecha_inicio',(@$oProyecto->fecha_inicio != null ? $oProyecto->fecha_inicio : date('Y-m-d') ),['class'=>'form-control inputSlim','required']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Fecha de finalización</label>
            <div class="col-sm-7">
              {!! Form::date('fecha_fin',null,['class'=>'form-control inputSlim','required']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Margen de error <small>(%)</small></label>
            <div class="col-sm-7">
              {!! Form::number('margen_error',(@$oProyecto->margen_error!=null ? $oProyecto->margen_error : 0),['id' =>'margen_error','class'=>'form-control inputSlim','min'=>0,'max'=>100,'step'=>.01,'onchange'=>($sTipoVista!='crear'?'calcular_totales()':''),'required']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Margen <small>(%)</small></label>
            <div class="col-sm-7">
              {!! Form::number('margen',(@$oProyecto->margen!=null ? $oProyecto->margen : 0),['id' =>'margen','class'=>'form-control inputSlim','min'=>0,'max'=>100,'step'=>.01,'onchange'=>($sTipoVista!='crear'?'calcular_totales()':''),'required']) !!}
            </div>
          </div>
          <div class="">
            @include('facturama.elementos.product_code')
          </div>
          <div class="">
            @include('facturama.elementos.unit_code')
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">IVA <small>(%)</small></label>
            <div class="col-sm-7">
              {!! Form::number('iva',(@$oProyecto->iva!=null ? $oProyecto->iva : 16),['id' =>'iva','class'=>'form-control inputSlim','min'=>0,'max'=>100,'step'=>.01,'onchange'=>($sTipoVista!='crear'?'calcular_totales()':''),'required']) !!}
            </div>
          </div>
          <div class="card-footer justify-content-center">
            {{Form::hidden('id',@$oProyecto->id,['id'=>'id_proyecto'])}}
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
          </div>
          {!! Form::close() !!}
      </div>
    </div>
  </div>
  <!--Datos proyecto END-->
  @if ($sTipoVista == 'editar')
    <!--Datos proyecto-->
    <div class="card">
      <a href="javascript:;" class="card-header" id="heading2" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse2">
        <strong class="color-fbm-blue">FASES</strong>
      </a>
      <div id="collapse2" class="collapse" aria-labelledby="heading2"><!--data-parent="#accordion"-->
        <div class="card-body">
          <div class="mb-2 mt-2">
            <a href="javascript:;" onclick="agregar_editar_fase(0)" class="btn btn-success btn-sm"><i class="fa fa-plus-square mr-2"></i>Agregar</a>
          </div>
          <div id="table_fases" class="table-responsive">
            @include('costeo.proyectos.table_fases')
          </div>
        </div>
      </div>
    </div>
    <!--Datos proyecto END-->

    <!--Costos variables-->
    <!--div class="card">
    <a href="javascript:;" class="card-header" id="heading3" data-toggle="collapse" data-target="#collapse3" aria-expanded="true" aria-controls="collapse3">
      <strong class="color-fbm-blue">COSTOS VARIABLES</strong>
    </a>

      <div id="collapse3" class="collapse" aria-labelledby="heading3">--data-parent="#accordion"--
        <div class="card-body">
          <div class="mb-2 mt-2">
            <a href="javascript:;" onclick="agregar_costo_variable()" class="btn btn-success btn-sm"><i class="fa fa-plus-square mr-2"></i>Agregar</a>
          </div>
          <div class="col-lg-12" id="table_costos_variables">
            include('costeo.proyectos.table_costos_variables')
          </div>
        </div>
      </div>
    </div-->
    <!--Costos variables END-->

    <!--Costos indirectos-->
    <div class="card">
      <a href="javascript:;" class="card-header" id="heading4" data-toggle="collapse" data-target="#costos_indirectos" aria-expanded="true" aria-controls="costos_indirectos">
        <strong class="color-fbm-blue">COSTOS INDIRECTOS</strong>
      </a>
      <div id="costos_indirectos" class="collapse" aria-labelledby="heading4"><!--data-parent="#accordion"-->
        <div class="card-body">
          <div class="mb-2 mt-2">
            <a href="javascript:;" onclick="agregar_actualizar_costo(0,'indirecto')" class="btn btn-success btn-sm"><i class="fa fa-plus-square mr-2"></i>Agregar</a>
          </div>
          @php
            $sTipoCosto = 'proyecto';
          @endphp
          <div class="col-lg-12" id="table_costos_indirectos">
            @include('costeo.proyectos.table_costos_indirectos')
          </div>
        </div>
      </div>
    </div>
    <!--Costos indirectos END-->

    <!--Cobros-->
    <div class="card">
      <a href="javascript:;" class="card-header" id="heading5" data-toggle="collapse" data-target="#collapse5" aria-expanded="true" aria-controls="collapse5">
        <strong class="color-fbm-blue">COBROS</strong>
      </a>
      <div id="collapse5" class="collapse" aria-labelledby="heading5"><!--data-parent="#accordion"-->
        <div class="card-body">
          @if ($boolPagado==false)
            <div class="mb-2 mt-2">
              <a href="javascript:;" onclick="cobro_proyecto('{{$oProyecto->id}}')" class="btn btn-success btn-sm"><i class="fa fa-money mr-2"></i>Registrar cobro</a>
            </div>
          @endif
          @include('costeo.proyectos.table_cobros')
        </div>
      </div>
    </div>
    <!--Cobros END-->

    <!--Facturas-->
    <div class="card">
      <a href="javascript:;" class="card-header" id="heading6" data-toggle="collapse" data-target="#collapse6" aria-expanded="true" aria-controls="collapse6">
        <strong class="color-fbm-blue">FACTURAS</strong>
      </a>
      <div id="collapse6" class="collapse" aria-labelledby="heading6"><!--data-parent="#accordion"-->
        <div class="card-body">
          <div class="mb-2 mt-2">
            <a href="javascript:;" onclick="factura_proyecto({{$oProyecto->id}})" class="btn btn-success btn-sm"><i class="fa fa-money mr-2"></i>Crear factura</a>
          </div>
          @include('costeo.proyectos.table_facturas')
        </div>
      </div>
    </div>
    <!--Facturas END-->


    @include('costeo.proyectos.table_resumen')
  @endif

</div>
<div class="card-footer">
  <a class="btn btn-secondary btn-sm mr-auto ml-auto" href="{{url('/proyectos')}}"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
</div>

@include('costeo.proyectos.functions')


<script type="text/javascript">
@if($sTipoVista == 'crear')
notificacion( 'Ayuda','1. Primero ingresa los datos básicos y guarda.\n2. Después agrega las fases del proyecto.','info');
@endif
setTimeout(function(){
  if (window.location.hash == '#costos_variables') {

    //$('.collapse').collapse("hide");
    //$('#collapse3').collapse("show");
  }else if ( window.location.hash == '#fases' ) {
    $('.collapse').collapse("hide");
    $('#collapse2').collapse("show");
  }else if (window.location.hash == '#costos_indirectos') {
    $('.collapse').collapse("hide");
    $('#costos_indirectos').collapse("show");
  }else if (window.location.hash == '#cobros') {
    $('.collapse').collapse("hide");
    $('#collapse5').collapse("show");
  }else if (window.location.hash == '#facturas') {
    $('.collapse').collapse("hide");
    $('#collapse6').collapse("show");
  }
}, 1000);

@if ($sTipoVista != 'crear')
  calcular_totales();
@endif


</script>
@endsection
