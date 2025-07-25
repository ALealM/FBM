@if( @count($oCostosPorProducto) > 0 )
  <div class="table-responsive">
    <table class="table table-sm table-striped table-hover"><!--table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table"-->
      <thead>
        <tr>
          <th class="text-center">Concepto</th>
          <th class="text-center">Medida</th>
          <th class="text-center">Costo</th>
          <th class="text-center">Unidades</th>
          <th class="text-center">Costo por producto</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @php( $fTotalPorProducto = 0 )
        @foreach($oCostosPorProducto as $oCostoPorProducto)
          <tr id="costoPorProducto{{ $oCostoPorProducto->id }}" data-info="{{ $oCostoPorProducto->concepto }}">
            <td>
              {{ $oCostoPorProducto->concepto }}
            </td>
            <td class="text-center">
              {{ $oCostoPorProducto->materia_unidades }} unidades por {{ $oCostoPorProducto->medida }}
            </td>
            <td class="text-right">
              <small>$</small>{{ number_format($oCostoPorProducto->costo, 2,'.',',') }}
            </td>
            <td class="text-center">
              {{ $oCostoPorProducto->unidades }}
            </td>
            <td class="text-right">
              <small>$</small>{{ number_format( ($oCostoPorProducto->unidades / $oCostoPorProducto->materia_unidades) * $oCostoPorProducto->costo, 2,'.',',') }}
              @php( $fTotalPorProducto += ($oCostoPorProducto->unidades / $oCostoPorProducto->materia_unidades) * $oCostoPorProducto->costo)
            </td>
            <td>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                  Acción
                </button>
                <div class="dropdown-menu">
                  <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="agregar_actualizar_costo('{{$oCostoPorProducto->id}}','por_producto')"><i class="fa fa-pencil mr-2"></i>Editar</a><br>
                  <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="eliminar_costo('{{$oCostoPorProducto->id}}','por_producto')"><i class="fa fa-times mr-2"></i>Eliminar</a><br>
                </div>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th colspan="4" class="text-right">TOTAL</th>
          <th class="text-right"><small>$</small>{{number_format($fTotalPorProducto,2,".",",")}}<input id="total_costos_productos" type="hidden" value="{{$fTotalPorProducto}}"/></th>
        </tr>
      </tfoot>
    </table>
  </div>
@else
  <center>Sin registros</center>
@endif
