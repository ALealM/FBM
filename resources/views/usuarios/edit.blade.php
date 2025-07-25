@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

{{Form::model(@$oUsuario,['url' =>[ ( $sTipoVista == 'crear' ? 'usuarios/store' : 'usuarios/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
{{ csrf_field() }}
<div class="row">
  <div class="col-6">
    <div class="row">
      <label class="col-sm-12 col-form-label">Nombre</label>
      <div class="col-sm-12">
        {!! Form::text('name',null,['id' => 'name', 'class'=>'form-control inputSlim','required','placeholder'=>'Nombre del usuario']) !!}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Apellido paterno</label>
      <div class="col-sm-12">
        {!! Form::text('apellido_paterno',null,['class'=>'form-control inputSlim','required','placeholder'=>'Apellido paterno']) !!}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Apellido materno</label>
      <div class="col-sm-12">
        {!! Form::text('apellido_materno',null,['class'=>'form-control inputSlim','placeholder'=>'Apellido materno']) !!}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Puesto en la empresa</label>
      <div class="col-sm-12">
        {!! Form::text('puesto',null,['class'=>'form-control inputSlim','required','placeholder'=>'Puesto']) !!}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Teléfono</label>
      <div class="col-sm-12">
        {!! Form::text('telefono',null,['class'=>'form-control inputSlim','required','placeholder'=>'Teléfono']) !!}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Área</label>
      <div class="col-sm-12">
        {!! Form::text('area',null,['class'=>'form-control inputSlim','required','placeholder'=>'Área']) !!}
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="row">
      <label class="col-sm-12 col-form-label">Correo electrónico</label>
      <div class="col-sm-12">
        {!! Form::email('email',null,['class'=>'form-control inputSlim','required','placeholder'=>'Correo electrónico']) !!}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Nueva contraseña</label>
      <div class="col-sm-12">
        <input name="pass_1" type="password" class="form-control inputSlim" placeholder="Cambiar contraseña">
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Repetir contraseña</label>
      <div class="col-sm-12">
        <input name="pass_2" type="password" class="form-control inputSlim" placeholder="Cambiar contraseña">

      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  {{Form::hidden('id',@$oUsuario->id)}}
  <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
</div>
{!! Form::close() !!}

@endsection
