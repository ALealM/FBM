<?php

namespace App\Http\Controllers;

use App\Models\Cuentas;
use App\Models\MovimientosCuentas;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Redirect;

class CuentasController extends Controller
{
  public function index()
  {
    //Bitacora::creaRegistro('Listado de cuentas.');
    $oCuentas = $this->get_cuentas_disponibles();
    return view('cuentas.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Listado de cuentas']],
      'sActivePage' => 'cuentas',
      'sTitulo' => 'Cuentas',
      'sDescripcion' => 'Listado de cuentas',
      'oCuentas' => $oCuentas
    ]);
  }

  public function get_cuentas_disponibles()
  {
    return Cuentas::where('estado',1)->where('id_empresa',\Auth::User()->id_empresa)->orderBy('id','DESC')->get();
  }

  public function store(Request $request)
  {
    //Bitacora::creaRegistro('Creación de cuenta.');
    $aInput = $request->all();
    $oCuenta = Cuentas::creaRegistro($aInput);

    $oCuentas = $this->get_cuentas_disponibles();
    $htmlCuentas = view('cuentas.table')->with('oCuentas', $oCuentas)->render();

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Cuenta guardada.',
      'resultado' => ['html' => $htmlCuentas]
    ]);
  }

  public function update(Request $request)
  {
    //Bitacora::creaRegistro('Actualización de cuenta.');
    $aInput = $request->all();

    $oCuenta = Cuentas::editaRegistro($aInput);

    $oCuentas = $this->get_cuentas_disponibles();
    $htmlCuentas = view('cuentas.table')->with('oCuentas', $oCuentas)->render();

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Datos de la cuenta guardados.',
      'resultado' => ['html' => $htmlCuentas]
    ]);
  }

  public function movimiento(Request $request)
  {
    $aInput = $request->all();
    //dd($aInput);
    //Bitacora::creaRegistro('Movimiento a cuenta.');
    $aInput['id_cuenta'] = $aInput['id'];
    //$oCuenta = Cuentas::find($aInput['id']);
    /*if ( $aInput['tipo'] == 1 ) {
      Cuentas::sumarMonto($aInput);
    }else {
      Cuentas::restarMonto($aInput);
    }*/
    MovimientosCuentas::creaRegistro($aInput);

    $oCuentas = $this->get_cuentas_disponibles();
    $htmlCuentas = view('cuentas.table')->with('oCuentas', $oCuentas)->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Movimiento realizado.',
      'resultado' => ['html' => $htmlCuentas]
    ]);
  }

  public function historial_movimientos(Request $request)
  {
    //Bitacora::creaRegistro('Ver historial de movimientos de una cuenta.');
    $aInput = $request->all();
    $oMovimientos = MovimientosCuentas::where('id_cuenta',$aInput['id'])->where('estado',1)->orderBy('fecha','ASC')->get();

    $html = view('cuentas.tableHistorialMovimientos')->with('oMovimientos', $oMovimientos)->with('id_cuenta',$aInput['id'])->render();

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Historial obtenido.',
      'resultado' => ['html' => $html]
    ]);
  }

  public function destroy(Request $request)
  {
    //Bitacora::creaRegistro('Eliminación de cuenta.');
    $aInput = $request->all();
    $oCuenta = Cuentas::find($aInput['id']);
    $oCuenta->estado = 0;
    $oCuenta->save();

    $oCuentas = $this->get_cuentas_disponibles();
    $htmlCuentas = view('cuentas.table')->with('oCuentas', $oCuentas)->render();

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Eliminación de la cuenta exitosa.',
      'resultado' => ['html' => $htmlCuentas]
    ]);
  }

}
