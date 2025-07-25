
<div class="table-responsive">
  <table class="table table-sm table-striped table-hover">
    <thead>
      <tr>
        <th class="text-center">Producto</th>
        <th class="text-center">Promedio aproximado de ventas al mes</th>
      </tr>
    </thead>
    <tbody>
      @foreach($oProductos as $key => $oProducto)
        <tr>
          <td>
            {{ $oProducto->producto }}
          </td>
          <td>
            {{ Form::number('unidades_promedio[' . $oProducto->id . ']', 0, [ 'class' => 'form-control col-6 mr-auto ml-auto', 'required','step'=>'any','min'=>0]) }}
          </td>
        </tr>
      @endforeach

    </tbody>
  </table>
</div>
