<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cobros extends Model
{
  protected $table = 'cobros';
  protected $fillable = [
    'id',
    'id_elemento',
    'id_movimiento',
    'tipo',// 1 = cobro proyecto factura
    'iva',
    'fecha',
    'id_empresa',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    return Cobros::create([
      'id_elemento' => $data['id_elemento'],
      'id_movimiento' => $data['id_movimiento'],
      'iva' => (@$data['iva']!=null ? $data['iva'] : 0 ),
      'tipo' => $data['tipo'],
      'fecha' => $data['fecha'],
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }
}
