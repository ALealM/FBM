<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tickets extends Model
{
  protected $table = 'tickets';
  protected $fillable = [
    'id',
    'asunto',
    'tipo',// problema , error , ayuda, pregunta
    'descripcion',
    'prioridad', //0 = baja , 1 = media ,  3 = alta
    'bitacora',
    'id_usuario',
    'id_empresa',
    'estado',//0 = eliminado, 1 = Nuevo, 2 = abierto, 3 = Cancelado, 4 =  en espera, 5 = Sin solucion, 6 = Solucionado y cerrado
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    return Tickets::create([
      'asunto' => $data['asunto'],
      'tipo' => $data['tipo'],
      'descripcion' => $data['descripcion'],
      'prioridad' => $data['prioridad'],
      'id_usuario' => $data['id_usuario'],
      'id_empresa' => $data['id_empresa'],
      'bitacora' => "[" . date('d/m/Y'). " ". date("H:i") . "] El usuario ha creado el ticket."
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oTicket = Tickets::find( $data['id'] );
    if ( @$data['asunto'] != null ) { $oTicket->asunto = $data['asunto']; }
    if ( @$data['tipo'] != null ) { $oTicket->tipo = $data['tipo']; }
    if ( @$data['descripcion'] != null ) { $oTicket->descripcion = $data['descripcion']; }
    if ( @$data['prioridad'] != null ) { $oTicket->prioridad = $data['prioridad']; }
    if ( @$data['bitacora'] != null ) { $oTicket->bitacora = "[" . date('d/m/Y'). " ". date("H:i") . "] " . $data['bitacora'] . $oTicket->bitacora; }
    if ( @$data['estado'] != null ) { $oTicket->estado = $data['estado']; }
    $oTicket->save();
    return $oTicket;
  }
}
