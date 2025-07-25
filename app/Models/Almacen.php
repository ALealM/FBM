<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Almacen extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'almacen';
  protected $fillable = [
    'id_materia',
    'cantidad',
    'precio',
    'id_usuario',
    'id_empresa',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */

  public static function creaRegistro($data) {
    return Almacen::create([
      'id_materia' => $data['id_materia'],
      'cantidad' => $data['cantidad'],
      'precio' => $data['precio'],
      'id_usuario' => \Auth::User()->id,
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function actualizaRegistro($data) {
    $oAlmacen = Almacen::find( $data['id'] );
    //$oAlmacen->id_materia = $data['id_materia'];
    $oAlmacen->cantidad = $data['cantidad'];
    $oAlmacen->precio = $data['precio'];
    $oAlmacen->save();
    return $oAlmacen;
  }

  public function usuario(){
    return $this->belongsTo('App\User','id_usuario','id')->first();
  }

  public function materia(){
    return $this->belongsTo('App\Models\MateriaPrima','id_materia','id')->first();
  }

}
