<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use App\Models\MovimientosCuentas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;
use Storage;

class PagosController extends Controller
{
  public function store(Request $request)
  {
    ///home/fullbmco/public_html/web/storage
    ///Users/oscar/Sites/financiero/storage/avatars/jasKJLCviGJGpjfE18weKyTGmJq6mIQoFaXU1wws.pdf
    //dd(storage_path('app/avatars/jasKJLCviGJGpjfE18weKyTGmJq6mIQoFaXU1wws.pdf') ,asset( Storage::url('avatars/jasKJLCviGJGpjfE18weKyTGmJq6mIQoFaXU1wws.pdf') ) );
    $aInput = $request->all();
    //dd($aInput);
    if ($request->file('pdf') != null) {
      $aInput['sRutaPdf'] = 'storage/' . Storage::disk('public')->putFile('empresas/'.\Auth::User()->id_empresa.'/facturas', $request->file('pdf'));
    }
    if ($request->file('xml') != null) {
      $aInput['sRutaXml'] = 'storage/' . Storage::disk('public')->putFile('empresas/'.\Auth::User()->id_empresa.'/facturas', $request->file('xml'));
    }
    //$path = Storage::putFileAs('empresas/'.\Auth::User()->id_empresa.'/facturas/', $request->file('pdf'), null );
    $aInput['concepto'] = $aInput['concepto_pago'];
    $aInput['tipo'] = 0;//salida (cargo)
    $aInput['tipo_elemento'] = 1; //pago
    $oMovimiento = MovimientosCuentas::creaRegistro($aInput);
    $aInput["id_movimiento"] = $oMovimiento->id;
    $aInput['tipo'] = $aInput['tipo_pago'];
    $oPago = Pagos::creaRegistro($aInput);
    $oMovimiento->id_elemento = $oPago->id;
    $oMovimiento->save();
    Session::flash('tituloMsg','Pago registrado');
    Session::flash('mensaje',"El pago ha sido registrado exitosamente.");
    Session::flash('tipoMsg','success');
    return back();
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    if ($request->file('pdf') != null) {
      $aInput['sRutaPdf'] = 'storage/' . Storage::disk('public')->putFile('empresas/'.\Auth::User()->id_empresa.'/facturas', $request->file('pdf'));
    }
    if ($request->file('xml') != null) {
      $aInput['sRutaXml'] = 'storage/' . Storage::disk('public')->putFile('empresas/'.\Auth::User()->id_empresa.'/facturas', $request->file('xml'));
    }
    $aInput['concepto'] = $aInput['concepto_pago'];
    $oMovimiento = MovimientosCuentas::actualizaRegistro($aInput);
    $oPago = Pagos::actualizaRegistro($aInput);
    Session::flash('tituloMsg','Pago modificado');
    Session::flash('mensaje',"El pago ha sido actualizado exitosamente.");
    Session::flash('tipoMsg','success');
    return back();
  }

  public function get_impuestos()
  {
    $oPagos = Pagos::select('pagos.*','movimientos_cuentas.monto')
    ->where('pagos.id_empresa',\Auth::User()->id_empresa)
    ->where('pagos.estado',1)
    ->where('pagos.iva','>',0)
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id','pagos.id_movimiento')
    ->get();
    $aImpuestos = [];
    foreach ($oPagos as $key => $oPago) {
      if (@$aImpuestos[date('Y-m',strtotime($oPago->fecha))]==null) {
        $aImpuestos[date('Y-m',strtotime($oPago->fecha))] = [];
      }
      array_push($aImpuestos[date('Y-m',strtotime($oPago->fecha))],
      [
        'cobro' => $oPago,
        'iva' => $oPago->iva,
        'iva_total' => ($oPago->monto * $oPago ->iva) / 100
      ]);
    }
    return $aImpuestos;
  }

  public function get_pdf($iIdPago)
  {
    $oPago = Pagos::where('id',$iIdPago)
    ->where('id_empresa',\Auth::User()->id_empresa)
    ->first();
    return response()->download($oPago->pdf,'fbm_doc.pdf');
    //return Storage::download($oPago->pdf);
  }

  public function get_xml($iIdPago)
  {
    $oPago = Pagos::where('id',$iIdPago)
    ->where('id_empresa',\Auth::User()->id_empresa)
    ->first();
    return response()->download($oPago->xml,'fbm_doc.xml');
  }

  public function destroy(Request $request)
  {
    $aInput = $request->all();
    $oMovimiento = MovimientosCuentas::eliminarRegistro($aInput);
    $oPago = Pagos::eliminarRegistro($aInput);
    Session::flash('tituloMsg','Registro de pago eliminado');
    Session::flash('mensaje',"El pago ha sido eliminado exitosamente.");
    Session::flash('tipoMsg','success');
    return back();
  }
}
