<table class="table-responsive table-striped">
  <thead>
    <tr>
      <th class="text-center">Producto</th>
      <th class="text-center">Porcentaje de ventas en el año (W)</th>
    </tr>
  </thead>
  <tbody>
    @foreach($oProductos as $key => $oProducto)
    <tr>
      <td>
        {{ $oProducto->producto }} %
      </td>
      <td>
        {{ Form::number('porcentaje[' . $oProducto->id . ']', 0, ['id' => 'porcentaje' . $oProducto->id, 'class' => 'form-control col-6 mr-auto ml-auto', 'required', 'onchange' => 'cambiar_porcentaje_ventas()']) }}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
