<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Models\Empresas;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Register Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users as well as their
  | validation and creation. By default this controller uses a trait to
  | provide this functionality without requiring any additional code.
  |
  */

  use RegistersUsers;

  /**
  * Where to redirect users after registration.
  *
  * @var string
  */
  protected $redirectTo = '/home';

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('guest');
  }

  /**
  * Get a validator for an incoming registration request.
  *
  * @param  array  $data
  * @return \Illuminate\Contracts\Validation\Validator
  */
  protected function validator(array $data)
  {
    return Validator::make($data, [
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
      'password' => ['required', 'string', 'min:6', 'confirmed'],
    ]);
  }

  /**
  * Create a new user instance after a valid registration.
  *
  * @param  array  $data
  * @return \App\User
  */
  protected function create(array $data)
  {
  
    $oEmpresa = Empresas::create([
      'nombre' => $data['empresa'],
      'direccion' => $data['direccion_empresa'],
      'industria' => $data['industria'],
      //'telefono' => $data['telefono'],
      'tamano' => $data['tamano'],
      'tipo' => 0, // 0 =  Trial , 1 = Empresa con licencia
      'tipo_fiscal' => $data['tipo_fiscal'],
      'tipo_sistema' => ($data['tipo_fiscal'] == 2 && $data['empresa_empleado'] == 2 ? 3 : $data['tipo_sistema'] ),
      'tipo_sistema_empleado' => ($data['tipo_fiscal'] == 2 && $data['empresa_empleado'] == 2 ? $data['tipo_empleado'] : null ),
      'vencimiento_licencia' => date('Y-m-d',strtotime("+30 days",strtotime(date('Y-m-d')) ))//30 días de prueba
    ]);

    $oUsuario = User::create([
      'name' => $data['name'],
      'apellido_paterno' => $data['apellido_paterno'],
      'apellido_materno' => $data['apellido_materno'],
      'email' => $data['email'],
      'password' => Hash::make($data['password']),
      'estado' => 1,
      'fecha_registro' => date('Y-m-d H:i:s'),
      'puesto' => $data['puesto'],
      'telefono' => $data['telefono'],
      'area' => $data['area'],
      'id_empresa' => $oEmpresa->id,
      'tipo' => 1 // 1 = administrador , 2 = usuario de la empresa
    ]);

    $oEmpresa->id_usuario = $oUsuario->id;//asignar usuario administrador
    $oEmpresa->save();

    return $oUsuario;
  }
}
