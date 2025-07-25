<?php

namespace App\Http\Controllers;

use App\Models\Ventas;
use App\Models\TicketsVentas;
use App\Models\Productos;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Session;

class VentasController extends Controller
{
  public function index()
  {
    $oProductos = Productos::where('id_empresa', \Auth::User()->id_empresa)->where('estado',1)->orderBy('producto','ASC')->get();
    return view('ventas.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Ventas']],
      'sActivePage' => 'ventas',
      'sTitulo' => 'VENTAS',
      'sDescripcion' => 'Registro de venta.',
      'oProductos' => $oProductos
    ]);
  }

  public function store(Request $request)
  {
    $aInput = $request->all();

    $oTicket = TicketsVentas::creaRegistro([
      'subtotal' => $aInput['subtotal'],
      'iva' => $aInput['iva'],
      'total' => $aInput['total']
    ]);

    foreach ($aInput['productos'] as $key => $aProducto) {
      $oVenta = Ventas::creaRegistro([
        'id_producto' => $aProducto['id'],
        'producto' => $aProducto['producto'],
        'precio' => $aProducto['precio_venta'],
        'iva' => $aProducto['iva'],
        'unidades_vendidas' => $aProducto['unidades_vendidas'],
        'id_ticket' => $oTicket->id
      ]);
    }
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Venta realizada.',
      'resultado' => null
    ]);
  }

  public function getVentas(Request $request)
  {
    $sFecha = $request->get('fecha');
    $oVentas = Ventas::where('fecha_registro',$sFecha)->get();
    if($oVentas->isEmpty()) $oVentas = Ventas::where('fecha_registro','1900-01-01')->get();
    return view('ventas/tableVentas')->with('ventas',$oVentas);
  }

  /*public function store(Request $request)
  {
    $aInput = $request->all();
    $sFecha = $aInput['fecha_registro'];
    $iNumeroProductos = 0;

    if ( count($aInput['unidades']) > 0) {
      //Update ventas del dìa
      $oVentas = Ventas::whereIn('fecha',[$sFecha])->whereIn('id_producto', array_keys($aInput['unidades']) )->get();
      if ( $oVentas->count() > 0 ) {
        foreach ($oVentas as $oVenta) {
          foreach ($aInput['unidades'] as $iIdProducto => $fUnidades ) {
            if ($oVenta->id_producto == $iIdProducto) {
              $aData['id'] = $oVenta->id;
              $aData['unidades_vendidas'] = floatval($oVenta->unidades_vendidas) +  $fUnidades;
              Ventas::editaRegistro($aData);
              $aInput['unidades'][$iIdProducto] = 0;
              $iNumeroProductos++;
            }
          }
        }
      }
      //Store ventas del dìa
      foreach ($aInput['unidades'] as $iIdProducto => $fUnidades ) {
        if ( $fUnidades > 0 ) {
          $aData['fecha'] = $sFecha;
          $aData['id_producto'] = $iIdProducto;
          $aData['unidades_vendidas'] = $fUnidades;
          Ventas::creaRegistro($aData);
          $iNumeroProductos++;
        }
      }

      Session::flash('tituloMsg','Guardado con éxito!');
      Session::flash('mensaje',"Se ha realizado la venta de (". $iNumeroProductos . ") diferentes productos.");
      Session::flash('tipoMsg','success');
      return Redirect::to('/ventas');
    }else {
      Session::flash('tituloMsg','Alerta');
      Session::flash('mensaje',"No se ha declarado ninguna venta");
      Session::flash('tipoMsg','success');
      return back()->withInput();
    }
  }
  */
}
