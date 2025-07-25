<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th>Concepto</th>
    <th>Fecha</th>
    <th>Fecha registro</td>
    <th>Cuenta</th>
    <th>Monto</th>
  </thead>
  <tbody>
    @foreach($oCobros as $oCobro)
    <tr id="cobro{{$oCobro->id}}" data-info="{{$oCobro}}">
      <td>
        {{ $oCobro->concepto }}
      </td>
      <td class="">
        {{ date('d/m/Y',strtotime($oCobro->fecha)) }}
      </td>
      <td class="">
        {{ date('d/m/Y',strtotime($oCobro->fecha_registro)) }}
      </td>
      <td class="">
        {{ $oCobro->nombre_cuenta }}
      </td>
      <td class="text-right">
        $ {{ number_format($oCobro->monto,2,".",",") }}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
