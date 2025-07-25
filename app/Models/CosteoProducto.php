<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class CosteoProducto extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'costeo_producto';
  public $timestamps = false;
  protected $fillable = [
    'id_usuario',
    'fecha_alta',
    'id_producto',
    'id_materia_prima',
    'precio',
    'unidades'
  ];

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */

  public static function creaRegistro($data) {
    return CosteoProducto::create([
      'id_usuario' => \Auth::User()->id,
      'fecha_alta' => date("Y-m-d H:i:s"),
      'id_producto' => $data['id_producto'],
      'id_materia_prima' => $data['id_costo'],
      //'precio' => $data['precio'],
      'unidades' => $data['unidades']
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oCosto = CosteoProducto::find( $data['id_costo_producto'] );
    $oCosto->unidades = $data['unidades'];
    $oCosto->save();
    return $oCosto;
  }

  public static function eliminarRegistro($iId)
  {
    $oCosto = CosteoProducto::find( $iId );
    $oCosto->delete();
    return $oCosto;
  }

  public function mp(){
    return $this->belongsTo('App\Models\MateriaPrima','id_materia_prima','id')->first();
  }

  public function almacen(){
    return $this->belongsTo('App\Models\Almacen','id_materia_prima','id')->first();
  }
}
