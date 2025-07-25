<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Empresas extends Model
{
  use Billable;

  protected $table = 'empresas';
  protected $primaryKey = 'id';
  protected $fillable = [
    'id',
    'nombre',
    'direccion',
    'industria',
    'telefono',
    'tamano',
    'fecha_registro',
    'fecha_modificacion',
    'imagen',
    'bitacora',
    'observaciones',
    'relevancia',// 0 - 5
    'estado',//0 = eliminada, 1 = activa, 2 == bloqueada
    'tipo',// 0 = TRIAL , 1 =  Empresa con licencia, 2 == prospecto
    'id_usuario',// usuario administrador de la empresa
    'id_licencia',
    'vencimiento_licencia',
    'tipo_sistema',// 1 = productos , 2 = proyectos, 3 = para empleado
    'tipo_sistema_empleado', // 1 = Asimilado, 2 = Asalariado , 3 = Honorarios
    //Stripe
    'stripe_id',//custumer id
    'card_brand',
    'card_last_four',
    'trial_ends_at',
    //Facturama
    'facturama_user',
    'facturama_pass'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function actualizaRegistro($data)
  {
    $oEmpresa = Empresas::find($data['id']);
    $oEmpresa->nombre = $data['nombre'];
    $oEmpresa->direccion = $data['direccion'];
    $oEmpresa->industria = $data['industria'];
    $oEmpresa->telefono = @$data['telefono'];
    $oEmpresa->tamano = $data['tamano'];
    if ( isset($data['imagen_url']) ) { $oEmpresa->imagen = $data['imagen_url']; }
    $oEmpresa->save();
    return $oEmpresa;
  }

  public static function actualizaFacturama($data)
  {
    $oEmpresa = Empresas::find($data['id']);
    $oEmpresa->facturama_user = $data['facturama_user'];
    $oEmpresa->facturama_pass = $data['facturama_pass'];
    $oEmpresa->save();
    return $oEmpresa;
  }
}
