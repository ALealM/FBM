
@extends('layouts.app', ['class' => 'off-canvas-sidebar','activePage' => 'proyectos', 'boolSinLayout'=> true,'title'=>''])
@section('content')

  <div>
    <input id="margen" class="d-none" value="{{$oProyecto->margen}}"/>
    <input id="margen_error" class="d-none" value="{{$oProyecto->margen_error}}"/>
    <input id="iva" class="d-none" value="{{$oProyecto->iva}}"/>
    <table class="table-bordered" style="width:100%;"><!--border: solid; border-width: 2px-->
      <tr>
        <td rowspan="4" style="width: 120px; padding-right: 20px;padding-left: 20px;">
          <img src="{{ asset( ( \Auth::User()->empresa()->imagen != null ) ? 'images/empresas/'. \Auth::User()->empresa()->imagen : "material/img/FBM_LOGO.png") }}" style="height: auto; width: 120px"/>
        </td>
        <td  colspan="2" class="text-center p-2"><h4><strong>PRESUPUESTO DE PROYECTO</strong></h4></td>
      </tr>
      <tr>
        <td class="p-2" ><strong>PROYECTO: </strong>{{$oProyecto->nombre}}</td>
        <td class="p-2" ><strong>SUB-PROYECTO:</strong> {{$oProyecto->sub_proyecto}}</td>
      </tr>
      <tr>
        <td colspan="2" class="p-2" ><strong>LIDER:</strong> {{$oProyecto->nombre_lider}}</td>
      </tr>
      <tr>
        <td colspan="2" class="">
          <p class="m-2">
            <strong>ALCANCE: </strong><br>
            {{$oProyecto->alcance}}
          </p>
        </td>
      </tr>
    </table>
  </div>

  @include('costeo.proyectos.table_fases')

  <div class="col-lg-12" id="table_costos_indirectos">
    @include('costeo.table_costos_indirectos')
  </div>

  @include('costeo.proyectos.table_resumen')
  @include('costeo.proyectos.functions')

  <script type="text/javascript">
  calcular_totales();
  window.load = function (){ setTimeout(() => { window.print(); }, 2000); }();
</script>

@endsection
