<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Ventas extends Model
{
  protected $table = 'ventas';
  protected $fillable = [
    'id',
    'id_producto',
    'producto',
    'precio',
    'iva',
    'unidades_vendidas',
    'id_ticket',
    'id_empresa',
    //'fecha',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    return Ventas::create([
      'id_producto' => $data['id_producto'],
      'producto' => $data['producto'],
      'precio' => $data['precio'],
      'iva' => $data['iva'],
      'unidades_vendidas' => $data['unidades_vendidas'],
      'id_ticket' => $data['id_ticket'],
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public function producto(){
    return $this->belongsTo('App\Models\Productos','id_producto','id')->first();
  }
}
