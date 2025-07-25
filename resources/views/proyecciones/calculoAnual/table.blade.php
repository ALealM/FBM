@if ($oProductos->count() > 0)
  <form method="GET" action="{{url('proyecciones/generar_anual')}}" accept-charset="UTF-8" class="form-horizontal" id="form-proyecto" enctype="multipart/form-data">
    {{ csrf_field() }}
    <table class="table-responsive table-striped">
      <thead>
        <tr>
          <th class="text-center">Producto</th>
          @foreach( $aMeses as $key => $sMes)
          <th class="text-center">{{$sMes}}</th>
          @endforeach
        </tr>
      </thead>
      <tbody>
        @foreach($oProductos as $key => $oProducto)
        <tr>
          <td>
            {{ $oProducto->producto }}
          </td>
          @foreach( $aMeses as $keymes => $sMes)
          <td>
            {{ Form::number('unidades[' . $sMes . '_' . $oProducto->id . ']', 0, [ 'class' => 'form-control col-6 mr-auto ml-auto', 'required','step'=>'any','min'=>0]) }}
          </td>
          @endforeach
        </tr>
        @endforeach
      </tbody>
    </table>
    <br>
    <div class="card-footer">
      <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto text-white"><i class="fa fa-magic mr-2"></i>Generar proyección</button>
    </div>
  </form>
@else
  <center>Requieres productos para realizar proyecciones</center>
@endif
