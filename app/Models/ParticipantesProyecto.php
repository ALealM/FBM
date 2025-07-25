<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantesProyecto extends Model
{
  protected $table = 'participantes_proyecto';
  protected $primaryKey = 'id';
  protected $fillable = [
    'id',
    'id_mano_obra',
    'disponibilidad',
    'id_rol',
    'id_fase',
    'id_proyecto',
    'fecha_inicio',
    'fecha_fin',
    'estado',
    'fecha_registro',
    'fecha_modificacion',
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    $oParticipante = ParticipantesProyecto::create([
      'id_mano_obra' => $data['id_mano_obra'],
      'disponibilidad' => $data['disponibilidad'],
      'fecha_inicio' => $data['fecha_inicio'],
      'fecha_fin' => $data['fecha_fin'],
      'id_rol' => $data['id_rol'],
      'id_fase' => $data['id_fase'],
      'id_proyecto' => $data['id_proyecto']
    ]);
    return $oParticipante;
  }

  public static function actualizaRegistro($data)
  {
    $oParticipante = ParticipantesProyecto::find($data['id']);
    $oParticipante->disponibilidad = $data['disponibilidad'];
    $oParticipante->fecha_inicio = $data['fecha_inicio'];
    $oParticipante->fecha_fin = $data['fecha_fin'];
    $oParticipante->save();
    return $oParticipante;
  }

  public static function eliminarRegistro( $iId )
  {
    $oParticipante = ParticipantesProyecto::find( $iId );
    $oParticipante->estado = 0;
    $oParticipante->save();
    return $oParticipante;
  }
}
