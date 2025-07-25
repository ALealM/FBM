<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MyResetPassword;

class User extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'usuarios';
  public $timestamps = false;
  protected $fillable = [
    'id',
    'name',
    'apellido_paterno',
    'apellido_materno',
    'email',
    'password',
    'estado',
    'fecha_registro',
    'puesto',
    'telefono',
    'area',
    'imagen',
    'color',
    'id_empresa',
    'tipo' // 1 = administrador de la empresa, 2 = Usuario de la empresa
  ];

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
  * The attributes that should be cast to native types.
  *
  * @var array
  */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function sendPasswordResetNotification($token)
  {
    $this->notify(new MyResetPassword($token));
  }

  public static function actualizaRegistro($data)
  {
    $oUsuario = User::find($data['id']);
    $oUsuario->name = $data['name'];
    $oUsuario->apellido_paterno = $data['apellido_paterno'];
    $oUsuario->apellido_materno = $data['apellido_materno'];
    $oUsuario->puesto = $data['puesto'];
    $oUsuario->telefono = $data['telefono'];
    $oUsuario->area = $data['area'];
    $oUsuario->save();
    return $oUsuario;
  }

  public static function actualizaContrasena($data)
  {
    $oUsuario = User::find($data['id']);
    $oUsuario->password = $data['password'];
    $oUsuario->remember_token = null;
    $oUsuario->save();
    return $oUsuario;
  }

  public static function editaImagen($data)
  {
    $registro= User::where('id',$data['id'])->first();
    $registro->imagen=$data['imagen'];
    $registro->save();
    return true;
  }

  public static function editaColor($data)
  {
    $registro= User::where('id',$data['id'])->first();
    $registro->color=$data['color'];
    $registro->save();
    return true;
  }

  public function empresa()
  {
    return $this->belongsTo('App\Models\Empresas','id_empresa','id')->leftJoin('usuarios','usuarios.id','empresas.id_usuario')->select('empresas.*','usuarios.email')->first();
  }

  public function licencia()
  {
    //dd( $this->belongsTo('App\Models\Licencias','id_licencia','id')->first() );
    return $this->belongsTo('App\Models\Empresas','id_empresa','id')->select('licencias.*')->leftJoin('licencias','licencias.id','empresas.id_licencia')->first();
  }

  public function permisos()
  {
    $oLicencia = $this->belongsTo('App\Models\Empresas','id_empresa','id')->select('licencias.*','empresas.tipo_sistema','tipo_sistema_empleado','empresas.vencimiento_licencia','empresas.facturama_user')
    ->leftJoin('licencias','licencias.id','empresas.id_licencia')->first();
    $boolSubscriptionStripe = app("App\Http\Controllers\StripeController")->get_validacion_suscripcion();
    $boolTrial = @$oLicencia->id == null && strtotime($oLicencia->vencimiento_licencia) >= strtotime(date('Y-m-d')) ;
    return [
      'nombre_licencia' => (@$oLicencia->nombre!=null?$oLicencia->nombre:'TRIAL'),
      'oLicencia' => $oLicencia,
      'boolSubscriptionStripe' => $boolSubscriptionStripe,
      'boolTrial' => $boolTrial,
      'tipo_sistema' => $oLicencia->tipo_sistema,
      'm_facturama' => ($oLicencia->facturama_user != null && $oLicencia->facturama_user != '' ? true : false ),
      'm_mano_obra' => ((@$oLicencia->m_mano_obra == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema != 3,
      'm_costos_fijos' => ((@$oLicencia->m_costos_fijos == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema != 3,
      'm_materia_prima' => ((@$oLicencia->m_costos_fijos == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema == 1,
      'm_costos_indirectos' => ((@$oLicencia->m_costos_indirectos == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema != 3,
      'm_proyectos' => ((@$oLicencia->m_proyectos == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema == 2,
      'm_productos' => ((@$oLicencia->m_productos == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema == 1,
      'm_ventas' => ((@$oLicencia->m_ventas == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema == 1,
      'm_estado_cuenta' => ((@$oLicencia->m_estado_cuenta == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema != 3,
      'm_proyecciones' => ((@$oLicencia->m_proyecciones == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema == 1,
      'm_escenarios' => ((@$oLicencia->m_escenarios == 1 && $boolSubscriptionStripe) || $boolTrial) && $oLicencia->tipo_sistema == 1,
      'numero_usuarios' => null, //será cuando se pueda inngresar más de unn usuario por empresa
      'numero_mano_obra' => ($boolTrial?5:$oLicencia->numero_mano_obra),
      'numero_productos' => ($boolTrial?5:$oLicencia->numero_productos),
      'numero_proyectos' => ($boolTrial?5:$oLicencia->numero_proyectos),
    ];
  }
}
