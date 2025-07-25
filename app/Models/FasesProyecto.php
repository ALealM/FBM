<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RolesFase;
use App\Models\ParticipantesProyecto;

class FasesProyecto extends Model
{
  protected $table = 'fases_proyecto';
  protected $primaryKey = 'id';
  protected $fillable = [
    'id',
    //'orden',
    'nombre',
    'fecha_inicio',
    'fecha_fin',
    'estado',
    'id_proyecto',
    'fecha_registro',
    'fecha_modificacion',
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
      $oFase = FasesProyecto::create([
        //'orden' => $data['orden'],
        'nombre' => $data['nombre'],
        'fecha_inicio' => $data['fecha_inicio'],
        'fecha_fin' => $data['fecha_fin'],
        'id_proyecto' => $data['id_proyecto']
      ]);

    return $oFase;
  }

  public static function actualizaRegistro($data)
  {
    $oFase = FasesProyecto::find($data['id']);
    //$oFase->orden = $data['orden'];
    $oFase->fecha_inicio = $data['fecha_inicio'];
    $oFase->fecha_fin = $data['fecha_fin'];
    $oFase->nombre = $data['nombre'];
    $oFase->save();
    return $oFase;
  }

  public function roles()
  {
    $oRoles = RolesFase::where('id_fase',$this->id)->where('estado',1)->get();
    return $oRoles;
  }

  public function numero_participantes()
  {
    $iNumeroParticipantes = ParticipantesProyecto::where('id_fase',$this->id)->where('estado',1)->count();
    return $iNumeroParticipantes;
  }

  public static function eliminarRegistro( $iId )
  {
    $oFase = FasesProyecto::find( $iId );
    $oFase->estado = 0;
    $oFase->save();
    return $oFase;
  }
}
