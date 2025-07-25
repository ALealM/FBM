@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

<div class="text-center col-12">
  <i class="material-icons" style="font-size: 60px;">settings_input_antenna</i>
  <h4>Hubo un <a onclick="ver_error()">error</a>, inténtalo de nuevo o consulta a soporte.<br>{{@$sInfo}}</h4>
  <div id="error_div" class="bg-dark text-white d-none">
    {{@$sError}}
  </div>
</div>

<script type="text/javascript">
  function ver_error()
  {
    console.log("hola");
    $("#error_div").removeClass('d-none');
  }
</script>

@endsection
