<?php

namespace App\Http\Controllers;

use App\Models\Cobros;
use App\Models\MovimientosCuentas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;

class CobrosController extends Controller
{
  public function store(Request $request)
  {
    $aInput = $request->all();
    if ($aInput['tipo_cobro']==1) {//proyecto

    }
    $aInput['tipo'] = 1;//entrada (abono)
    $aInput['tipo_elemento'] = 2;//tipo cobro
    $oMovimiento = MovimientosCuentas::creaRegistro($aInput);
    $aInput["id_movimiento"] = $oMovimiento->id;
    $aInput['tipo'] = $aInput['tipo_cobro'];
    $oCobro = Cobros::creaRegistro($aInput);
    $oMovimiento->id_elemento = $oCobro->id;
    $oMovimiento->save();
    Session::flash('tituloMsg','Cobro registrado');
    Session::flash('mensaje',"El cobro ha sido registrado exitosamente.");
    Session::flash('tipoMsg','success');
    return back();
  }

  public function get_impuestos()
  {
    //Proyectos
    $oCobros = Cobros::select('cobros.*','proyectos.nombre as nombre_proyecto','movimientos_cuentas.monto')
    ->where('cobros.id_empresa',\Auth::User()->id_empresa)
    ->where('cobros.iva','>',0)
    ->where('cobros.tipo',1)
    ->where('proyectos.estado',1)
    ->leftJoin('proyectos','proyectos.id','cobros.id_elemento')
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id','cobros.id_movimiento')
    ->get();

    $aImpuestos = [];
    foreach ($oCobros as $key => $oCobro) {
      if (@$aImpuestos[date('Y-m',strtotime($oCobro->fecha))]==null) {
        $aImpuestos[date('Y-m',strtotime($oCobro->fecha))] = [];
      }
      array_push($aImpuestos[date('Y-m',strtotime($oCobro->fecha))],
      [
        'cobro' => $oCobro,
        'iva' => $oCobro->iva,
        'iva_total' => ($oCobro->monto * $oCobro ->iva) / 100
      ]);
    }
    return $aImpuestos;
  }
}
