<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
  protected $table = 'pagos';
  protected $fillable = [
    'id',
    'id_elemento',
    'tipo',// 1 = pago nomina , 2 = costo fijo, 3 = pago costo indirecto (proyectos)
    'id_movimiento', 
    'iva',
    'pdf',
    'xml',
    'fecha',
    'id_empresa',
    'estado',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    return Pagos::create([
      'id_elemento' => $data['id_elemento'],
      'id_movimiento' => $data['id_movimiento'],
      'iva' => (@$data['iva']!=null ? $data['iva'] : 0 ),
      'tipo' => $data['tipo'],
      'pdf' => @$data['sRutaPdf'],
      'xml' => @$data['sRutaXml'],
      'fecha' => $data['fecha'],
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oPago = Pagos::find($data['id_pago']);
    $oPago->iva = (@$data['iva']!=null?$data['iva']:0);
    if (@$data['sRutaPdf'] != null) { $oPago->pdf = @$data['sRutaPdf']; }
    if (@$data['sRutaXml'] != null) { $oPago->xml = @$data['sRutaXml']; }
    $oPago->fecha = $data['fecha'];
    $oPago->save();
    return $oPago;
  }

  public static function eliminarRegistro($data)
  {
    $oPago = Pagos::find($data['id_pago']);
    $oPago->estado = 0;
    $oPago->save();
    return $oPago;
  }
}
