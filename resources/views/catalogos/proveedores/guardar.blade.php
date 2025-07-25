@extends('layouts.app', ['activePage' => @$sActivePage ])
@section('content')

<div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Datos del proveedor
        </button>
      </h5>
    </div>
    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
      <div class="card-body">

        {{Form::model(@$oProveedor,['url' =>[ ( $sTipoVista == 'crear' ? 'proveedores/store' : 'proveedores/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
        {{ csrf_field() }}
        <div class="row">
          <label class="col-sm-2 col-form-label">Nombre</label>
          <div class="col-sm-7">
            {!! Form::text('nombre',null,['id' => 'nombre','class'=>'form-control inputSlim','required','placeholder'=>'Ingrese nombre del proveedor']) !!}
          </div>
        </div>
        <div class="row">
          <label class="col-sm-2 col-form-label">Dirección</label>
          <div class="col-sm-7">
            {!! Form::text('direccion',null,['class'=>'form-control inputSlim','required','placeholder'=>'Ingrese la dirección del proveedor']) !!}
          </div>
        </div>
        <div class="card-footer">
          {{Form::hidden('id',@$oProveedor->id)}}
          <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
        </div>
        {!! Form::close() !!}

      </div>
    </div>
  </div>
</div>

<div class="card-footer">
  <a class="btn btn-secondary btn-sm mr-auto ml-auto" href="{{url('/proveedores')}}"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
</div>

@endsection
