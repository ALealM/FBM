<form id="formModalFactura" onsubmit="event.preventDefault();">
  {{ csrf_field() }}
  <div class="row">
    <label class="col-12 col-form-label">Nombre del receptor</label>
    <div class="col-lg-12">
      {!! Form::text('name_receiver',null,['class'=>'form-control inputSlim','required']) !!}
    </div>
  </div>
  <div class="row">
    <label class="col-12 col-form-label">RFC del receptor</label>
    <div class="col-lg-12">
      {!! Form::text('rfc_receiver',null,['id'=>'rfc_receiver','class'=>'form-control inputSlim','required']) !!}
    </div>
  </div>
  <div class="">
    @include('facturama.elementos.cfdi_use')
  </div>
  <div class="row">
    <label class="col-12 col-form-label">Lugar de expedición <small>(Código postal)</small></label>
    <div class="col-lg-12">
      {!! Form::text('expedition_place',null,['class'=>'form-control inputSlim','required']) !!}
    </div>
  </div>
  <div class="row">
    <label class="col-12 col-form-label">Forma de pago</label>
    <div class="col-lg-12">
      {{ Form::select('payment_method',(@$aFormasPago!=null?$aFormasPago:[]),null,['class'=>'form-control','required']) }}
    </div>
  </div>
  <div class="row mt-2">
    <label class="col-12 col-form-label">Productos/servicios</label>
    <div class="col-lg-12 p-2">
      <table class="table table-striped table-hover">
        <thead>
          <th class="text-center" style="font-size:70%;"><strong>Clave</strong></th>
          <th class="text-center" style="font-size:70%;"><strong>Cantidad</strong></th>
          <th class="text-center" style="font-size:70%;"><strong>Clave unidad</strong></th>
          <th class="text-center" style="font-size:70%;"><strong>Concepto</strong></th>
          <th class="text-center" style="font-size:70%;"><strong>Precio unitario</strong></th>
          <th class="text-center" style="font-size:70%;"><strong>Importe</strong></th>
        </thead>
        @foreach ($aItems as $key => $aItem)
          <tr>
            <td class="text-center">{{$aItem['ProductCode']}}</td>
            <td class="text-center">{{$aItem['Quantity']}}</td>
            <td class="text-center">{{$aItem['UnitCode']}}</td>
            <td class="">{{$aItem['Description']}}</td>
            <td class="text-right">${{number_format($aItem['UnitPrice'],2,'.',',')}}</td>
            <td class="text-right">${{number_format($aItem['Total'],2,'.',',')}}</td>
          </tr>
        @endforeach
      </table>
    </div>
  </div>
  <div class="card-footer text-center">
    {{Form::hidden('items',json_encode($aItems))}}
    @if (@$oProyecto != null)
      {{Form::hidden('id_proyecto',$oProyecto->id)}}
    @endif
    <button type="submit" form="formModalFactura" class="btn btn-success btn-sm" onclick="guardar_factura()"><i class="fa fa-floppy-o mr-2"></i>Generar factura</button>
    <a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Cancelar</a>
  </div>
</form>

<script>

function guardar_factura()
{
  if ( $("#formModalFactura").valid() ) {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "POST",
      url: "{{ asset ('facturama/store_cfdi') }}",
      data: $('#formModalFactura').serializeArray(),
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus === 1){
          notificacion( 'Factura generada','La factura ha sido generada.','success');
          $('#myModal').modal('toggle');
        }else {
          notificacion( 'Error al crear factura',result.mensaje,'danger');
        }
      },error: function (result) {console.log("error");}
    });
  }
}
</script>
