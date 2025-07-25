<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Movimientos extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'movimientos';
  protected $fillable = [
    'id_materia',
    'cantidad',
    'tipo',
    'precio',
    'observaciones',
    'fecha',
    'id_proveedor',
    'id_usuario',
    'id_empresa',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data) {
    return Movimientos::create([
      'id_materia' => $data['id_materia'],
      'cantidad' => $data['cantidad'],
      'tipo' => ( $data['tipo'] == 'entrada' ? 1 : 0 ), //Entrada o salida
      'precio' => $data['costo'],
      'fecha' => $data['fecha'],
      'id_proveedor' => $data['id_proveedor'],
      'observaciones' => $data['observaciones'],
      'id_usuario' => \Auth::User()->id,
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

}
