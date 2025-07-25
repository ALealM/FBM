@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

{{Form::model(@$oEmpresa,['url' =>[ ( $sTipoVista == 'crear' ? 'empresa/store' : 'empresa/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
{{ csrf_field() }}
<div class="row">
  <div class="col-4 text-center my-auto">
    <img src="{{ asset( ( @$oEmpresa->imagen != null ) ? 'images/empresas/'.$oEmpresa->imagen : "material/img/FBM_LOGO.png") }}" alt="usuario" style="height: 100px; width: auto"/>
    {{Form::file('imagen', ['class'=>'form-control', 'accept'=> "image/png, image/jpeg" ] )}}
  </div>
  <div class="col-8">
    <div class="row">
      <label class="col-sm-12 col-form-label">Nombre de la empresa</label>
      <div class="col-sm-12">
        {!! Form::text('nombre',null,['class'=>'form-control inputSlim','required','placeholder'=>'Nombre de la empresa']) !!}
      </div>
    </div>

    <div class="row">
      <label class="col-sm-12 col-form-label">Dirección</label>
      <div class="col-sm-12">
        {!! Form::text('direccion',null,['class'=>'form-control inputSlim','placeholder'=>'Dirección de la empresa']) !!}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Industria</label>
      <div class="col-sm-12">
        {{ Form::select('industria',['1'=>'Automotriz','2'=>'Comercializadora','3'=>'Comida','4'=>'Hotelería','5'=>'Logística','6'=>'Servicios','7'=>'Transporte','8'=>'Otro...'],null,['placeholder'=>'Seleccione su industria...','class'=>'form-control','required']) }}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Teléfono</label>
      <div class="col-sm-12">
        {!! Form::text('telefono',null,['class'=>'form-control inputSlim','required','placeholder'=>'Teléfono']) !!}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Tamaño de la empresa</label>
      <div class="col-sm-12">
        {{ Form::select('tamano',['1'=>'Micro','2'=>'Pequeña','3'=>'Mediana','4'=>'Grande'],null,['placeholder'=>'Seleccione el tamaño...','class'=>'form-control','required']) }}
      </div>
    </div>
  </div>
</div>
<div class="card-footer">
  {{Form::hidden('id',@$oEmpresa->id)}}
  <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
</div>
{!! Form::close() !!}

@endsection
