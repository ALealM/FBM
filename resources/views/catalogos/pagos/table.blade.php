<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th>Concepto</th>
    <th class="text-center">Fecha del periodo</th>
    <th class="text-center">Fecha registro</td>
    <th class="text-center">Cuenta</th>
    <th class="text-center">Monto</th>
  </thead>
  <tbody>
    @foreach($oPagos as $oPago)
    <tr id="pago{{$oPago->id}}" data-info="{{$oPago}}">
      <td>
        {{ $oPago->concepto }}
      </td>
      <td class="text-center">
        {{ date('d/m/Y',strtotime($oPago->fecha)) }}
      </td>
      <td class="text-center">
        {{ date('d/m/Y',strtotime($oPago->fecha_registro)) }}
      </td>
      <td class="text-center">
        {{ $oPago->nombre_cuenta }}
      </td>
      <td class="text-right">
        $ {{ number_format($oPago->monto,2,".",",") }}
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="pago('{{$oPago->id_elemento}}','{{$oPago->id}}','{{$sTipoPago}}')"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
            @if ($oPago->pdf != null)
              <a href="{{asset('pagos/get_pdf').'/'.$oPago->id}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-pdf-o mr-2"></i>PDF</a><br>
            @endif
            @if ($oPago->xml != null)
              <a href="{{asset('pagos/get_xml').'/'.$oPago->id}}" class="col-12 btn btn-secondary btn-sm"><i class="fa fa-file-code-o mr-2"></i>xml</a><br>
            @endif
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_pago('{{$oPago->id}}')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@include('catalogos.pagos.functions')
