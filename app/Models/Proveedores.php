<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
  protected $table = 'proveedores';
  protected $fillable = [
    'nombre',
    'direccion',
    'id_empresa',
    'estado',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data) {
    return Proveedores::create([
      'nombre' => $data['nombre'],
      'direccion' => $data['direccion'],
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function actualizaRegistro($data) {
    $oProveedor = Proveedores::find( $data['id'] );
    $oProveedor->nombre = $data['nombre'];
    $oProveedor->direccion = $data['direccion'];
    $oProveedor->save();
    return $oProveedor;
  }

  public static function eliminarRegistro( $iId ) {
    $oProveedor = Proveedores::find( $iId );
    $oProveedor->estado = 0;
    $oProveedor->save();
    return $oProveedor;
  }
}
