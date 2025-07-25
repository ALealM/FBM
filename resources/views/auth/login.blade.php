@extends('layouts.app', ['class' => 'off-canvas-sidebar', 'activePage' => 'login', 'title' => __('Full Business Manager')])
@section('content')
<div class="container" style="height: auto;">
  <div class="row align-items-center">
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
      <form class="form" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="card card-login card-hidden mb-3">
          <div class="card-header text-center">
            <h4 class="card-title"><strong>{{ __('Inicio de sesión') }}</strong></h4>
          </div>
          <div class="card-header text-center">
            <div class="logo">
              <img style="width:150px" src="{{ asset('material') }}/img/FBM_LOGO.png">
            </div>
          </div>
          <div class="card-body">
            <p class="card-description text-center">{{ __('Ingrese su') }} <strong>correo electrónico</strong> {{ __(' y su ') }}<strong>contraseña</strong> </p>
            <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">
                    <i class="material-icons">email</i>
                  </span>
                </div>
                <input type="email" name="email" class="form-control" placeholder="{{ __('Correo electrónico...') }}" value="{{ old('email') }}" required>
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
      <div class="row">
        <div class="col-6 text-center">
          <a href="{{ route('register') }}" class="text-light">
            <small>{{ __('Crear nueva cuenta') }}</small>
          </a>
        </div>
        <div class="col-6 text-center">
          <a href="{{ url('olvidaste_contrasena') }}" class="text-light">
            <small>¿Olvidaste tu contraseña?</small>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
