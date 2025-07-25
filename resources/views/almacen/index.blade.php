@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

<div class="mb-2 mt-2 text-white">
  <a class="btn btn-success" onclick="ingresar_almacen(0,'entrada')"><i class="fa fa-plus-square mr-2"></i>Entrada</a>
  <a class="btn btn-danger" onclick="ingresar_almacen(0,'salida')"><i class="fa fa-minus-square mr-2"></i>Salida</a>
</div>

@include('almacen.table')


<script>
var BASE_URL = window.location.protocol + "//" + window.location.host+ "/";

function getMedida() {
  var id_mat = $("#materia").val();
  $.get(BASE_URL + "almacen/getMedida", {id_mat: id_mat}, function (result) {
    $('#medida').val(result);
  });
}

function ingresar_almacen(iId, sTipo)
{
  var sNombre = ( iId == 0 ? '' : $("#concepto" + iId ).data('info') );
  $("#myModalLabel").html( (sTipo == 'entrada' ? 'Entrada ' : 'Salida ') + ' de almacén <br/><h3 class="mt-0">' + sNombre + '</h3>');
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "GET",
    url: "{{ asset ('almacen/edit') }}",
    data: {
      'iId' : iId,
      'sTipo' : sTipo
    },
    cache: false,
    dataType: "json",
    success: function (result) {
      if(result.estatus === 1){
        $("#myModalBody").html( result.resultado );
      }else {
        $("#myModalBody").html('<div class="text-center">Error</div>');
      }
      $("#myModal").modal();
    },
    error: function (result) {
      console.log("error");
    }
  });
}

function historial_movimientos(iId)
{
  var sNombre = ( iId == 0 ? '' : $("#concepto" + iId ).data('info') );
  $("#myModalLabel").html('Historial de movimientos <br/><h3 class="mt-0">' + sNombre + '</h3>');
  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "GET",
    url: "{{ asset ('almacen/historial_movimientos') }}",
    data: {
      'iId' : iId
    },
    cache: false,
    dataType: "json",
    success: function (result) {
      if(result.estatus === 1){
        $("#myModalBody").html( result.resultado );
      }else {
        $("#myModalBody").html('<div class="text-center">Error</div>');
      }
      $("#myModal").modal();
    },
    error: function (result) {
      console.log("error");
    }
  });
}




</script>
@endsection
