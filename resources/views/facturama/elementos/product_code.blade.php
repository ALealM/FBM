<div>
  <div class="row">
    <label class="col-sm-2 col-form-label">Código del producto/servicio <small>(SAT)</small></label>
    <div class="col-sm-7">
      {!! Form::text('product_code',null,['id'=>'product_code','class'=>'form-control inputSlim']) !!}
    </div>
    <div>
      <a href="javascript:" class="btn btn-secondary btn-sm" onclick="{  $('#divBusquedaProductCode').slideDown() }"><i class="fa fa-search mr-2"></i>Buscar código</a>
    </div>
  </div>
  <div id="divBusquedaProductCode" class="m-2">
    <div class="row">
      <label class="col-12 col-form-label">Buscar código <small>(ingresa una palabra clave del producto/servicio)</small></label>
      <div class="col-lg-12">
        {!! Form::text('buscador_product_code',null,['id'=>'buscador_product_code','class'=>'form-control inputSlim','minlength'=>4]) !!}
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 bg-light" style="max-height:150px; overflow: scroll;">
        <table>
          <tbody id="tbodyResultadosProductCode"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
  $("#divBusquedaProductCode").hide();
  $('#buscador_product_code').on('keyup',function ( evt ) {
    //if( evt.keyCode == 13 ) buscar_productos();
    if ($("#buscador_product_code").val().length > 3) {
      cambio_product_code();
    }
  });

  function cambio_product_code()
  {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "GET",
      url: "{{ asset ('facturama/get_product_code') }}",
      data: {
        'palabra' : $("#buscador_product_code").val(),
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if(result.estatus == 1){
          var sHtml = '';
          for (var i in result.resultado) {
            var aResultado = result.resultado[i];
            sHtml = sHtml +
            '<tr>' +
              '<td>' + aResultado['Name'] + '</td>' +
              '<td>' + aResultado['Value'] +
                '<a href="javascript:;" class="btn btn-success btn-sm mr-2 ml-2" onclick="agregar_codigo('+aResultado['Value']+')"><i class="fa fa-plus"></i></a>'+
              '</td>' +
            '</tr>';
          }
          $("#tbodyResultadosProductCode").html(sHtml);
        }else {
          notificacion("Alerta","Error al consultar código.","error");
        }
      },
      error: function (result) {
        console.log("error");
      }
    });
  }

  function agregar_codigo(sCodigo)
  {
    $("#product_code").val(sCodigo);
    $('#divBusquedaProductCode').slideUp();
  }
</script>
