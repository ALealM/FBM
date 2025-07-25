<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TicketsVentas extends Model
{
  protected $table = 'tickets_ventas';
  protected $fillable = [
    'id',
    'subtotal',
    'iva',
    'total',
    'id_empresa',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    return TicketsVentas::create([
      'subtotal' => $data['subtotal'],
      'iva' => $data['iva'],
      'total' => $data['total'],
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }
}
