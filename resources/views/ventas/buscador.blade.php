<div class="col-lg-12">
  <span class="bmd-form-group">
    <div class="input-group no-border">
      <input id="busqueda" type="text" class="form-control" placeholder="Buscar producto..."/>
      <button type="submit" class="btn btn-white btn-round btn-just-icon" onclick="buscar_productos()">
        <i class="material-icons">search</i>
        <div class="ripple-container"></div>
      </button>
    </div>
  </span>
</div>
<div id="table_resultados" class="col-lg-12">
  <!--include('ventas.table_resultados')-->
</div>
<script>

$('#busqueda').on('keyup',function ( evt ) {
  //if( evt.keyCode == 13 ) buscar_productos();
  buscar_productos();
});

function buscar_productos()
{
  if ($("#busqueda").val().trim()!="") {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "GET",
      url: "{{asset("productos/buscador")}}",
      data: {
        'busqueda' : $("#busqueda").val(),
      },
      cache: false,
      dataType: "json",
      success: function (result) {
        if (result.estatus == 1) {
          $("#table_resultados").html(result.resultado);
        }
      },
      error: function (result) {
        console.log("error");
      }
    });
  }else {
    $("#table_resultados").html('');
  }
}
</script>
