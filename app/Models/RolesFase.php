<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ParticipantesProyecto;
use App\Models\ManoObra;

class RolesFase extends Model
{
  protected $table = 'roles_fase';
  protected $primaryKey = 'id';
  protected $fillable = [
    'id',
    'nombre',
    'id_fase',
    'estado',
    'fecha_registro',
    'fecha_modificacion',
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    $oRol = RolesFase::create([
      'nombre' => $data['nombre'],
      'id_fase' => $data['id_fase']
    ]);
    return $oRol;
  }

  public static function actualizaRegistro($data)
  {
    $oRol = RolesFase::find($data['id']);
    $oRol->nombre = $data['nombre'];
    $oRol->save();
    return $oRol;
  }

  public function participantes()
  {
    $oParticipantes = ParticipantesProyecto::where('id_rol',$this->id)
    ->where('estado',1)
    ->get();
    foreach ($oParticipantes as $key => $oParticipante) {
      $oManoObra = ManoObra::where('id',$oParticipante->id_mano_obra)->first();
      $aResultadoImpuestos = app('App\Http\Controllers\ManoDeObraController')->calcular_impuestos($oManoObra);


      $oParticipantes[$key]->valor_hora = $aResultadoImpuestos['resultado']['fSueldoDiario'] / 8;
      $oParticipantes[$key]->dias = round((( strtotime($oParticipante->fecha_fin) - strtotime($oParticipante->fecha_inicio))/86400)) + 1 ;
      $oParticipantes[$key]->horas_trabajo = ($oParticipantes[$key]->dias * $oParticipante->disponibilidad / 100) * 8;
      $oParticipantes[$key]->valor = $oParticipantes[$key]->valor_hora * $oParticipantes[$key]->horas_trabajo;
      $oParticipantes[$key]->nombre = $oManoObra->nombre;
      $oParticipantes[$key]->oManoObra = $oManoObra;
      $oParticipantes[$key]->aImpuestos = $aResultadoImpuestos;
    }
    return $oParticipantes;
  }

  public static function eliminarRegistro( $iId )
  {
    $oRol = RolesFase::find( $iId );
    $oRol->estado = 0;
    $oRol->save();
    return $oRol;
  }
}
