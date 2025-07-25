@extends('layouts.app', ['activePage' => @$sActivePage])
@section('content')
{{Form::model(@$oEmpresa,['url'=>['facturama/update'],'method'=>'PUT','class'=>'form-horizontal','id'=>'form', 'autocomplete'=>'off', 'accept-charset'=>'UTF-8', 'enctype'=>'multipart/form-data'])}}
{{ csrf_field() }}
<div class="row justify-content-center">
  <div class="col-12 text-center">
    <img src="{{ asset( 'images/facturama_logo.png' ) }}" style="height: 50px; width: auto"/>
    @if ($oEmpresa->facturama_user != null && $oEmpresa->facturama_user != '')
      <span class="font-italic text-success"><i class="fa fa-check mr-2"></i>
        <small>
          Enlace establecido
        </small>
      </span>
    @endif
    <br>
    <span class="font-italic text-danger"><i class="fa fa-exclamation-triangle mr-2"></i>
      <small>
        El servicio de Facturama es de un tercero, por lo que el sistema de FBM no tiene ninguna responsabilidad sobre el mismo.<br>
        Al enlazar la cuenta de Facturama se podrá obtener información a través del servicio del mismo para generar, consultar y cancelar CFDI's.
        <ul>
          <li>Se requiere adquirir la API web de Facturama (Verifica el panel de Facturama para adquirirla).</li>
          <li>Una vez enlazada la cuenta, puedes remover el enlace cuando sea necesario.</li>
        </ul>
      </small>
    </span>
  </div>
  <div class="col-5">
    <div class="row">
      <label class="col-sm-12 col-form-label">Usuario</label>
      <div class="col-sm-12">
        {!! Form::text('facturama_user',null,['id'=>'facturama_user','class'=>'form-control inputSlim','required']) !!}
      </div>
    </div>
    <div class="row">
      <label class="col-sm-12 col-form-label">Contraseña</label>
      <div class="col-sm-12">
        <input id="facturama_pass" name="facturama_pass" type="password" class="form-control inputSlim" required>
      </div>
    </div>
  </div>
</div>
<div class="card-footer justify-content-center">
  @if ($oEmpresa->facturama_user != null && $oEmpresa->facturama_user != '')
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
          Acción
        </button>
        <div class="dropdown-menu dropdown-menu-left">
          <a href="javascript:;" class="col-12 btn btn-secondary btn-sm" onclick="remover_cuenta()"><i class="fa fa-times mr-2"></i>Remover enlace</a><br>
        </div>
      </div>
    @endif
  <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-superpowers mr-2"></i>Enlazar</button>
</div>
{!! Form::close() !!}

<script>

  function remover_cuenta()
  {
    $("#myModalLabel").html('Remover enlace de Facturama');
    $("#myModalBody").html(
      '<div>' +
        '<p>Al remover las credenciales el enlace a Facturama se interrumpirá.</p>' +
        '<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">' +
          '<div class="modal-body form-horizontal" style="padding-bottom: 0px; padding-top: 0px"><br>' +
            '<div class="text-center">' +
              '<button class="btn btn-danger btn-sm" onclick="destroy()"><i class="fa fa-times mr-2"></i>Remover</button>' +
              '<button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-rotate-left mr-2"></i>Cancelar</button>' +
            '</div>' +
          '</div>' +
        '</div>' +
      '</div>'
    );
    $("#myModal").modal();
  }

  function destroy()
  {
    $.ajax({
      headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
      type: "DELETE",
      url: "{{ asset ('facturama/remover_enlace') }}",
      data: {},
      cache: false,
      dataType: "json",
      success: function (result) {
        $("#myModal").modal();
        if(result.estatus === 1){
          $("#facturama_user").val('');
          $("#facturama_pass").val('');
          notificacion('Enlace',result.mensaje,'success');
        }else {
          notificacion('Alerta',result.mensaje,'warning');
        }
      },
      error: function (result) {
        console.log("error");
      }
    });
  }
</script>
@endsection
