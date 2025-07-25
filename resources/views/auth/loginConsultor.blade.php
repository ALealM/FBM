@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'login', 'title' => __('Full Business Manager')])
@section('content')
<div class="container" style="height: auto;">
  <div class="row align-items-center">
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
      <form class="form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="card card-login card-hidden mb-3">
          <div class="card-header text-center">
            <h4 class="card-title"><strong>{{ __('Inicio de sesión para consultor') }}</strong></h4>
          </div>
          <!--div class="card-header text-center">
            <div class="logo">
              <img style="width:150px" src="{{ asset('material') }}/img/FBM_LOGO.png">
            </div>
          </div-->
          <div class="card-body">
            <center>
              <img class="user__img" src="{{ ( $oEmpresa->imagen != null ) ? asset('/images/empresas/'.$oEmpresa->imagen) :  asset('images/user_icon.png') }}"
              alt="usuario" style="height: 100px; width: auto; width:3rem;height:3rem;border-radius:50%;margin-right:.8rem"/>
                <h4>{{$oEmpresa->nombre}}</h4>
            </center>

            <p class="card-description text-center">
              Ingrese <strong>correo electrónico</strong> y <strong>contraseña de consultor</strong>
              para poder ingresar a la cuenta de la empresa.
            </p>

            <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="material-icons">email</i>
                  </span>
                </div>
                <input name="login_consultor" class="d-none" value="1"/>
                <input name="id_empresa" class="d-none" value="{{$oEmpresa->id}}"/>
                <input name="id_usuario" class="d-none" value="{{$oEmpresa->id_usuario}}"/>
                <input type="email" name="email" class="form-control" placeholder="{{ __('Correo electrónico...') }}" value="{{ old('email') }}" required/>
              </div>
              @if ($errors->has('email'))
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
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Contraseña...') }}" required>
              </div>
              @if ($errors->has('password'))
              <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
                <strong>{{ $errors->first('password') }}</strong>
              </div>
              @endif
            </div>

          </div>
          <div class="card-footer justify-content-center">
            <button type="submit" class="btn btn-link btn-lg color-fbm-blue">{{ __('Iniciar sesión') }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
