@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

<div class="mb-2 mt-2">
  <a href="{{url('mano_de_obra/nuevo')}}" class="btn btn-success {{ ( $iPermitidos <= 0 ? 'disabled' : '' )}}"><i class="fa fa-plus-square mr-2"></i>Nuevo</a>
  <a href="javascript:;" onclick="edit_grupos()" class="btn btn-secondary {{ ( $iPermitidos <= 0 ? 'disabled' : '' )}}"><i class="material-icons mr-2">engineering</i>Grupos</a>
</div>

@if ( $iPermitidos <= 0 )
  <span class="font-italic text-danger"><i class="fa fa-exclamation-triangle mr-2"></i><small>El número de registros para mano de obra ha alcanzado su límite.</small></span><br>
  <span class="font-italic color-fbm-blue"><small>Adquiere un nuevo tipo de licencia para ingresar más registros.</small></span>
@endif

@include('catalogos.manoDeObra.table')
<script>
function edit_grupos()
{
  $("#myModalLabel").html('Administrar grupos de trabajadores <br/><h3 class="mt-0">Grupos de trabajadores</h3>');

  $.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: "GET",
    url: "{{ asset ('mano_de_obra/get_grupos') }}",
    data: {},
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
