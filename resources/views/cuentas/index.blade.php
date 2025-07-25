@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
  <div class="mb-2 mt-2">
    <a href="javascript:;" class="btn btn-success" onclick="edit_cuenta(0)"><i class="fa fa-plus-square mr-2"></i>Crear cuenta</a>
  </div>

  <div id="table_cuentas">
    @include('cuentas.table')
  </div>
@endsection
