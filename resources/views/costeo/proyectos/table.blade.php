<!--table class="table tile table-hover table-responsive dataTable" role='grid' id="data-table"-->
<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
<!--table class="table table-responsive table-striped table-hover table-bordered dataTable" role='grid' id="data-table"-->
  <thead>
    <th>Nombre</th>
    <th class="text-center">Alcance</th>
    <th class="text-center">Periodo</th>
    <th class="text-center">Precio venta</th>
    <th class="text-center">Estado</th>
    <th class="text-center">Restante</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oProyectos as $oProyecto)
      @php
        $aPresupuesto = $oProyecto->presupuesto();
        $aCobros = $oProyecto->cobros();
        $fTotalRestante = $aPresupuesto['precio_venta']-$aCobros['total_cobrado'];
        $boolPagado = ($aPresupuesto['precio_venta']==0?false:($fTotalRestante <= 0 ? true : false));
      @endphp
    <tr id="proyecto{{ $oProyecto->id }}" data-info="{{ $oProyecto }}" data-presupuesto="{{collect($aPresupuesto)}}" data-cobros="{{collect($aCobros)}}">
      <td>{{ $oProyecto->nombre }}</td>
      <td class="text-center">{{ substr($oProyecto->alcance, 0,50) }}...</td>
      <td class="text-center">{{ date('d/m/Y',strtotime($oProyecto->fecha_inicio)) }} - {{ date('d/m/Y',strtotime($oProyecto->fecha_fin)) }}</td>

      <td class="text-right">${{number_format($aPresupuesto['precio_venta'],2,'.',',')}}</td>
      <td class="text-center text-{{( $boolPagado ? 'success' : 'danger')}}">
        {{( $boolPagado ? 'Pagado' : 'Por pagar' )}}
      </td>
      <td class="text-right text-{{( $boolPagado ? 'success' : 'danger')}}">
        ${{number_format($fTotalRestante,2,'.',',')}}
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="{{url('/proyectos/editar/'.$oProyecto->id)}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
            <a href="{{url('/proyectos/editar/'.$oProyecto->id)}}#fases" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-tasks mr-2"></i>Fases</a><br>
            <a href="{{url('/proyectos/editar/'.$oProyecto->id)}}#costos_indirectos" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-dollar mr-2"></i>Costos indirectos</a><br>
            <a href="{{url('/proyectos/editar/'.$oProyecto->id)}}#cobros" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-list mr-2"></i>Cobros</a><br>
            @if ($boolPagado==false)
              <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="cobro_proyecto({{$oProyecto->id}})"><i class="fa fa-file mr-2"></i>Registrar cobro</a><br>
            @endif
            <a href="{{url('/proyectos/editar/'.$oProyecto->id)}}#facturas" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-list mr-2"></i>Facturas</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="factura_proyecto({{$oProyecto->id}})"><i class="fa fa-file mr-2"></i>Crear factura</a><br>
            <a href="{{url('/proyectos/imprimir/'.$oProyecto->id)}}" class="col-12 btn btn-secondary btn-sm" target="_blank"><i class="fa fa-print mr-2"></i>Imprimir</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="duplicar_proyecto({{$oProyecto->id}})"><i class="fa fa-clone mr-2"></i>Duplicar</a><br>
            <!--a href="{{url('/proyectos/editar/'.$oProyecto->id)}}#costos_variables" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-dollar mr-2"></i>Costos variables</a><br-->
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_proyecto('{{$oProyecto->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@include('costeo.proyectos.functions')
<script>
function eliminar_proyecto(iId)
{
  var sNombre = $("#proyecto" + iId ).data('info')['nombre'];
  $("#myModalLabel").html('Eliminar proyecto <h3 class="mt-0">' + sNombre + '</h3>');
  $("#myModalBody").html(
    '<form id="form2" method="GET" action="{{url( "proyectos/destroy" )}}" autocomplete="off" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">' +
      '{{ csrf_field() }}' +
      '<strong>' + sNombre + '</strong>' +
      '<p>El proyecto se eliminará y no habrá vuelta atrás.</p>' +
      '<div class="card-footer text-center">' +
        '<input class="d-none" name="id" value="' + iId +'"/>' +
        '<button type="submit" form="form2" class="btn btn-danger btn-sm"><i class="fa fa-times mr-2"></i>Eliminar</button>' +
        '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>' +
      '</div>' +
    '</form>'
  );
  $("#myModal").modal();
}
</script>
