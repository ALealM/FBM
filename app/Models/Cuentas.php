<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
  protected $table = 'cuentas';
  protected $fillable = [
    'id',
    'nombre',
    'descripcion',
    'banco',
    'numero',
    'monto',
    'id_empresa',
    'estado',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    return Cuentas::create([
      'nombre' => $data['nombre'],
      'descripcion' => $data['descripcion'],
      'banco' => $data['banco'],
      'numero' => $data['numero'],
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function editaRegistro($data)
  {
    $registro= Cuentas::find($data['id']);
    $registro->nombre=$data['nombre'];
    $registro->descripcion=$data['descripcion'];
    $registro->banco=$data['banco'];
    $registro->numero=$data['numero'];
    $registro->save();
    return $registro;
  }

  public static function getTotal($iId)
  {
    $fAbonos = floatval(MovimientosCuentas::where('id_cuenta',$iId)->where('tipo',1)->where('estado',1)->sum('monto'));
    $fCargos = floatval(MovimientosCuentas::where('id_cuenta',$iId)->where('tipo',0)->where('estado',1)->sum('monto'));
    return ($fAbonos - $fCargos);
  }

  public static function eliminarRegistro($data)
  {
    $registro = Cuentas::find($data['id']);
    $registro->estado = 0;
    $registro->save();
    return $registro;
  }
}
