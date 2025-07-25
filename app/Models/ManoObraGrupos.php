<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManoObraGrupos extends Model
{
  protected $table = 'mano_obra_grupos';
  protected $fillable = [
    'nombre',
    'estado',
    'id_empresa',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data) {
    return ManoObraGrupos::create([
      'nombre' => $data['nombre'],
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oManoDeObraGrupo = ManoObraGrupos::find( $data['id'] );
    $oManoDeObraGrupo->nombre = $data['nombre'];
    $oManoDeObraGrupo->save();
    return $oManoDeObraGrupo;
  }

  public static function eliminarRegistro( $iId )
  {
    $oManoDeObraGrupo = ManoObraGrupos::find( $iId );
    $oManoDeObraGrupo->estado = 0;
    $oManoDeObraGrupo->save();
    return $oManoDeObraGrupo;
  }
}
