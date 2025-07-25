<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class CostosIndirectos extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'costos_indirectos';
  protected $fillable = [
    'concepto',
    'costo',
    'unidades',
    'id_medida',
    'tipo',// 1 = Activo , 2 = Consumible
    'comprado',//0 = por pagar , 1 = comprado
    'estado',
    'id_empresa',
    'id_usuario',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */

  public static function creaRegistro($data) {
    return CostosIndirectos::create([
      'id_usuario' => \Auth::User()->id,
      'concepto' => $data['concepto'],
      'costo' => $data['costo'],
      'id_medida' => $data['id_medida'],
      'unidades' => $data['unidades'],
      'tipo' => @$data['tipo'],
      'comprado' => ( @$data['comprado']!=null ? $data['comprado']*1 : 0 ),
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oCostoIndirecto = CostosIndirectos::find( $data['id'] );
    $oCostoIndirecto->concepto = $data['concepto'];
    $oCostoIndirecto->id_medida = $data['id_medida'];
    $oCostoIndirecto->unidades = $data['unidades'];
    $oCostoIndirecto->costo = $data['costo'];
    $oCostoIndirecto->tipo = @$data['tipo'];
    $oCostoIndirecto->comprado = @$data['comprado'];
    $oCostoIndirecto->save();
    return $oCostoIndirecto;
  }

  public static function eliminarRegistro( $iId )
  {
    $oCostoIndirecto = CostosIndirectos::find( $iId );
    $oCostoIndirecto->estado = 0;
    $oCostoIndirecto->save();
    return $oCostoIndirecto;
  }

  public function get_costo_indirecto_proyecto()
  {
    return CostosIndirectosPro::select('costos_indirectos_pro.*','proyectos.nombre as nombre_proyecto')->where('id_costo_indirecto',$this->id)
    ->leftJoin('proyectos','proyectos.id','costos_indirectos_pro.id_proyecto')->first();
  }

  public function pagos()//Solo para proyectos
  {
    $oPagos = Pagos::select('pagos.*','movimientos_cuentas.concepto','movimientos_cuentas.monto','cuentas.id as id_cuenta','cuentas.nombre as nombre_cuenta')
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
  }

  /*public function usuario(){
    return $this->belongsTo('App\User','id_usuario','id')->first();
  }

  public function totPV($fi, $ff){
    $p = CostosIndirectosPro::where('id_costo_indirecto',$this->attributes['id'])->pluck('id_producto');
    return Ventas::whereIn('id_producto',$p)->whereBetween('fecha_venta', [$fi, $ff])->sum('unidades_vendidas');
  }*/

  public function medida(){
    return $this->belongsTo('App\Models\Medidas','id_medida','id')->first();
  }

}
