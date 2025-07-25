<div>
  <div class="row">
    <label class="col-lg-12 col-form-label">Uso del CFDI <small>(SAT)</small></label>
    <div class="col-sm-7">
      {!! Form::text('cfdiUse_receiver',null,['id'=>'cfdiUse_receiver','class'=>'form-control inputSlim']) !!}
    </div>
    <div>
      <a id="boton_buscar_cfdi_use" href="javascript:" class="btn btn-secondary btn-sm" onclick="{  $('#divBusquedaCfdiUse').slideDown() }"><i class="fa fa-search mr-2"></i>Buscar usos del CFDI</a>
    </div>
  </div>


  <div id="divBusquedaCfdiUse" class="m-2">
    <div class="row">
      <div class="col-lg-12 bg-light" style="max-height:150px; overflow: scroll;">
        <table>
          <tbody id="tbodyResultadosUnitCode"></tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<script>
  $("#divBusquedaCfdiUse").hide();

  $('#boton_buscar_cfdi_use').on('click',function ( evt ) {
    if ($("#rfc_receiver").val().length > 3) {
      buscar_cfid_use();
    }else {
      $("#divBusquedaCfdiUse").html('<center><span class="text-danger">Se debe ingresar correctamente el RFC del receptor</span></center>');
    }
  });

  function buscar_cfid_use()
  {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "GET",
      url: "{{ asset ('facturama/get_usos_cfdi') }}",
      data: {
        'rfc' : $("#rfc_receiver").val(),
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
              '<td>' + aResultado['Value'] + '</td>' +
              '<td>' +
                '<a href="javascript:;" class="btn btn-success btn-sm mr-2 ml-2" onclick="agregar_cfdi_use(\''+aResultado['Value']+'\')"><i class="fa fa-plus"></i></a>'+
              '</td>' +
            '</tr>';
          }
          $("#divBusquedaCfdiUse").html(sHtml);
        }else {
          notificacion("Alerta","Error al consultar usos del CFDI.","error");
        }
      },
      error: function (result) {
        console.log("error");
      }
    });
  }

  function agregar_cfdi_use(sUso)
  {
    $("#cfdiUse_receiver").val(sUso);
    $('#divBusquedaCfdiUse').slideUp();
  }
</script>
