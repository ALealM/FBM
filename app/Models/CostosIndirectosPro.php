<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class CostosIndirectosPro extends Authenticatable
{

  protected $table = 'costos_indirectos_pro';
  public $timestamps = false;
  protected $fillable = [
    'id',
    'id_usuario',
    'fecha_alta',
    'id_costo_indirecto',
    'id_producto',
    'id_proyecto',
    'id_fase',
    'comprado',//1 = comprado y 0 = no comprado
    'unidades'
  ];

  public static function creaRegistro($data)
  {
    return CostosIndirectosPro::create([
      'id_usuario' => \Auth::User()->id,
      'fecha_alta' => date("Y-m-d H:i:s"),
      'id_costo_indirecto' => $data['id_costo'],
      'id_producto' => @$data['id_producto'],
      'id_proyecto' => @$data['id_proyecto'],
      'id_fase' => @$data['id_fase'],
      'unidades' => $data['unidades']
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oCostoIndirecto = CostosIndirectosPro::find( $data['id_costo_producto'] );
    if (@$data['unidades'] != null) { $oCostoIndirecto->unidades = $data['unidades']; }
    $oCostoIndirecto->id_proyecto = @$data['id_proyecto'];
    $oCostoIndirecto->id_fase = @$data['id_fase'];
    $oCostoIndirecto->save();
    return $oCostoIndirecto;
  }


  public static function eliminarRegistro($iId)
  {
    $oCostoIndirecto = CostosIndirectosPro::find( $iId );
    $oCostoIndirecto->delete();
    return $oCostoIndirecto;
  }

  public function usuario(){
    return $this->belongsTo('App\User','id_usuario','id')->first();
  }

  public function indirecto(){
    return $this->belongsTo('App\Models\CostosIndirectos','id_costo_indirecto','id')->first();
  }

  /*public function pagos()//Solo para proyectos
  {
    $oPagos = Pagos::select('pagos.*','movimientos_cuentas.concepto','movimientos_cuentas.monto','cuentas.nombre as nombre_cuenta')
    ->where('pagos.id_elemento',$this->id)
    ->where('pagos.tipo',3)//costo indirecto de proyecto
    ->where('pagos.estado',1)
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id','pagos.id_movimiento')
    ->leftJoin('cuentas','cuentas.id','movimientos_cuentas.id_cuenta')
    ->get();

    return [
      'numero_pagos' => $oPagos->count(),
      'pagos' => $oPagos,
    ];
  }*/
}
