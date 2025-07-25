<!--table class="table tile table-hover table-responsive dataTable" role='grid' id="data-table"-->
<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <!--table class="table table-responsive table-striped table-hover table-bordered dataTable" role='grid' id="data-table"-->
  <thead>
    <th>Tipo de asesoría</th>
    <th>Descripción</th>
    <th>Fecha</th>
    <th>Estado</th>
  </thead>
  <tbody>
    @foreach($oSolicitudes as $key => $oSolicitud)
      <tr id="solicitud{{ $oSolicitud->id }}" data-info="{{ $oSolicitud }}">
        <td class="">{{ $oSolicitud->nombre_asesoria }}</td>
        <td class="">{{ substr($oSolicitud->descripcion_asesoria, 0,50) }}...</td>
        <td class="">{{ date('d/m/Y',strtotime($oSolicitud->fecha_registro)) }}</td>
        <td class="">
          @if ($oSolicitud->estado == 1)
            <span class="text-success">Enviada</span>
          @elseif ($oSolicitud->estado == 2)
            <span class="text-success">Aceptada</span>
          @elseif ($oSolicitud->estado == 3)
            <span class="text-danger">Rechazada</span>
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
