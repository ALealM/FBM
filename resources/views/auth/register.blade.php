@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'register', 'title' => __('Full Business Manager')])

@section('content')
  <div class="container" style="height: auto;">
    <div class="row align-items-center">
      <div class="col-lg-6 col-md-6 col-sm-8 ml-auto mr-auto">
        <form id="formRegistro" class="form" method="POST" action="{{ route('register') }}">
          @csrf
          <div class="card card-login card-hidden mb-3">
            <div class="card-header text-center">
              <h4 class="card-title"><strong>Registro</strong></h4>
            </div>
            <div class="card-header text-center">
              <div class="logo">
                <img style="width:150px" src="{{ asset('material') }}/img/FBM_LOGO.png">
              </div>
            </div>
            <div class="col-lg-12">
              <div class="nav-tabs-navigation bg-fbm-blue">
                <div class="nav-tabs-wrapper">
                  <span class="nav-tabs-title"></span>
                  <ul class="nav nav-tabs" data-tabs="tabs">
                    <li class="nav-item mr-auto ml-auto">
                      <a class="nav-link active show" href="#tipo" data-toggle="tab">
                        Tipo de cuenta
                        <div class="ripple-container"></div>
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                    <li class="nav-item mr-auto ml-auto">
                      <a class="nav-link" href="#usuario" data-toggle="tab">
                        Usuario
                        <div class="ripple-container"></div>
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                    <li class="nav-item mr-auto ml-auto">
                      <a class="nav-link" href="#datos" data-toggle="tab">
                        Datos complementarios
                        <div class="ripple-container"></div>
                        <div class="ripple-container"></div>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active show" id="tipo">
                    <div class="bmd-form-group mt-3 tipo_fiscal">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">amp_stories</i>
                          </span>
                        </div>
                        {{ Form::select('tipo_fiscal',['1'=>'Persona moral','2'=>'Persona física'],null,['id'=>'tipo_fiscal','class'=>'valid-tipo form-control','onchange'=>"cambio_tipo_cuenta()",'required']) }}
                      </div>
                    </div>
                    <div class="bmd-form-group mt-3 empresa_empleado">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">splitscreen</i>
                          </span>
                        </div>
                        {{ Form::select('empresa_empleado',['1'=>'Cuenta para empresa','2'=>'Cuenta para empleado'],null,['id'=>'empresa_empleado','class'=>'valid-tipo form-control','onchange'=>'cambio_tipo_cuenta()','required']) }}
                      </div>
                    </div>
                    <div class="bmd-form-group mt-3 tipo_sistema">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">computer</i>
                          </span>
                        </div>
                        {{ Form::select('tipo_sistema',['1'=>'Sistema de productos','2'=>'Sistema de proyectos'],null,['class'=>'valid-tipo form-control','required']) }}
                      </div>
                    </div>
                    <div class="bmd-form-group mt-3 tipo_empleado">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">hail</i>
                          </span>
                        </div>
                        {{ Form::select('tipo_empleado',['1'=>'Asimilado','2'=>'Asalariado','3'=>'Honorarios'],null,['class'=>'valid-tipo form-control','required']) }}
                      </div>
                    </div>
                    <div class="card-footer justify-content-center">
                      <a class="btn btn-link btn-md color-fbm-blue" onclick="siguiente(1)">SIGUIENTE</a>
                    </div>
                  </div>
                  <div class="tab-pane" id="usuario">
                    <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">face</i>
                          </span>
                        </div>
                        <input id="name" type="text" name="name" class="valid-usuario form-control" placeholder="{{ __('Nombre...') }}" value="{{ old('name') }}" required onfocusout="cambiar_ventana(2)">
                      </div>
                      @if ($errors->has('name'))
                        @php
                          $sVentanaError = "usuario";
                        @endphp
                        <div id="name-error" class="error text-danger pl-3" for="name" style="display: block;">
                          <strong>{{ $errors->first('name') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                          </span>
                        </div>
                        <input type="text" name="apellido_paterno" class="valid-usuario form-control" placeholder="Apellido paterno..." value="{{ old('apellido_paterno') }}"/>
                      </div>
                    </div>
                    <div class="bmd-form-group">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                          </span>
                        </div>
                        <input type="text" name="apellido_materno" class="valid-usuario form-control" placeholder="Apellido materno..." value="{{ old('apellido_materno') }}"/>
                      </div>
                    </div>
                    <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">email</i>
                          </span>
                        </div>
                        <input type="email" name="email" class="valid-usuario form-control" placeholder="{{ __('Correo...') }}" value="{{ old('email') }}" required>
                      </div>
                      @if ($errors->has('email'))
                        @php
                          $sVentanaError = "usuario";
                        @endphp
                        <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
                          <strong>{{ $errors->first('email') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">lock_outline</i>
                          </span>
                        </div>
                        <input type="password" name="password" id="password" class="valid-usuario form-control" placeholder="{{ __('Contraseña...') }}" required>
                      </div>
                      @if ($errors->has('password'))
                        @php
                          $sVentanaError = "usuario";
                        @endphp
                        <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
                          <strong>{{ $errors->first('password') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">lock_outline</i>
                          </span>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="valid-usuario form-control" placeholder="{{ __('Confirmar contraseña...') }}" required>
                      </div>
                      @if ($errors->has('password_confirmation'))
                        @php
                          $sVentanaError = "usuario";
                        @endphp
                        <div id="password_confirmation-error" class="error text-danger pl-3" for="password_confirmation" style="display: block;">
                          <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('telefono') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">phone</i>
                          </span>
                        </div>
                        <input type="text" name="telefono" class="valid-usuario form-control" placeholder="{{ __('Teléfono contacto...') }}" value="{{ old('telefono') }}" required>
                      </div>
                      @if ($errors->has('telefono'))
                        @php
                          $sVentanaError = "usuario";
                        @endphp
                        <div id="name-error" class="error text-danger pl-3" for="telefono" style="display: block;">
                          <strong>{{ $errors->first('telefono') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="card-footer justify-content-center">
                      <a class="btn btn-link btn-md color-fbm-blue" onclick="siguiente(2)">SIGUIENTE</a>
                    </div>
                  </div>
                  <div class="tab-pane" id="datos">
                    <div class="bmd-form-group{{ $errors->has('puesto') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">work</i>
                          </span>
                        </div>
                        <input type="text" name="puesto" class="valid-datos form-control" placeholder="{{ __('Puesto...') }}" value="{{ old('puesto') }}" required>
                      </div>
                      @if ($errors->has('puesto'))
                        @php
                          $sVentanaError = "datos";
                        @endphp
                        <div id="name-error" class="error text-danger pl-3" for="puesto" style="display: block;">
                          <strong>{{ $errors->first('puesto') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('area') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">class</i>
                          </span>
                        </div>
                        <input type="text" name="area" class="valid-datos form-control" placeholder="{{ __('Área/Departamento...') }}" value="{{ old('area') }}" required>
                      </div>
                      @if ($errors->has('area'))
                        @php
                          $sVentanaError = "datos";
                        @endphp
                        <div id="name-error" class="error text-danger pl-3" for="area" style="display: block;">
                          <strong>{{ $errors->first('area') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('empresa') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">store</i>
                          </span>
                        </div>
                        <input type="text" name="empresa" class="valid-datos form-control" placeholder="{{ __('Nombre empresa...') }}" value="{{ old('empresa') }}" required>
                      </div>
                      @if ($errors->has('empresa'))
                        @php
                          $sVentanaError = "datos";
                        @endphp
                        <div id="name-error" class="error text-danger pl-3" for="empresa" style="display: block;">
                          <strong>{{ $errors->first('empresa') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('direccion_empresa') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">directions</i>
                          </span>
                        </div>
                        <input type="text" name="direccion_empresa" class="valid-datos form-control" placeholder="{{ __('Dirección empresa...') }}" value="{{ old('direccion_empresa') }}" required>
                      </div>
                      @if ($errors->has('direccion_empresa'))
                        @php
                          $sVentanaError = "datos";
                        @endphp
                        <div id="name-error" class="error text-danger pl-3" for="direccion_empresa" style="display: block;">
                          <strong>{{ $errors->first('direccion_empresa') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('industria') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">apartment</i>
                          </span>
                        </div>
                        {{ Form::select('industria',['1'=>'Automotriz','2'=>'Comercializadora','3'=>'Comida','4'=>'Hotelería','5'=>'Logística','6'=>'Servicios','7'=>'Transporte','8'=>'Otro...'],old('industria'),['placeholder'=>'Seleccione industria...','class'=>'valid-datos form-control','required']) }}
                      </div>
                      @if ($errors->has('industria'))
                        @php
                          $sVentanaError = "datos";
                        @endphp
                        <div id="name-error" class="error text-danger pl-3" for="industria" style="display: block;">
                          <strong>{{ $errors->first('industria') }}</strong>
                        </div>
                      @endif
                    </div>
                    <div class="bmd-form-group{{ $errors->has('tamano') ? ' has-danger' : '' }} mt-3">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text">
                            <i class="material-icons">zoom_out_map</i>
                          </span>
                        </div>
                        {{ Form::select('tamano',['1'=>'Micro','2'=>'Pequeña','3'=>'Mediana','4'=>'Grande'],old('tamano'),['placeholder'=>'Seleccione tamaño...','class'=>'valid-datos form-control','required']) }}
                      </div>
                      @if ($errors->has('tamano'))
                        @php
                          $sVentanaError = "datos";
                        @endphp
                        <div id="name-error" class="error text-danger pl-3" for="tamano" style="display: block;">
                          <strong>{{ $errors->first('tamano') }}</strong>
                        </div>
                      @endif
                    </div>

                    <div class="card-footer justify-content-center">
                      <button type="submit" class="btn btn-success btn-md">REGISTRAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer text-center text-danger">
              <strong>{{strlen($errors->first())>0?'Verifica los datos ingresados.':''}}</strong>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>

  @if (@$sVentanaError != null)
    setTimeout(function(){ $('[href="#{{$sVentanaError}}"]').click(); }, 1000);
  @endif

  function siguiente(iVentanaActual)
  {
    if (iVentanaActual==1 && $(".valid-tipo").valid()) {
      $('[href="#usuario"]').click();
    }else if (iVentanaActual==2 && $(".valid-usuario").valid()) {
      $('[href="#datos"]').click();
    }
  }

  cambio_tipo_cuenta();
  function cambio_tipo_cuenta()
  {
    if( $("#tipo_fiscal").val() == 1 ){//Moral
      $(".empresa_empleado").hide();
      $(".tipo_sistema").show();
      $(".tipo_empleado").hide();
    }else {//Fisica
      $(".empresa_empleado").show();
      if ( $("#empresa_empleado").val() ==  1 ) {//Empresa
        $(".tipo_sistema").show();
        $(".tipo_empleado").hide();
      }else {//Empleado
        $(".tipo_sistema").hide();
        $(".tipo_empleado").show();
      }
    }

  }
</script>
@endsection
