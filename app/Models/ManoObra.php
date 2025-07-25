<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class ManoObra extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'mano_obra_d';
  protected $fillable = [
    'nombre',
    'concepto',
    'costo',
    'periodo',
    'id_grupo',
    'inicio',
    'fin',
    'fecha_inicio_pagos',
    //'numero_trabajadores',
    'tipo_contratacion',//'1'=>'Contrato indefinido','2'=>'Contrato temporal'
    'tipo',//['1'=>'Asalariado','2'=>'Asimilado','3'=>'Honorarios','4'=>'Prácticas']
    'prima_vacacional',
    'dias_aguinaldo',
    'estado',
    'id_usuario',
    'id_empresa',
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
    return ManoObra::create([
      'nombre' => $data['nombre'],
      'concepto' => $data['concepto'],
      'costo' => $data['costo'],
      'periodo' => $data['periodo'],
      'inicio' => $data['inicio'],
      'fin' => ($data['tipo_contratacion']==2?$data['fin']:null),
      'fecha_inicio_pagos' => $data['fecha_inicio_pagos'],
      //'numero_trabajadores' => $data['numero_trabajadores'],
      'id_grupo' => $data['id_grupo'],
      'tipo_contratacion' => $data['tipo_contratacion'],
      'tipo' => $data['tipo'],
      'prima_vacacional' => $data['prima_vacacional'],
      'dias_aguinaldo' => $data['dias_aguinaldo'],
      //'aguinaldo' => $data['aguinaldo'],
      'id_usuario' => \Auth::User()->id,
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oManoDeObra = ManoObra::find( $data['id'] );
    $oManoDeObra->nombre = $data['nombre'];
    $oManoDeObra->concepto = $data['concepto'];
    $oManoDeObra->costo = $data['costo'];
    $oManoDeObra->periodo = $data['periodo'];
    $oManoDeObra->inicio = $data['inicio'];
    $oManoDeObra->fin = ($data['tipo_contratacion']==2?$data['fin']:null);
    $oManoDeObra->fecha_inicio_pagos = $data['fecha_inicio_pagos'];
    //$oManoDeObra->numero_trabajadores = $data['numero_trabajadores'];
    $oManoDeObra->tipo_contratacion = $data['tipo_contratacion'];
    $oManoDeObra->tipo = $data['tipo'];
    $oManoDeObra->id_grupo = $data['id_grupo'];
    $oManoDeObra->prima_vacacional = $data['prima_vacacional'];
    $oManoDeObra->dias_aguinaldo = $data['dias_aguinaldo'];
    //$oManoDeObra->inicio = $data['imss'];
    //$oManoDeObra->inicio = $data['infonavit'];
    //$oManoDeObra->inicio = $data['aguinaldo'];
    $oManoDeObra->save();
    return $oManoDeObra;
  }

  public static function eliminarRegistro( $iId )
  {
    $oManoDeObra = ManoObra::find( $iId );
    $oManoDeObra->estado = 0;
    $oManoDeObra->save();
    return $oManoDeObra;
  }

  public function usuario(){
    return $this->belongsTo('App\User','id_usuario','id')->first();
  }

  public function periodo(){
    return $this->belongsTo('App\Models\Periodos','periodo','id')->first();
  }

  public function pagos()
  {
    /*$iDias = floor((strtotime(date('Y-m-d')) - strtotime($this->inicio))/86400);
    $iNumeroPagos = floor($iDias/$this->dias);*/

    $oPagos = Pagos::select('pagos.*','movimientos_cuentas.concepto','movimientos_cuentas.monto','cuentas.nombre as nombre_cuenta','cuentas.id as id_cuenta')
    ->where('pagos.id_elemento',$this->id)
    ->where('pagos.tipo',1)//mano de obra
    ->where('pagos.estado',1)
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id','pagos.id_movimiento')
    ->leftJoin('cuentas','cuentas.id','movimientos_cuentas.id_cuenta')
    ->get();

    $dateAux = $this->fecha_inicio_pagos;
    $aPagosPendientes = [];
    $aPeriodos = [];

    if ($dateAux != null && $dateAux != '') {
      while (  strtotime("+".$this->dias." days",strtotime($dateAux)) <= strtotime(date('Y-m-d')) ) {
        if ( $this->dias == 15 ) {
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
        }
        $iPagos = $oPagos->whereBetween('fecha',[$dateInicio,$dateFin])->count();
        array_push($aPeriodos,['fecha_inicio'=>date('d/m/Y',strtotime($dateInicio)),'fecha_fin'=>date('d/m/Y',strtotime($dateFin)),'pago'=>$iPagos]);
        if ($iPagos == 0) {
          array_push($aPagosPendientes,['fecha_inicio'=>date('d/m/Y',strtotime($dateInicio)),'fecha_fin'=>date('d/m/Y',strtotime($dateFin)),'pagos'=>$iPagos]);
        }
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
