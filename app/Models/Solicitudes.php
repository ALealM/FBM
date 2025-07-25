<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Solicitudes extends Model
{
  protected $table = 'solicitudes';
  protected $fillable = [
    'id',
    'tipo',//1 = licencia
    'id_licencia',
    'id_asesoria',
    'id_usuario_solicitante',
    'notas',
    'estado',//0 = eliminada, 1 = Solitada, 2 = Cerrada o atendida, 3 = Denegada
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data) {
    return Solicitudes::create([
      'tipo' => $data['tipo'],
      'id_licencia' => @$data['id_licencia'],
      'id_asesoria' => @$data['id_asesoria'],
      'id_usuario_solicitante' => @$data['id_usuario_solicitante'],
      'notas' => @$data['notas']
    ]);
  }
}
