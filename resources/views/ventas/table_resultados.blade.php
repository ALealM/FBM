@if ($oProductos->count()>0)
  <table class="table table-hover">
    <tbody>
      @foreach ($oProductos as $oProducto)
        <tr id="productoResultado{{$oProducto->id}}" data-info="{{$oProducto}}">
          <td class="">{{$oProducto->producto}}</td>
          <td class="text-center">{{$oProducto->nombre_medida}}</td>
          <td class="text-right">${{number_format($oProducto->precio_venta,2,'.',',')}}</td>
          <td class="text-center"><a class="btn btn-sm btn-success btn-round btn-just-icon text-white" onclick="agregar_carrito({{$oProducto->id}})"><i class="fa fa-cart-plus"></i></a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
@else
  <center class="font-italic">Sin resultados</center>
@endif
<script>
  function agregar_carrito(iId)
  {
    aProducto = $("#productoResultado"+iId).data('info');
    if ( $("#producto"+iId).length > 0 ) {
      $("#cantidad"+iId).val( parseFloat($("#cantidad"+iId).val()) + 1 );
    }else {
      $("#tbodyCarrito").append(
        '<tr id="producto'+iId+'" class="producto" data-info=\''+JSON.stringify(aProducto)+'\'>' +
          '<td><img class="user__img" src="{{ asset('')}}'+( aProducto['imagen']!='' ? 'images/productos/' + aProducto['imagen'] : "images/producto_icon.png")+'" style="height: 50px; width: auto;"/></td>' +
          '<td>'+aProducto['producto']+'</td>' +
          '<td class="text-right">'+formatter.format(aProducto['precio_venta'])+'</td>' +
          '<td class="text-center">'+aProducto['nombre_medida']+'</td>' +
          '<td class="text-center">' +
          '<input id="cantidad'+iId+'" class="form-control inputSlim" required="" step="any" min="0.01" name="cantidad" type="number" onchange="calcular_total_venta()" value="1">' +
          //Form::number('cantidad',1,['id'=>'cantidad','class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01])
          '</td>' +
          '<td>' +
            '<a class="btn btn-sm btn-danger btn-round btn-just-icon text-white" onclick="quitar_carrito('+iId+')"><i class="fa fa-minus-circle"></i></a>' +
          '</td>' +
        '</tr>'
      );
    }
    $("#table_resultados").html('');
    calcular_total_venta();
  }

  function quitar_carrito(iId)
  {
    console.log(iId);
    $("#producto"+iId).remove();
    calcular_total_venta();
  }

  
</script>
