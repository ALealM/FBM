@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')

  <div id="accordion">
    <div class="card">
      <a href="javascript:;" class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <strong class="color-fbm-blue">DATOS DEL PRODUCTO</strong>
      </a>
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
        <div class="card-body">
          {{Form::model(@$oProducto,['url' =>[ ( $sTipoVista == 'crear' ? 'productos/store' : 'productos/update' ) ],'method' => ( $sTipoVista == 'crear' ? 'POST' : 'PUT' ), 'class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data', 'onsubmit'=>"return validarProducto();"])}}
          {{ csrf_field() }}
          <div class="col-12 text-center mb-2">
            <img class="user__img" src="{{ asset( ( @$oProducto->imagen != null ) ? 'images/productos/'. $oProducto->imagen : "images/producto_icon.png") }}" style="height: 100px; width: auto;"/>
            <div class="col-lg-6 mr-auto ml-auto">
              {{Form::file('imagen', ['class'=>'form-control', 'accept'=> "image/png, image/jpeg" ] )}}
              <small class="font-italic">Se recomienda un tamaño de 500x500px</small>
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Nombre</label>
            <div class="col-sm-7">
              {!! Form::text('producto',null,['id' => 'nombre', 'class'=>'form-control inputSlim','required','placeholder'=>'Ingrese nombre del producto']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Descripción</label>
            <div class="col-sm-7">
              {!! Form::textarea('descripcion',null,['class'=>'form-control inputSlim','placeholder'=>'Descripción...','rows'=>4]) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Medida</label>
            <div class="col-sm-7">
              {!! Form::select('id_medida',$oMedidas,null,['class'=>'form-control inputSlim','required','placeholder'=>'Seleccione la medida...']) !!}
            </div>
          </div>
          <div class="">
            @include('facturama.elementos.unit_code')
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Tipo</label>
            <div class="col-sm-7">
              {!! Form::select('tipo',[1 => 'Con costos por producto e indirectos',2 => 'Compra-venta'],null,['id'=>'tipo','class'=>'form-control inputSlim','onchange'=>'cambio_tipo()', 'required']) !!}
            </div>
          </div>
          <div class="row">
            <label class="col-sm-2 col-form-label">Precio de venta <small>($)</small></label>
            <div class="col-sm-7">
              {!! Form::number('precio_venta',null,['id'=>'precio_venta','class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'Ingrese precio de venta']) !!}
            </div>
          </div>
          <div class="row costo">
            <label class="col-sm-2 col-form-label">Costo <small>($)</small></label>
            <div class="col-sm-7">
              {!! Form::number('costo',null,['id'=>'costo','class'=>'form-control inputSlim','required','step'=>'any','min'=>0.01,'placeholder'=>'Ingrese el costo']) !!}
            </div>
          </div>
          <div class="">
            @include('facturama.elementos.product_code')
          </div>

          <div class="row">
            <label class="col-sm-2 col-form-label">IVA <small>(%)</small></label>
            <div class="col-sm-7">
              {!! Form::number('iva',(@$oProducto->iva!=null?@$oProducto->iva:16),['id'=>'iva','class'=>'form-control inputSlim','required','step'=>'any','min'=>0,'max'=>100,'step'=>.01]) !!}
            </div>
          </div>
          <div class="card-footer">
            {{Form::hidden('id',@$oProducto->id,['id'=>'id_producto'])}}
            <button type="submit" class="btn btn-success btn-sm ml-auto mr-auto"><i class="fa fa-floppy-o mr-2"></i>Guardar</button>
          </div>
          {!! Form::close() !!}

        </div>
      </div>
    </div>
    <div class="card {{ ( $sTipoVista == 'editar' ? '' : 'd-none') }} tipo-0">
      <a href="javascript:;" class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
        <strong class="color-fbm-blue">COSTOS POR PRODUCTO</strong>
      </a>
      <div id="collapseThree" class="collapse" aria-labelledby="headingThree">
        <div class="card-body">

          <div class="mb-2 mt-2">
            <a href="javascript:;" onclick="agregar_actualizar_costo(0,'por_producto')" class="btn btn-success btn-sm"><i class="fa fa-plus-square mr-2"></i>Agregar</a>
          </div>

          <div class="col-lg-12" id="table_costos_por_producto">
            @include('costeo.productos.table_costos_por_producto')
          </div>

        </div>
      </div>
    </div>
    <div class="card {{ ( $sTipoVista == 'editar' ? '' : 'd-none') }} tipo-0">
      <a href="javascript:;" class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
        <strong class="color-fbm-blue">COSTOS INDIRECTOS</strong>
      </a>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo">
        <div class="card-body">
          <div class="mb-2 mt-2">
            <a href="javascript:;" onclick="agregar_actualizar_costo(0,'indirecto')" class="btn btn-success btn-sm"><i class="fa fa-plus-square mr-2"></i>Agregar</a>
          </div>
          @php
            $sTipoCosto = 'producto';
          @endphp
          <div class="col-lg-12" id="table_costos_indirectos">
            @include('costeo.table_costos_indirectos')
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer">
    <a class="btn btn-secondary btn-sm mr-auto ml-auto" href="{{url('/productos')}}"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>
  </div>

  <script type="text/javascript">

  @if($sTipoVista == 'crear')
  notificacion( 'Ayuda','1. Primero ingresa los datos básicos.\n2. Guarda.\n3.Después agrega los costos del producto.','info');
  @else
  @endif

  if (window.location.hash == '#costos_indirectos') {
    setTimeout(function(){
      $('#collapseTwo').collapse({
        toggle: true
      });
    }, 1000);
  }else if (window.location.hash == '#costos_por_producto') {
    setTimeout(function(){
      $('#collapseThree').collapse({
        toggle: true
      });
    }, 1000);
  }
  cambio_tipo();

  function cambio_tipo()
  {
    if ( $("#tipo").val() == 1) {
      $(".tipo-0").show();
      $(".costo").hide();
      $('#costo').removeAttr("required");
    }else {
      $(".tipo-0").hide();
      $(".costo").show();
      $('#costo').prop("required", true);
    }
  }

  function validarProducto()
  {
    var fTotalIndirectos = parseFloat( $("#total_costos_productos").val() ) + parseFloat( $("#total_costos_indirectos").val() );
    if ( ( parseFloat($("#precio_venta").val()) - fTotalIndirectos) <= 0 ) {
      notificacion( 'Alerta','El producto cuesta más producirlo que venderlo.','error');
      return false;
    }else {
      return true;
    }
  }
</script>
@endsection
