<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cfdis extends Model
{
  protected $table = 'cfdis';
  protected $fillable = [
    'id',
    'cfdi',
    'folio',
    'receptor',
    'total',
    'id_proyecto',
    'id_empresa',
    'id_usuario',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    return Cfdis::create([
      'cfdi' => $data['cfdi'],
      'folio' => $data['folio'],
      'id_proyecto' => $data['id_proyecto'],
      'receptor' => @$data['receptor'],
      'total' => @$data['total'],
      'id_empresa' => \Auth::User()->id_empresa,
      'id_usuario' => \Auth::User()->id
    ]);
  }

  /*public static function eliminarRegistro($iId)
  {
    $oProyecto = Proyectos::find( $iId );
    $oProyecto->estado = 0;
    $oProyecto->save();
    return $oProyecto;
  }*/
}
