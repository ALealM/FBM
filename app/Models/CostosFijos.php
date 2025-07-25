<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class CostosFijos extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'costos_fijos';
  protected $fillable = [
    'concepto',
    'costo',
    'iva',
    'periodo',
    'inicio',
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

  public static function creaRegistro($data)
  {
    return CostosFijos::create([
      'id_usuario' => \Auth::User()->id,
      'concepto' => $data['concepto'],
      'costo' => $data['costo'],
      'iva' => ( @$data['iva'] != null ? $data['iva'] : 0 ),
      'periodo' => $data['periodo'],
      'inicio' => $data['inicio'],
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oCostoFijo = CostosFijos::find( $data['id'] );
    $oCostoFijo->concepto = $data['concepto'];
    $oCostoFijo->costo = $data['costo'];
    $oCostoFijo->iva = ( @$data['iva'] != null ? $data['iva'] : 0 );
    $oCostoFijo->periodo = $data['periodo'];
    $oCostoFijo->inicio = $data['inicio'];
    $oCostoFijo->save();
    return $oCostoFijo;
  }

  public static function eliminarRegistro( $iId ) {
    $oCostoFijo = CostosFijos::find( $iId );
    $oCostoFijo->estado = 0;
    $oCostoFijo->save();
    return $oCostoFijo;
  }

  public function usuario(){
    return $this->belongsTo('App\User','id_usuario','id')->first();
  }

  public function periodo(){
    return $this->belongsTo('App\Models\Periodos','periodo','id')->first();
  }

  public function pagos()
  {
    $oPagos = Pagos::select('pagos.*','movimientos_cuentas.concepto','movimientos_cuentas.monto','cuentas.nombre as nombre_cuenta','cuentas.id as id_cuenta')
    ->where('pagos.id_elemento',$this->id)
    ->where('pagos.tipo',2)//costo fijos
    ->where('pagos.estado',1)
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id','pagos.id_movimiento')
    ->leftJoin('cuentas','cuentas.id','movimientos_cuentas.id_cuenta')
    ->get();

    $dateAux = $this->inicio;
    $aPagosPendientes = [];
    $aPeriodos = [];
    while ( strtotime("+".$this->dias." days",strtotime($dateAux)) <= strtotime(date('Y-m-d')) ) {
      if ( $this->dias == 1) {
        $dateInicio = $dateAux;
        $dateFin = date("Y-m-d",strtotime("+1 days",strtotime($dateAux)));
        $dateAux = date("Y-m-d",strtotime("+2 days",strtotime($dateAux)));
      }elseif ( $this->dias == 7) {
        $dateInicio = $dateAux;
        $dateFin = date("Y-m-d",strtotime("+7 days",strtotime($dateAux)));
        $dateAux = date("Y-m-d",strtotime("+8 days",strtotime($dateAux)));;
      }elseif ( $this->dias == 15 ) {
        $dateInicio = ( date('d',strtotime($dateAux))*1 > 15 ? date('Y-m-',strtotime($dateAux)).'16' : date('Y-m-',strtotime($dateAux)).'01' );
        $dateFin = ( date('d',strtotime($dateAux))*1 < 15 ? date('Y-m-',strtotime($dateAux)).'15' : date('Y-m-t',strtotime($dateAux)) );
        $dateAux = date("Y-m-d",strtotime("+16 days",strtotime($dateAux)));
      }elseif ( $this->dias == 30 ) {
        $dateInicio = date('Y-m-',strtotime($dateAux)).'01';
        $dateFin = date('Y-m-t',strtotime($dateAux));
        $dateAux = date("Y-m-d",strtotime("+1 months",strtotime($dateAux)));
      }elseif ( $this->dias == 60 ) {
        $dateInicio = date('Y-m-',strtotime($dateAux)).'01';
        $dateFin = date("Y-m-t",strtotime("+1 months",strtotime($dateAux)));
        $dateAux = date("Y-m-",strtotime("+2 months",strtotime($dateAux))).'01';
      }elseif ( $this->dias == 91 ) {
        $dateInicio = date('Y-m-',strtotime($dateAux)).'01';
        $dateFin = date("Y-m-t",strtotime("+2 months",strtotime($dateAux)));
        $dateAux = date("Y-m-",strtotime("+3 months",strtotime($dateAux))).'01';
      }elseif ( $this->dias == 121 ) {
        $dateInicio = date('Y-m-',strtotime($dateAux)).'01';
        $dateFin = date("Y-m-t",strtotime("+3 months",strtotime($dateAux)));
        $dateAux = date("Y-m-",strtotime("+4 months",strtotime($dateAux))).'01';
      }elseif ( $this->dias == 184 ) {
        $dateInicio = date('Y-m-',strtotime($dateAux)).'01';
        $dateFin = date("Y-m-t",strtotime("+5 months",strtotime($dateAux)));
        $dateAux = date("Y-m-",strtotime("+6 months",strtotime($dateAux))).'01';
      }elseif ( $this->dias == 365 ) {
        $dateInicio = date('Y-m-',strtotime($dateAux)).'01';
        $dateFin = date("Y-m-t",strtotime("+11 months",strtotime($dateAux)));
        $dateAux = date("Y-m-",strtotime("+12 months",strtotime($dateAux))).'01';
      }

      $iPagos = $oPagos->whereBetween('fecha',[$dateInicio,$dateFin])->count();
      //$iPagos = $oPagos->where('fecha','=>',$dateInicio)->where('fecha','=<',$dateFin)->count();
      array_push($aPeriodos,['fecha_inicio'=>date('d/m/Y',strtotime($dateInicio)),'fecha_fin'=>date('d/m/Y',strtotime($dateFin)),'pago'=>$iPagos]);
      if ($iPagos == 0) {
        array_push($aPagosPendientes,['fecha_inicio'=>date('d/m/Y',strtotime($dateInicio)),'fecha_fin'=>date('d/m/Y',strtotime($dateFin)),'pagos'=>$iPagos]);
      }
    }

    return [
      'numero_pagos' => $oPagos->count(),
      'numero_pagos_pendientes' => count($aPagosPendientes),
      'pagos_pendientes' => $aPagosPendientes,
      'pagos' => $oPagos,
      'periodos' => $aPeriodos
    ];
  }

}
