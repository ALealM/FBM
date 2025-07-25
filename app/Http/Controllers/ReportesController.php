<?php

namespace App\Http\Controllers;

use App\Models\Ventas;
use App\Models\Productos;
use App\Models\ManoObra;
use App\Models\CosteoProducto;
use App\Models\CostosFijos;
use App\Models\CostosIndirectos;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
  public function reporte()
  {
    return view('reportes.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Reportes']],
      'sActivePage' => 'reportes',
      'sTitulo' => 'REPORTE DE LAS VENTAS',
      'sDescripcion' => 'Seleccione las fechas a consultar.'
    ]);
  }

  public function getReporte(Request $request)
  {
    $sFechaInicial = $request->get('inicio');
    $sFechaFinal = $request->get('fin');
    $totV=0;
    $totD=0;
    $volT=0;
    $totI=0;
    $iCantidadVentas = Ventas::whereBetween('fecha_registro', [$sFechaInicial, $sFechaFinal])->count();
    //dd( $iCantidadVentas );
    $oVentas = Ventas::whereBetween('fecha_registro', [$sFechaInicial, $sFechaFinal])->get();
    if($oVentas->isEmpty()){
      return 1;
    }
    foreach ($oVentas as $venta){
      $totV += $venta->unidades_vendidas * $venta->producto()->precio_venta;
    }

    $mod = ManoObra::sum('costo')*$iCantidadVentas/$oVentas->sum('unidades_vendidas');
    $productos= Productos::all();
    foreach ($productos as $producto){
      $producto->unidades = $producto->unidades($sFechaInicial,$sFechaFinal);
      $totD += $producto->unidades($sFechaInicial,$sFechaFinal)*($producto->indirectos($producto->id)+$mod+$producto->material($producto->id));
      $volT += (($producto->unidades($sFechaInicial,$sFechaFinal)/$oVentas->sum('unidades_vendidas'))*($producto->precio_venta-($producto->indirectos($producto->id)+$mod+$producto->material($producto->id))));
    }
    $costosP = CosteoProducto::all();
    $costosF = CostosFijos::all();
    $ct = \DB::select( \DB::raw('select count(*) mayor from costeo_producto group by id_producto order by mayor desc limit 1') )[0]->mayor;
    $indirectos= CostosIndirectos::all();
    foreach ($indirectos as $indirecto){
      $indirecto->totPV = $indirecto->totPV($sFechaInicial,$sFechaFinal);
      $totI += $indirecto->costo*$indirecto->totPV($sFechaInicial,$sFechaFinal);
    }
    $uoa = ($totV-$totD)*28*12-$costosF->sum('costo')*28*12;
    $mo = ManoObra::all();

    //cut
    $i = 0;
    $totVentas = [];
    while( $i <= 30 ){
      $dia = ( $i < 10 ) ? "0" . ($i+1) : ($i+1);
      $totVentas[$i] = 0;
      foreach($productos as $producto){
        $totVentas[$i] += $producto->unidades("2020-03-$dia","2020-03-$dia") * $producto->precio_venta;
      }
      $i++;
    }
    $i = 0;
    $totEgresos = [];
    while($i<=30){
      $dia = ( $i < 10 ) ? "0" . ($i+1) : ($i+1);
      $totEgresos[$i]=0;
      foreach($productos as $producto){
        $totEgresos[$i] += $producto->unidades("2020-03-$dia","2020-03-$dia")*$producto->material($producto->id);
      }
      $i++;
    }
    //cut

    return view('reportes.graficas',[
      'ventas' => $oVentas,
      'productos' => $productos,
      'mod' => $mod,
      'ct' => $ct,
      'indirectos' => $indirectos,
      'mo' => $mo,
      'costosF' => $costosF,
      'totV' => $totV,
      'totD' => $totD,
      'volT' => $volT,
      'uoa' => $uoa,
      'totI' => $totI,
      'fechai' => $sFechaInicial,
      'fechaf' => $sFechaFinal,
      'cant' => $iCantidadVentas,
      //
      'totVentas' => $totVentas,
      'totEgresos' => $totEgresos
    ]);
  }

  public function actividad()
  {
    return view('actividad.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Actividad']],
      'sActivePage' => 'actividad',
      'sTitulo' => 'ACTIVIDAD DE LAS VENTAS',
      'sDescripcion' => 'Seleccione las fechas a consultar.'
    ]);
  }

  public function getActividad(Request $request)
  {
    $sFechaInicial = $request->get('inicio');
    $sFechaFinal = $request->get('fin');
    $totV=0;
    $totD=0;
    $volT=0;
    $totI=0;
    $iCantidadVentas = Ventas::whereBetween('fecha_registro', [$sFechaInicial, $sFechaFinal])->where('id_producto',1)->sum('id_producto');
    $oVentas = Ventas::whereBetween('fecha_registro', [$sFechaInicial, $sFechaFinal])->get();
    if($oVentas->isEmpty()){
      return 1;
    }
    foreach ($oVentas as $venta){
      $totV += $venta->unidades_vendidas * $venta->producto()->precio_venta;
    }
    $mod = ManoObra::sum('costo')*$iCantidadVentas/$oVentas->sum('unidades_vendidas');
    $productos= Productos::all();
    foreach ($productos as $producto){
      $producto->unidades = $producto->unidades($sFechaInicial,$sFechaFinal);
      $totD += $producto->unidades($sFechaInicial,$sFechaFinal)*($producto->indirectos($producto->id)+$mod+$producto->material($producto->id));
      $volT += (($producto->unidades($sFechaInicial,$sFechaFinal)/$oVentas->sum('unidades_vendidas'))*($producto->precio_venta-($producto->indirectos($producto->id)+$mod+$producto->material($producto->id))));
    }
    $costosP = CosteoProducto::all();
    $costosF = CostosFijos::all();
    $ct = \DB::select( \DB::raw('select count(*) mayor from costeo_producto group by id_producto order by mayor desc limit 1') )[0]->mayor;
    $indirectos= CostosIndirectos::all();
    foreach ($indirectos as $indirecto){
      $indirecto->totPV = $indirecto->totPV($sFechaInicial,$sFechaFinal);
      $totI += $indirecto->costo*$indirecto->totPV($sFechaInicial,$sFechaFinal);
    }
    $uoa = ($totV-$totD)*28*12-$costosF->sum('costo')*28*12;
    $mo = ManoObra::all();
    return view('actividad/ventas')->with('ventas',$oVentas)->with('productos',$productos)->with('mod',$mod)->with('ct',$ct)
    ->with('indirectos',$indirectos)->with('mo',$mo)->with('costosF',$costosF)->with('totV',$totV)->with('totD',$totD)
    ->with('volT',$volT)->with('uoa',$uoa)->with('totI',$totI)->with('fechai',$sFechaInicial)->with('fechaf',$sFechaFinal)->with('cant',$iCantidadVentas);
  }

}
