<table class="table table-condensed table-striped table-hover dataTable" role='grid' id="data-table">
  <thead>
    <th class="text-center">Materia</th>
    <th class="text-center">Medida</th>
    <th class="text-center">Cantidad</th>
    <th class="text-center">Precio unitario</th>
    <th></th>
  </thead>
  <tbody>
    @foreach($oAlmacen as $oAlm )
    <tr id="concepto{{$oAlm->id_materia}}" data-info="{{ $oAlm->concepto }}">
      <td class="project-status">
        {{ $oAlm->concepto }}
      </td>
      <td class="text-center">
        {{ $oAlm->medida }}
      </td>
      <td class="text-center">
        {{ number_format($oAlm->cantidad) }}
      </td>
      <td class="text-right">
        <small>$</small>{{ number_format($oAlm->precio,2,".",",") }}
      </td>
      <td class="text-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            Acción
          </button>
          <div class="dropdown-menu">
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="ingresar_almacen({{$oAlm->id_materia}},'entrada')"><i class="fa fa-plus-square mr-2"></i>Entrada</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="ingresar_almacen({{$oAlm->id_materia}},'salida')"><i class="fa fa-minus-square mr-2"></i>Salida</a><br>
            <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="historial_movimientos({{$oAlm->id_materia}})"><i class="fa fa-outdent mr-2"></i>Historial movimientos</a><br>
          </div>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
