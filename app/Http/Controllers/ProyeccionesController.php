<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;
use App\Models\CosteoProducto;
use App\Models\CostosIndirectosPro;
use App\Models\CostosFijos;
use App\Models\ManoObra;
use Illuminate\Support\Facades\Session;
use Redirect;

class ProyeccionesController extends Controller
{

  public $aMeses = [
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    9 => 'Septiembre',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
  ];

  public function index()
  {
    return view('proyecciones.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Proyecciones']],
      'sActivePage' => 'proyecciones',
      'sTitulo' => 'PROYECCIONES',
      'sDescripcion' => 'Realiza una proyección'
    ]);
  }

  public function calculo_anual()
  {
    $oProductos = Productos::where('id_empresa', \Auth::User()->id_empresa)
    ->where('estado',1)
    ->orderBy('id','DESC')
    ->get();

    return view('proyecciones.calculoAnual.index',[
      'aBreadCrumb' => [['link'=> '/proyecciones', 'label'=> 'Proyecciones'],['link'=> 'active', 'label'=> 'Calculo anual']],
      'sActivePage' => 'proyecciones',
      'sTitulo' => 'PROYECCIÓN ANUAL',
      'sDescripcion' => 'Realiza una proyección anual de las ventas de tus productos',
      'aMeses' => $this->get_meses(date('Y-m-d')),
      'oProductos' => $oProductos
    ]);
  }

  public function calculo_anual_inverso()
  {
    $oProductos = Productos::where('id_empresa', \Auth::User()->id_empresa)
    ->where('estado',1)
    ->orderBy('id','DESC')
    ->get();

    return view('proyecciones.calculoAnualInverso.index',[
      'aBreadCrumb' => [['link'=> '/proyecciones', 'label'=> 'Proyecciones'],['link'=> 'active', 'label'=> 'Calculo anual inverso']],
      'sActivePage' => 'proyecciones',
      'sTitulo' => 'PROYECCIÓN ANUAL INVERSO',
      'sDescripcion' => 'Realiza una proyección con la ganancia deseada',
      'oProductos' => $oProductos
    ]);
  }

  public function calculo_anual_incremento()
  {
    $oProductos = Productos::where('id_empresa', \Auth::User()->id_empresa)
    ->where('estado',1)
    ->orderBy('id','DESC')
    ->get();

    $aMeses = $this->get_meses();
    return view('proyecciones.calculoAnualIncremento.index',[
      'aBreadCrumb' => [['link'=> '/proyecciones', 'label'=> 'Proyecciones'],['link'=> 'active', 'label'=> 'Calculo anual con incremento']],
      'sActivePage' => 'proyecciones',
      'sTitulo' => 'PROYECCIÓN ANUAL CON INCREMENTO',
      'sDescripcion' => 'Realiza una proyección anual de las ventas de tus productos con incremento en porcentaje',
      'oProductos' => $oProductos,
      'sMes' => $aMeses[ intval(date('m')) ]
    ]);
  }

  public function generar_anual( Request $request)
  {
    $aInput = $request->all();
    //dd($aInput);
    //COSTOS POR PRODUCTO
    $aMeses = $this->get_meses(date('Y-m-d'));
    foreach ($aMeses as $key => $sMes) {
      $aCostosMesProductos[$sMes] = [];
      $aGananciasMesProductos[$sMes] = [];
      $aIngresosPeriodoProductos[$sMes] = [];
    }
    $aCostosProductos = [];
    $aNumeroVentasProductos = [];
    $fTotalCostosIndirectos = 0;
    $fTotalGanancia = 0;
    $fTotalIngresos = 0;
    $aCarritoMateriaPrima = [];
    $boolCalcularDescuentos = false;
    $aDescuentosMayoreo = [];
    $aMargenUtilidad = [];

    //dd($aMeses,$aInput['unidades']);

    //Para escenarios con aumento de costo en materia prima
    $fAumentoMateriaPrima = floatval(( @$aInput['porcentaje_alza_materia_prima'] > 0 && @$aInput['mes_alza_materia_prima'] >= 0  ) ? $aInput['porcentaje_alza_materia_prima'] : 0);
    $sMesAumentoMateriaPrima = ( @$aInput['porcentaje_alza_materia_prima'] > 0 && @$aInput['mes_alza_materia_prima'] >= 0  ) ? $aMeses[$aInput['mes_alza_materia_prima']] : "";
    $boolCalcularIncremento = false;
    do {

      foreach ($aInput['unidades'] as $key => $fUnidades) {
        if ( floatval( $fUnidades ) > 0 ) {
          $sMes = explode("_", $key)[0];
          $iIdProducto = explode("_", $key)[1];
          $aCostosMesProductos[ $sMes ][ $iIdProducto ] = 0;
          $aGananciasMesProductos[ $sMes ][ $iIdProducto ] = 0;
          $aIngresosPeriodoProductos[ $sMes ][ $iIdProducto ] = 0;
          if ( isset( $aCostosProductos[$iIdProducto] ) == false ) {
            $aCostosProductos[$iIdProducto] = 0;
          }
          if ( isset( $aNumeroVentasProductos[$iIdProducto] ) == false ) {
            $aNumeroVentasProductos[$iIdProducto] = 0;
          }
          $aNumeroVentasProductos[$iIdProducto] += floatval( $fUnidades );

          //Calcular incremento de materia prima
          if ($sMes == $sMesAumentoMateriaPrima) {
            $boolCalcularIncremento = true;
          }

          $aResultadoProducto = $this->get_costos_ganancias_por_producto($iIdProducto, floatval( $fUnidades ), $aCarritoMateriaPrima, !$boolCalcularDescuentos );
          //dd($aResultadoProducto);
          if ($aResultadoProducto['estatus'] == 1) {

            //Materia prima
            $fCosto = $aResultadoProducto['resultado']['fTotalCostosMateriaPrima'];
            /*if ($boolCalcularIncremento) {
              dd($aCostosMesProductos,$boolCalcularIncremento, ($fCosto + (($fCosto * $fAumentoMateriaPrima)/100)), $fCosto, $fAumentoMateriaPrima);
            }*/
            $aCostosMesProductos[ $sMes ][ $iIdProducto ] += ( $boolCalcularIncremento ? ($fCosto + (($fCosto * $fAumentoMateriaPrima)/100)) : $fCosto );
            $aCostosProductos[$iIdProducto] += ( $boolCalcularIncremento ? ($fCosto + (($fCosto * $fAumentoMateriaPrima)/100)) : $fCosto );

            //Carrito
            $aCarritoMateriaPrima = $aResultadoProducto['resultado']['aCarritoMateriaPrima'];
            //Descuentos
            $aDescuentosMayoreo[$iIdProducto] = $aResultadoProducto['resultado']['aDescuentosMayoreo'];

            //Costos indirectos
            $fCosto = $aResultadoProducto['resultado']['fTotalCostosIndirectos'];
            $aCostosMesProductos[ $sMes ][ $iIdProducto ] +=  $fCosto;
            $aCostosProductos[$iIdProducto] += $fCosto;
            $fTotalCostosIndirectos += $fCosto;
            //Margen de Utilidad Promedio
            $aMargenUtilidad[$iIdProducto] = $aResultadoProducto['resultado']['fMargenUtilidad'];

            //Ganancias
            $aGananciasMesProductos[ $sMes ][ $iIdProducto ] += $aResultadoProducto['resultado']['fTotalGanancia'];
            $fTotalGanancia += $aResultadoProducto['resultado']['fTotalGanancia'];

            //Ingresos
            $aIngresosPeriodoProductos[ $sMes ][ $iIdProducto ] += $aResultadoProducto['resultado']['fPrecio'];
            $fTotalIngresos += $aResultadoProducto['resultado']['fPrecio'];

          }else {
            Session::flash('tituloMsg','Alerta!');
            Session::flash('mensaje',$aResultadoProducto['mensaje']);
            Session::flash('tipoMsg','error');
            return Redirect::to('/proyecciones/calculo_anual');
          }
        }
      }

      $boolCalcularDescuentos = ((count($aCarritoMateriaPrima) > 0 && $boolCalcularDescuentos == false) ? true : false );
      if ($boolCalcularDescuentos) {
        //Reiniciarlizar contadores
        foreach ($aMeses as $key => $sMes) {
          $aCostosMesProductos[$sMes] = [];
          $aGananciasMesProductos[$sMes] = [];
          $aIngresosPeriodoProductos[$sMes] = [];
        }
        $boolCalcularIncremento = false;
        $aCostosProductos = [];
        $aNumeroVentasProductos = [];
        $fTotalCostosIndirectos = 0;
        $fTotalGanancia = 0;
        $fTotalIngresos = 0;
        $aDescuentosMayoreo = [];
      }
    } while ($boolCalcularDescuentos == true);


    //COSTOS FIJOS
    $fCostoFijoMensual = 0;
    $aResultadoCostosFijosMensual = $this->get_costos_fijos_mes();
    if ($aResultadoCostosFijosMensual['estatus'] == 1) {
      $fCostoFijoMensual = $aResultadoCostosFijosMensual['resultado'];
    }else {
      Session::flash('tituloMsg','Alerta!');
      Session::flash('mensaje',$aResultadoCostosFijosMensual['mensaje']);
      Session::flash('tipoMsg','error');
      return Redirect::to('/proyecciones/calculo_anual');
    }

    //COSTOS MANO DE OBRA
    $fCostoManoObraMensual = 0;
    $aResultadoCostoManoObraMensual = $this->get_costo_mano_obra_mes();
    if ($aResultadoCostoManoObraMensual['estatus'] == 1) {
      $fCostoManoObraMensual = $aResultadoCostoManoObraMensual['resultado'];
    }else {
      Session::flash('tituloMsg','Alerta!');
      Session::flash('mensaje',$aResultadoCostoManoObraMensual['mensaje']);
      Session::flash('tipoMsg','error');
      return Redirect::to('/proyecciones/calculo_anual');
    }

    //Calcular los pesos de los productos (W)
    arsort($aNumeroVentasProductos);
    $aPesosProductos = [];
    $oProductos = Productos::whereIn('id', array_keys( $aNumeroVentasProductos ) )->get();
    foreach ($aNumeroVentasProductos as $key => $fVentaProducto) {
      $aPesosProductos[ $key ]['id'] = $key;
      $aPesosProductos[ $key ]['producto'] =  $oProductos->where('id',$key)->first()->producto;
      $aPesosProductos[ $key ]['costo'] = $aCostosProductos[$key];
      $aPesosProductos[ $key ]['peso'] = $aNumeroVentasProductos[$key] / array_sum($aNumeroVentasProductos);
      $aPesosProductos[ $key ]['precio_venta'] = $oProductos->where('id',$key)->first()->precio_venta;
      $aPesosProductos[ $key ]['numero_ventas'] = $fVentaProducto;
    }

    //Información de la página
    if ( @$aInput['aPagina'] != null) {
      $aPagina = $aInput['aPagina'];
    }else {
      $aPagina = [
        'aBreadCrumb' => [['link'=> '/proyecciones', 'label'=> 'Proyecciones'],['link'=> 'active', 'label'=> 'Calculo anual']],
        'sActivePage' => 'proyecciones',
        'sTitulo' => 'PROYECCIÓN ANUAL',
        'sDescripcion' => 'Proyección de ventas anual realizada',
      ];
    }

    return view('proyecciones.calculoAnual.index',[
      'aBreadCrumb' => $aPagina['aBreadCrumb'],
      'sActivePage' => $aPagina['sActivePage'],
      'sTitulo' => $aPagina['sTitulo'],
      'sDescripcion' => $aPagina['sDescripcion'],
      'aMeses' => $aMeses,
      'aPesosProductos' => $aPesosProductos,
      'aDescuentosMayoreo' => $aDescuentosMayoreo,
      'aNumeroVentasProductos' => $aNumeroVentasProductos,
      'aCostosProductos' => $aCostosProductos,
      'aCostosMesProductos' => $aCostosMesProductos,
      'aGananciasMesProductos' => $aGananciasMesProductos,
      'aIngresosPeriodoProductos' => $aIngresosPeriodoProductos,
      'fTotalIngresos' => $fTotalIngresos,
      'fTotalEgresos' =>array_sum( $aCostosProductos ) + ($fCostoFijoMensual * 12) + ($fCostoManoObraMensual * 12),
      'fTotalGanancia' => $fTotalGanancia,
      'fCostoFijoMensual' => $fCostoFijoMensual,
      'fCostoManoObraMensual' => $fCostoManoObraMensual,
      'fTotalCostosIndirectos' => $fTotalCostosIndirectos,
      'aCarritoMateriaPrima' => $aCarritoMateriaPrima,
      'fMargenUtilidadPromedio' => @(array_sum( $aMargenUtilidad ) / count( $aMargenUtilidad ))
    ]);
  }

  public function generar_calculo_por_ventas($aInput)
  {
    $sPeriodo = ( @$aInput['sPeriodo'] != null ? $aInput['sPeriodo'] : 'anual' );

    //COSTOS FIJOS
    $fCostoFijoPeriodo = 0;
    $aResultadoCostosFijosMensual = $this->get_costos_fijos_mes();
    if ($aResultadoCostosFijosMensual['estatus'] != 1) {
      return [
        'estatus' => 0,
        'mensaje' => $aResultadoCostosFijosMensual['mensaje']
      ];
    }
    $fCostoFijoPeriodo = $aResultadoCostosFijosMensual['resultado'];
    //COSTOS MANO DE OBRA
    $fCostoManoObraPeriodo = 0;
    $aResultadoCostoManoObraPeriodo = $this->get_costo_mano_obra_mes();
    if ($aResultadoCostoManoObraPeriodo['estatus'] != 1) {
      return [
        'estatus' => 0,
        'mensaje' => $aResultadoCostoManoObraPeriodo['mensaje']
      ];
    }
    $fCostoManoObraPeriodo = $aResultadoCostoManoObraPeriodo['resultado'];

    switch ($sPeriodo) {
      case 'semanal':
        $aPeriodo = $this->get_dias(7, @$aInput['sTipo'], @$aInput['date']);
        $fCostoFijoPeriodo = $fCostoFijoPeriodo / 4;
        $fCostoManoObraPeriodo = $fCostoManoObraPeriodo / 4;
      break;
      case 'mensual':
        $aPeriodo = $this->get_semanas(4, @$aInput['sTipo'], @$aInput['date']);
      break;
      case 'semestral':
        $aPeriodo = $this->get_meses(@$aInput['date'],6,@$aInput['sTipo']);
        $fCostoFijoPeriodo = $fCostoFijoPeriodo * 6;
        $fCostoManoObraPeriodo = $fCostoManoObraPeriodo * 6;
      break;
      default://anual
        $aPeriodo = $this->get_meses(@$aInput['date'],12,@$aInput['sTipo']);
        $fCostoFijoPeriodo = $fCostoFijoPeriodo * 12;
        $fCostoManoObraPeriodo = $fCostoManoObraPeriodo * 12;
      break;
    }
    //dd($aPeriodo,$aInput['unidades']);

    //COSTOS POR PRODUCTO
    foreach ($aPeriodo as $key => $sPeriodoAux) {
      $aCostosPeriodoProductos[$key] = [];
      $aGananciasPeriodoProductos[$key] = [];
      $aIngresosPeriodoProductos[$key] = [];
    }
    //dd($aIngresosPeriodoProductos);
    $aCostosProductos = [];
    $aNumeroVentasProductos = [];
    $fTotalCostosMateriaPrima = 0;
    $fTotalCostosIndirectos = 0;
    $fTotalGanancia = 0;
    $fTotalIngresos = 0;
    $aCarritoMateriaPrima = [];
    $boolCalcularDescuentos = false;
    $aDescuentosMayoreo = [];
    $aMargenUtilidad = [];

    //Para escenarios con aumento de costo en materia prima
    $fAumentoMateriaPrima = floatval(( @$aInput['porcentaje_alza_materia_prima'] > 0 && @$aInput['mes_alza_materia_prima'] >= 0  ) ? $aInput['porcentaje_alza_materia_prima'] : 0);
    $sPeriodoAumentoMateriaPrima = ( @$aInput['porcentaje_alza_materia_prima'] > 0 && @$aInput['mes_alza_materia_prima'] >= 0  ) ? $aPeriodo[$aInput['mes_alza_materia_prima']] : "";
    $boolCalcularIncremento = false;
    do {
      //dd($aInput['unidades']);
      foreach ($aInput['unidades'] as $key => $fUnidades) {
        if ( floatval( $fUnidades ) > 0 ) {
          $sPeriodo = explode("_", $key)[0];
          $iIdProducto = explode("_", $key)[1];
          $aCostosPeriodoProductos[ $sPeriodo ][ $iIdProducto ] = 0;
          $aGananciasPeriodoProductos[ $sPeriodo ][ $iIdProducto ] = 0;
          $aIngresosPeriodoProductos[ $sPeriodo ][ $iIdProducto ] = 0;
          if ( isset( $aCostosProductos[$iIdProducto] ) == false ) {
            $aCostosProductos[$iIdProducto] = 0;
          }
          if ( isset( $aNumeroVentasProductos[$iIdProducto] ) == false ) {
            $aNumeroVentasProductos[$iIdProducto] = 0;
          }
          $aNumeroVentasProductos[$iIdProducto] += floatval( $fUnidades );

          //Calcular incremento de materia prima
          if ($sPeriodo == $sPeriodoAumentoMateriaPrima) {
            $boolCalcularIncremento = true;
          }

          $aResultadoProducto = $this->get_costos_ganancias_por_producto($iIdProducto, floatval( $fUnidades ), $aCarritoMateriaPrima, !$boolCalcularDescuentos );
          //dd($aResultadoProducto);
          if ($aResultadoProducto['estatus'] == 1) {

            //Materia prima
            $fCosto = $aResultadoProducto['resultado']['fTotalCostosMateriaPrima'];
            $fTotalCostosMateriaPrima += $fCosto;

            $aCostosPeriodoProductos[ $sPeriodo ][ $iIdProducto ] += ( $boolCalcularIncremento ? ($fCosto + (($fCosto * $fAumentoMateriaPrima)/100)) : $fCosto );
            $aCostosProductos[$iIdProducto] += ( $boolCalcularIncremento ? ($fCosto + (($fCosto * $fAumentoMateriaPrima)/100)) : $fCosto );

            //Carrito
            $aCarritoMateriaPrima = $aResultadoProducto['resultado']['aCarritoMateriaPrima'];
            //Descuentos
            $aDescuentosMayoreo[$iIdProducto] = $aResultadoProducto['resultado']['aDescuentosMayoreo'];

            //Costos indirectos
            $fCosto = $aResultadoProducto['resultado']['fTotalCostosIndirectos'];
            $aCostosPeriodoProductos[ $sPeriodo ][ $iIdProducto ] +=  $fCosto;
            $aCostosProductos[$iIdProducto] += $fCosto;
            $fTotalCostosIndirectos += $fCosto;
            //Margen de Utilidad Promedio
            $aMargenUtilidad[$iIdProducto] = $aResultadoProducto['resultado']['fMargenUtilidad'];

            //Ganancias
            $aGananciasPeriodoProductos[ $sPeriodo ][ $iIdProducto ] += $aResultadoProducto['resultado']['fTotalGanancia'];
            $fTotalGanancia += $aResultadoProducto['resultado']['fTotalGanancia'];

            //Ingresos
            $aIngresosPeriodoProductos[ $sPeriodo ][ $iIdProducto ] += $aResultadoProducto['resultado']['fPrecio'];
            $fTotalIngresos += $aResultadoProducto['resultado']['fPrecio'];

          }else {
            Session::flash('tituloMsg','Alerta!');
            Session::flash('mensaje',$aResultadoProducto['mensaje']);
            Session::flash('tipoMsg','error');
            return Redirect::to('/proyecciones/calculo_anual');
          }
        }
      }
      //dd($aGananciasPeriodoProductos);
      $boolCalcularDescuentos = ((count($aCarritoMateriaPrima) > 0 && $boolCalcularDescuentos == false) ? true : false );
      if ($boolCalcularDescuentos) {
        //Reiniciarlizar contadores
        foreach ($aPeriodo as $key => $sPeriodo) {
          $aCostosPeriodoProductos[$key] = [];
          $aGananciasPeriodoProductos[$key] = [];
          $aIngresosPeriodoProductos[$key] = [];
        }
        $boolCalcularIncremento = false;
        $aCostosProductos = [];
        $aNumeroVentasProductos = [];
        $fTotalCostosIndirectos = 0;
        $fTotalCostosMateriaPrima = 0;
        $fTotalGanancia = 0;
        $fTotalIngresos = 0;
        $aDescuentosMayoreo = [];
      }
    } while ($boolCalcularDescuentos == true);

    //Calcular los pesos de los productos (W)
    arsort($aNumeroVentasProductos);
    $aPesosProductos = [];
    $oProductos = Productos::whereIn('id', array_keys( $aNumeroVentasProductos ) )->get();
    foreach ($aNumeroVentasProductos as $key => $fVentaProducto) {
      $aPesosProductos[ $key ]['id'] = $key;
      $aPesosProductos[ $key ]['producto'] =  $oProductos->where('id',$key)->first()->producto;
      $aPesosProductos[ $key ]['costo'] = $aCostosProductos[$key];
      $aPesosProductos[ $key ]['peso'] = $aNumeroVentasProductos[$key] / array_sum($aNumeroVentasProductos);
      $aPesosProductos[ $key ]['precio_venta'] = $oProductos->where('id',$key)->first()->precio_venta;
      $aPesosProductos[ $key ]['numero_ventas'] = $fVentaProducto;
    }

    //Información de la página
    if ( @$aInput['aPagina'] != null) {
      $aPagina = $aInput['aPagina'];
    }else {
      $aPagina = [
        'aBreadCrumb' => [['link'=> '/proyecciones', 'label'=> 'Proyecciones'],['link'=> 'active', 'label'=> 'Calculo anual']],
        'sActivePage' => 'proyecciones',
        'sTitulo' => 'PROYECCIÓN ANUAL',
        'sDescripcion' => 'Proyección de ventas anual realizada',
      ];
    }

    return [
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa',
      'aPeriodo' => $aPeriodo,
      'aPesosProductos' => $aPesosProductos,
      'aDescuentosMayoreo' => $aDescuentosMayoreo,
      'aNumeroVentasProductos' => $aNumeroVentasProductos,
      'aCostosProductos' => $aCostosProductos,
      'aCostosPeriodoProductos' => $aCostosPeriodoProductos,
      'aGananciasPeriodoProductos' => $aGananciasPeriodoProductos,
      'aIngresosPeriodoProductos' => $aIngresosPeriodoProductos,
      'fTotalIngresos' => $fTotalIngresos,
      'fTotalEgresos' =>array_sum( $aCostosProductos ) + $fCostoFijoPeriodo  + $fCostoManoObraPeriodo,
      'fTotalGanancia' => $fTotalGanancia,
      'fCostoFijoPeriodo' => $fCostoFijoPeriodo,
      'oCostosFijos' => $aResultadoCostosFijosMensual['oCostosFijos'],
      'fMODUnitario' => (count($aPesosProductos)>0 ? @(($fCostoManoObraPeriodo) / array_sum( array_column($aPesosProductos, 'numero_ventas') )) : 0 ),
      'fCostoManoObraPeriodo' => $fCostoManoObraPeriodo,
      'fTotalCostosIndirectos' => $fTotalCostosIndirectos,
      'fTotalCostosMateriaPrima' => $fTotalCostosMateriaPrima,
      'aCarritoMateriaPrima' => $aCarritoMateriaPrima,
      'fMargenUtilidadPromedio' => (count($aMargenUtilidad)>0?@(array_sum($aMargenUtilidad)/count($aMargenUtilidad)):0)
    ];
  }

  public function generar_anual_incremento( Request $request)
  {
    $aInput = $request->all();
    $aMeses = $this->aMeses;
    //dd($aInput);
    $aUnidades = [];
    $iMesInicial = intval(date('m'));
    $iMes = $iMesInicial;
    $sMesInicial = $aMeses[ intval(date('m')) ];
    $iMesContador = 0;

    foreach ($aInput['unidades'] as $key => $fUnidades) {
      $aUnidades[$sMesInicial . '_' . $key] = floatval($fUnidades);
      $fUnidadesAcumulados = $fUnidades;
      $iMes = $iMesInicial;
      $iMesContador = 1;

      while ($iMesContador <= 12) {
        $fUnidadesAcumulados += ($fUnidades * floatval($aInput['incremento'][$key]) );
        $aUnidades[$aMeses[$iMes] . '_' . $key] = $fUnidadesAcumulados;
        if ($iMes == 12) {
          $iMes = 1;
        }else {
          $iMes ++;
        }
        $iMesContador++;
      }

    }
    //dd($aUnidades);
    return $this->generar_anual( new Request (['unidades' => $aUnidades]) );
  }

  public function generar_anual_inverso( Request $request)
  {
    $aInput = $request->all();
    //dd($aInput);

    //COSTOS FIJOS
    $fCostoFijoMensual = 0;
    $aResultadoCostosFijosMensual = $this->get_costos_fijos_mes();
    if ($aResultadoCostosFijosMensual['estatus'] == 1) {
      $fCostoFijoMensual = $aResultadoCostosFijosMensual['resultado'];
    }else {
      Session::flash('tituloMsg','Alerta!');
      Session::flash('mensaje',$aResultadoCostosFijosMensual['mensaje']);
      Session::flash('tipoMsg','error');
      return Redirect::to('/proyecciones/calculo_anual_inverso');
    }

    //COSTOS MANO DE OBRA
    $fCostoManoObraMensual = 0;
    $aResultadoCostoManoObraMensual = $this->get_costo_mano_obra_mes();
    if ($aResultadoCostoManoObraMensual['estatus'] == 1) {
      $fCostoManoObraMensual = $aResultadoCostoManoObraMensual['resultado'];
    }else {
      Session::flash('tituloMsg','Alerta!');
      Session::flash('mensaje',$aResultadoCostoManoObraMensual['mensaje']);
      Session::flash('tipoMsg','error');
      return Redirect::to('/proyecciones/calculo_anual_inverso');
    }

    //COSTOS POR PRODUCTO
    $fTotalCostosIndirectos = 0;
    $fTotalIngresos = 0;
    $aCostosProductos = [];
    $fTotalGanancia = floatval($aInput['ganancias']) + ($fCostoFijoMensual * 12 ) + ($fCostoManoObraMensual * 12 );
    $fTotalGananciaReal = 0;
    $aNumeroVentasProductos = [];
    $fTotalCostosMateriaPrima= 0;
    $aPesosProductos = [];
    $aCarritoMateriaPrima = [];
    $boolCalcularDescuentos = false;
    $aDescuentosMayoreo =  [];
    $aMargenUtilidad = [];
    $vueltas = 0;
    do {
      foreach ($aInput['porcentaje'] as $key => $fPorcentaje) {
        $fPorcentaje = floatval( $fPorcentaje );
        if ( $fPorcentaje > 0 ) {
          $iIdProducto = $key;
          $aPesosProductos[$key] = [];
          $aNumeroVentasProductos[$key] = 0;
          $aCostosProductos[$iIdProducto] = 0;



          $aResultadoProducto = $this->get_costos_ganancias_por_producto($iIdProducto,1,$aCarritoMateriaPrima,$boolCalcularDescuentos);

          if ($aResultadoProducto['estatus'] == 1) {

            //Calcular nuimero de ventas  GT = G * NV <-> NV = GT / G
            if ($aResultadoProducto['resultado']['fTotalGanancia'] == 0) {
              $fNumeroVentas = round( (( ($fTotalGanancia * $fPorcentaje)/ 100 ) ) ,2);
            }else {
              $fNumeroVentas = round( (( ($fTotalGanancia * $fPorcentaje)/ 100 ) / $aResultadoProducto['resultado']['fTotalGanancia']) ,2);
            }

            $aNumeroVentasProductos[$key] = $fNumeroVentas;
            $aResultadoProducto = $this->get_costos_ganancias_por_producto($iIdProducto,$fNumeroVentas,$aCarritoMateriaPrima,!$boolCalcularDescuentos);

            $fTotalGananciaReal += $aResultadoProducto['resultado']['fTotalGanancia'];

            //Materia prima
            $fCosto = $aResultadoProducto['resultado']['fTotalCostosMateriaPrima'];
            $aCostosProductos[$iIdProducto] += $fCosto ;
            $fTotalCostosMateriaPrima += $fCosto;
            //Carrito
             //$this->get_costos_ganancias_por_producto($iIdProducto,$fNumeroVentas,$aCarritoMateriaPrima,($boolCalcularDescuentos == true ? false : true));
            $aCarritoMateriaPrima = $aResultadoProducto['resultado']['aCarritoMateriaPrima'];
            //Descuentos
            $aDescuentosMayoreo[$iIdProducto] = $aResultadoProducto['resultado']['aDescuentosMayoreo'];

            //Costos indirectos
            $fCosto = $aResultadoProducto['resultado']['fTotalCostosIndirectos'];
            $aCostosProductos[$iIdProducto] += $fCosto;
            $fTotalCostosIndirectos += $fCosto;

            //Margen de Utilidad Promedio
            $aMargenUtilidad[$iIdProducto] = $aResultadoProducto['resultado']['fMargenUtilidad'];

            //Ingresos
            $fTotalIngresos += $aResultadoProducto['resultado']['fPrecio'];

            //Detalle producto
            $aPesosProductos[$key]['id'] = $iIdProducto;
            $aPesosProductos[$key]['peso'] = $fPorcentaje / 100;
            $aPesosProductos[$key]['producto'] = $aResultadoProducto['resultado']['sProducto'];
            $aPesosProductos[$key]['precio_venta'] = $aResultadoProducto['resultado']['fPrecio'] / $fNumeroVentas;
            $aPesosProductos[$key]['costo'] = $aCostosProductos[$iIdProducto];
            $aPesosProductos[$key]['numero_ventas'] = $fNumeroVentas;

          }else {
            Session::flash('tituloMsg','Alerta!');
            Session::flash('mensaje',$aResultadoProducto['mensaje'] . ", " . $aResultadoProducto['resultado']);
            Session::flash('tipoMsg','error');
            //return Redirect::to('/proyecciones/calculo_anual_inverso');
            return back()->withInput();
          }
        }
      }

      $boolCalcularDescuentos = ((count($aCarritoMateriaPrima) > 0 && $boolCalcularDescuentos == false) ? true : false );
      if ($boolCalcularDescuentos) {
        //dd($aCarritoMateriaPrima);
        //Reiniciarlizar contadores
        $fTotalCostosIndirectos = 0;
        $fTotalIngresos = 0;
        $aCostosProductos = [];
        $fTotalGanancia = floatval($aInput['ganancias']) + ($fCostoFijoMensual * 12 ) + ($fCostoManoObraMensual * 12 );
        $fTotalGananciaReal = 0;
        $aNumeroVentasProductos = [];
        $fTotalCostosMateriaPrima= 0;
        $aPesosProductos = [];
      }
      $vueltas++;
    } while ($boolCalcularDescuentos == true);
    //dd($aCarritoMateriaPrima);
    $fTotalGananciaReal = $fTotalGananciaReal - ($fCostoFijoMensual * 12 ) - ($fCostoManoObraMensual * 12 );
    /*dd(
      [
        'aPesosProductos' => $aPesosProductos,
        'aCostosProductos' => $aCostosProductos,
        'fTotalEgresos' => array_sum( $aCostosProductos ) + ($fCostoFijoMensual * 12) + ($fCostoManoObraMensual * 12),
        'fTotalIngresos' => $fTotalIngresos,
        'fTotalGanancia' => $fTotalGanancia,
        'fTotalGananciaReal' => $fTotalGananciaReal,
        'fCostoFijoMensual' => $fCostoFijoMensual,
        'fCostoManoObraMensual' => $fCostoManoObraMensual,
        'fTotalCostosIndirectos' => $fTotalCostosIndirectos,
        'fTotalCostosMateriaPrima' => $fTotalCostosMateriaPrima,
        'aDescuentosMayoreo' => $aDescuentosMayoreo,
        'fMargenUtilidadPromedio' => array_sum( $aMargenUtilidad ) / count( $aMargenUtilidad )
      ]
    );*/
    return view('proyecciones.calculoAnualInverso.index',[
      'aBreadCrumb' => [['link'=> '/proyecciones', 'label'=> 'Proyecciones'],['link'=> 'active', 'label'=> 'Calculo anual inverso']],
      'sActivePage' => 'proyecciones',
      'sTitulo' => 'PROYECCIÓN ANUAL INVERSO',
      'sDescripcion' => 'Realización de proyección inversa',
      'aPesosProductos' => $aPesosProductos,
      'aCostosProductos' => $aCostosProductos,
      'fTotalEgresos' => array_sum( $aCostosProductos ) + ($fCostoFijoMensual * 12) + ($fCostoManoObraMensual * 12),
      'fTotalIngresos' => $fTotalIngresos,
      'fTotalGanancia' => $fTotalGanancia,
      'fTotalGananciaReal' => $fTotalGananciaReal,
      'fCostoFijoMensual' => $fCostoFijoMensual,
      'fCostoManoObraMensual' => $fCostoManoObraMensual,
      'fTotalCostosIndirectos' => $fTotalCostosIndirectos,
      'fTotalCostosMateriaPrima' => $fTotalCostosMateriaPrima,
      'aDescuentosMayoreo' => $aDescuentosMayoreo,
      'fMargenUtilidadPromedio' => array_sum( $aMargenUtilidad ) / count( $aMargenUtilidad )
    ]);
  }

  public function valuacion(Request $request)
  {
    $aInput = $request->all();
    $aTasas[0] = $aInput['tasa_0'];//80;
    $aTasas[1] = $aInput['tasa_1'];//100;
    $aTasas[2] = $aInput['tasa_2'];//30;
    $aTasas[3] = $aInput['tasa_3'];//30;
    $aTasas[4] = $aInput['tasa_4'];//30;

    $fIngresos = $aInput['ingresos'];//4434005.76;
    $fEgresos = $aInput['egresos'];//1586575.30;

    $aMargenes[0] = $aInput['margen_0'];//55;
    $aMargenes[1] = $aInput['margen_1'];//60;
    $aMargenes[2] = $aInput['margen_2'];//52;
    $aMargenes[3] = $aInput['margen_3'];//52;
    $aMargenes[4] = $aInput['margen_4'];//52;

    $aIngresos[0] = $fIngresos + ($fIngresos * $aTasas[0] / 100);
    $aIngresos[1] = $aIngresos[0] + ($aIngresos[0] * $aTasas[1] / 100);
    $aIngresos[2] = $aIngresos[1] + ($aIngresos[1] * $aTasas[2] / 100);
    $aIngresos[3] = $aIngresos[2] + ($aIngresos[2] * $aTasas[3] / 100);
    $aIngresos[4] = $aIngresos[3] + ($aIngresos[3] * $aTasas[4] / 100);

    $aAccionistas[0] = $aInput['accionista_a'];//80;
    $aAccionistas[1] = $aInput['accionista_b'];//100;
    $aAccionistas[2] = $aInput['accionista_c'];//30;


    $fDescuento = $aInput['descuento'];//45;

    return view('proyecciones.valuacion.index',[
      'aBreadCrumb' => [['link'=> '/proyecciones', 'label'=> 'Proyecciones'],['link'=> 'active', 'label'=> 'Valuación']],
      'sActivePage' => 'proyecciones',
      'sTitulo' => 'VALUACIÓN',
      'sDescripcion' => 'Valuación generada',
      'aTasas' => $aTasas,
      'fIngresos' => $fIngresos,
      'fEgresos' => $fEgresos,
      'aIngresos' => $aIngresos,
      'aMargenes' => $aMargenes,
      'fDescuento' => $fDescuento,
      'aAccionistas' => $aAccionistas
    ]);

  }

  public function get_costos_ganancias_por_producto( $iIdProducto, $fUnidades = 1, $aCarritoMateriaPrima = [], $boolCalcularCarrito = true )
  {
    try {
      $fTotalCostosIndirectos = 0;
      $fTotalCostosMateriaPrima = 0;
      $fTotalCostos = 0;
      $aDescuentosMayoreo = [];


      //Producto (ganancias)
      $oProducto = Productos::find($iIdProducto);

      if ($oProducto->tipo == 1) {
        //Producto con costos por producto y costos indirectos

        //Materia Prima
        $oCostosMateriaPrima = CosteoProducto::select('costeo_producto.*','materia_prima.costo', 'materia_prima.unidades as materia_unidades',
        'materia_prima.concepto as nombre_materia','materia_prima.incremento_anual','materia_prima.unidades_mayoreo','materia_prima.descuento_mayoreo')
        ->where('id_producto', $iIdProducto )
        ->where('materia_prima.estado',1)
        ->leftJoin('materia_prima','materia_prima.id','costeo_producto.id_materia_prima')
        ->get();
        //dd($oCostosMateriaPrima->toArray());

        foreach ($oCostosMateriaPrima as $oMateriaPrima) {


          //Agregar al carrito de materia prima
          if ($boolCalcularCarrito == true) {

            if ( array_key_exists($oMateriaPrima->id_materia_prima,$aCarritoMateriaPrima) ) {
              $aCarritoMateriaPrima[$oMateriaPrima->id_materia_prima]['fCantidadTotal'] += $oMateriaPrima->unidades * $fUnidades;
            }else {
              $aCarritoMateriaPrima[$oMateriaPrima->id_materia_prima] = [
                'sNombreMateria' => $oMateriaPrima->nombre_materia,
                'fCosto' => $oMateriaPrima->costo,
                'fMateriaUnidades' => floatval( $oMateriaPrima->materia_unidades ),
                'fUnidadesMayoreo' => $oMateriaPrima->unidades_mayoreo,
                'fDescuentoMayoreo' => $oMateriaPrima->descuento_mayoreo,
                'fIncrementoAnual' => $oMateriaPrima->incremento_anual,
                'fCantidadTotal' => $oMateriaPrima->unidades * $fUnidades
              ];
            }
          }

          //Calcular costo
          if ( @$oMateriaPrima->costo != null && $oMateriaPrima->materia_unidades > 0 ) {
            $fCosto = ( $oMateriaPrima->costo /  floatval( $oMateriaPrima->materia_unidades )) * floatval( $oMateriaPrima->unidades );
            //Descuento por Mayoreo
            if ( $oMateriaPrima->unidades_mayoreo >= 0  && $oMateriaPrima->descuento_mayoreo > 0 ) {
              //if ( $fUnidades * ($oMateriaPrima->unidades / $oMateriaPrima->materia_unidades) >= ($oMateriaPrima->unidades_mayoreo)) {
              if (@$aCarritoMateriaPrima[$oMateriaPrima->id_materia_prima] != null) {
                //dd($aCarritoMateriaPrima);
                if ( ($aCarritoMateriaPrima[$oMateriaPrima->id_materia_prima]['fCantidadTotal'] / $oMateriaPrima->materia_unidades) >= ($oMateriaPrima->unidades_mayoreo)) {
                  $aDescuentosMayoreo[$oMateriaPrima->id_materia_prima] = [
                    'sNombreMateria' => $oMateriaPrima->nombre_materia,
                    'fDescuento' => $oMateriaPrima->descuento_mayoreo
                  ];
                  //$oMateriaPrima->descuento_mayoreo;

                  $fCosto = $fCosto - ($fCosto * floatval($oMateriaPrima->descuento_mayoreo)) / 100;

                }
              }

            }
            $fTotalCostosMateriaPrima += $fCosto;
          }else {
            return [
              'estatus' => 0,
              'mensaje' => 'Se debe ingresar el costo y las unidades correctas para la materia prima ' . $oMateriaPrima->nombre_materia . '.',
              'resultado' => null
            ];
          }
        }

        //Costos indirectos
        $oCostosIndirectos = CostosIndirectosPro::select('costos_indirectos_pro.*', 'costos_indirectos.unidades as indirecto_unidades',
        'costos_indirectos.costo', 'costos_indirectos.concepto as nombre_materia')
        ->where('id_producto', $iIdProducto )
        ->where('costos_indirectos.estado',1)
        ->leftJoin('costos_indirectos','costos_indirectos.id','costos_indirectos_pro.id_costo_indirecto')
        ->get();

        foreach ($oCostosIndirectos as $oCostoIndirecto) {
          if ( @$oCostoIndirecto->costo != null && $oCostoIndirecto->indirecto_unidades > 0 ) {
            $fCosto = ( floatval( $oCostoIndirecto->costo ) / floatval( $oCostoIndirecto->indirecto_unidades ) ) * floatval( $oCostoIndirecto->unidades );
            $fTotalCostosIndirectos += $fCosto;
          }else {
            return [
              'estatus' => 0,
              'mensaje' => 'Se debe ingresar el costo y las unidades correctas del costo indirecto ' . $oCostoIndirecto->nombre_materia . '.',
              'resultado' => null
            ];
          }
        }
        $fMargenUtilidad = ($oProducto->precio_venta - ($fTotalCostosMateriaPrima + $fTotalCostosIndirectos))/$oProducto->precio_venta;
      }else {
        if (!($oProducto->costo >= 0)) {
          return [
            'estatus' => 0,
            'mensaje' => 'Especifica el costo del producto ' . $oProducto->producto . '.',
            'resultado' => [
              'sProducto' => $oProducto->producto,
              'aDescuentosMayoreo' => $aDescuentosMayoreo,
              'fTotalCostosIndirectos' => $fTotalCostosIndirectos * $fUnidades,
              'fTotalCostosMateriaPrima' => $fTotalCostosMateriaPrima * $fUnidades,
              'fTotalCostos' => (($fTotalCostosIndirectos*$fUnidades) + ($fTotalCostosMateriaPrima*$fUnidades)),
              'fTotalGanancia' => floatval($oProducto->precio_venta)*$fUnidades - (($fTotalCostosIndirectos*$fUnidades) + ($fTotalCostosMateriaPrima*$fUnidades)),
              'fPrecio' => $oProducto->precio_venta * $fUnidades,
              'aCarritoMateriaPrima' => $aCarritoMateriaPrima
            ]
          ];
        }
        //producto compra-venta
        $fTotalCostosMateriaPrima += $oProducto->costo;
        $fMargenUtilidad = ($oProducto->precio_venta - $oProducto->costo) / $oProducto->precio_venta;
      }



      return [
        'estatus' => 1,
        'mensaje' => 'Consulta exitosa.',
        'resultado' => [
          'sProducto' => $oProducto->producto,
          'aDescuentosMayoreo' => $aDescuentosMayoreo,
          'fTotalCostosIndirectos' => $fTotalCostosIndirectos * $fUnidades,
          'fTotalCostosMateriaPrima' => $fTotalCostosMateriaPrima * $fUnidades,
          'fTotalCostos' => (($fTotalCostosIndirectos*$fUnidades) + ($fTotalCostosMateriaPrima*$fUnidades)),
          'fTotalGanancia' => floatval($oProducto->precio_venta)*$fUnidades - (($fTotalCostosIndirectos*$fUnidades) + ($fTotalCostosMateriaPrima*$fUnidades)),
          'fPrecio' => $oProducto->precio_venta * $fUnidades,
          'aCarritoMateriaPrima' => $aCarritoMateriaPrima,
          'fMargenUtilidad' => $fMargenUtilidad
        ]
      ];

    } catch (\Exception $e) {
      return [
        'estatus' => 0,
        'mensaje' => 'Error al consultar producto',
        'resultado' => $e->getMessage()
      ];
    }
  }

  public function get_dia_semana($sDay)
  {
    switch ($sDay) {
      case 'Monday':
        return 'Lunes';
      break;
      case 'Tuesday':
        return 'Martes';
      break;
      case 'Wednesday':
        return 'Miércoles';
      break;
      case 'Thursday':
        return 'Jueves';
      break;
      case 'Friday':
        return 'Viernes';
      break;
      case 'Saturday':
        return 'Sábado';
      break;
      case 'Sunday':
        return 'Domingo';
      break;
      default:
        return $sDay;
      break;
    }
  }

  public function get_dias($iDias = 7, $sTipo = 'consulta', $date = null )//consulta o proyección
  {
    //dd($iDias,$date,$sTipo );
    $aSemana = [];
    $date = ( $date == null ? date('Y-m-d') : $date );
    do {
      $iDias--;
      $iDate = strtotime("-".$iDias." days", strtotime( $date ));
      $aSemana[ date("d/m/Y", $iDate ) ] = $this->get_dia_semana(date("l",$iDate)) .' '.  date("d",$iDate) . ' de ' . $this->aMeses[ abs(date("m",$iDate)) ];
    } while ($iDias >= 1);
    return $aSemana;
  }

  public function get_semanas($iSemanas = 4, $sTipo = 'consulta', $date = null )//consulta o proyección
  {
    $aSemanas = [];
    $date = ( $date == null ? date('Y-m-d') : $date );

    $iContador = 0;
    while ( $iContador < $iSemanas) {
      $iContador++;
      $iDate = strtotime("-". (7 * $iContador) ." days", strtotime( $date ));
      $aSemanas[ date("d/m/Y", $iDate ) ] = 'Semana del ' . $this->get_dia_semana(date("l",$iDate)) .' '.  date("d",$iDate) . ' de ' . $this->aMeses[abs(date("m",$iDate))];
    }
    return array_reverse($aSemanas, true);
  }

  public function get_meses($dateInicial = null, $iNumeroMeses = 12, $sTipo = 'proyeccion')
  {
    $dateInicial = ( $dateInicial != null ? $dateInicial : date('Y-m-d') );
    $date = $dateInicial;
    $iMesContador = 0;
    $aMesesOrdenados = [];

    $aux = [];
    while ($iMesContador < $iNumeroMeses ) {
      $iMes = intval(date('m' ,strtotime( $date )));
      $sAno = date('Y' ,strtotime( $date ));
      $aMesesOrdenados[ $iMes ] = $this->aMeses[$iMes]; //. ' ' . $sAno;
      array_push($aux,$date);
      $iMesContador++;

      if ($sTipo == 'consulta') {
        $date = date('Y-m-d', strtotime("-". $iMesContador ." months", strtotime( $dateInicial )) );
      }else {
        $date = date('Y-m-d', strtotime("+". $iMesContador ." months", strtotime( $dateInicial )) );
      }
    }
    return ($sTipo == 'consulta' ? array_reverse( $aMesesOrdenados , true) : $aMesesOrdenados );
  }

  /*
  public function get_meses($dateInicial = null, $iNumeroMeses = 12, $sTipo = 'proyeccion')
  {
    $date = ( $dateInicial != null ? $dateInicial : date('Y-m-d') );
    $iMesContador = 0;
    $aMesesOrdenados = [];

    $aux = [];
    while ($iMesContador < $iNumeroMeses ) {
      $iMes = intval(date('m' ,strtotime( $date )));
      $sAno = date('Y' ,strtotime( $date ));
      $aMesesOrdenados[ $iMes ] = $this->aMeses[$iMes]; //. ' ' . $sAno;
      array_push($aux,$iMes);
      $iMesContador++;
      if ($sTipo == 'consulta') {
        $date = date('Y-m-d', strtotime("-". $iMesContador ." months", strtotime( $dateInicial )) );
      }else {
        $date = date('Y-m-d', strtotime("+". $iMesContador ." months", strtotime( $dateInicial )) );
      }
    }
    dd($aMesesOrdenados,$iMesContador,$aux);
    return ($sTipo == 'consulta' ? array_reverse( $aMesesOrdenados , true) : $aMesesOrdenados );
  }
  */

  public function get_costo_mano_obra_mes( )
  {
    try {
      //MANO DE OBRA (MOD)
      $oManoObra = ManoObra::select('mano_obra_d.*','periodos.periodo')
      ->where('mano_obra_d.id_empresa', \Auth::User()->id_empresa)
      ->where('mano_obra_d.estado',1)
      ->leftJoin('periodos','periodos.id','mano_obra_d.periodo')
      ->orderBy('mano_obra_d.id','DESC')->get();

      $fCostoManoObraMensual = 0;
      foreach ($oManoObra as $oCosto) {
        //Calculo por mes
        switch ($oCosto->periodo) {
          case 'Diario':
          $fCostoPeriodo = floatval($oCosto->costo) * 30;
          break;
          case 'Semanal':
          $fCostoPeriodo = floatval($oCosto->costo) * 4;
          break;
          case 'Quincenal':
          $fCostoPeriodo = floatval($oCosto->costo) * 2;
          break;
          case 'Mensual':
          $fCostoPeriodo = floatval($oCosto->costo);
          break;
          case 'Bimestral':
          $fCostoPeriodo = floatval($oCosto->costo) / 2;
          break;
          case 'Trimestral':
          $fCostoPeriodo = floatval($oCosto->costo) / 3;
          break;
          case 'Cuatrimestral':
          $fCostoPeriodo = floatval($oCosto->costo) / 4;
          break;
          case 'Semestral':
          $fCostoPeriodo = floatval($oCosto->costo) / 6;
          break;
          case 'Anual':
          $fCostoPeriodo = floatval($oCosto->costo) / 12;
          break;
          default:
          $fCostoPeriodo = 0;
          break;
        }
        $oCosto->costo_mensual = $fCostoPeriodo;
        $fCostoManoObraMensual += $fCostoPeriodo;
      }
      return [
        'estatus' => 1,
        'mensaje' => 'Consulta exitosa',
        'resultado' => $fCostoManoObraMensual,
        'oManoObra' => $oManoObra
      ];

    } catch (\Exception $e) {
      return [
        'estatus' => 0,
        'mensaje' => 'Error al consultar costos de mano de obra.',
        'resultado' => $e->getMessage()
      ];
    }
  }

  public function get_costos_fijos_mes()
  {
    try {
      //COSTOS FIJOS
      $oCostosFijos = CostosFijos::select('costos_fijos.*','periodos.periodo')
      ->where('costos_fijos.id_empresa', \Auth::User()->id_empresa)
      ->where('costos_fijos.estado',1)
      ->leftJoin('periodos','periodos.id','costos_fijos.periodo')
      ->orderBy('costos_fijos.id','DESC')->get();

      $fCostoFijoMensual = 0;
      foreach ($oCostosFijos as $key => $oCostos) {
        //Calculo por mes
        switch ($oCostos->periodo) {
          case 'Diario':
          $fCostoPeriodo = floatval($oCostos->costo) * 30;
          break;
          case 'Semanal':
          $fCostoPeriodo = floatval($oCostos->costo) * 4;
          break;
          case 'Quincenal':
          $fCostoPeriodo = floatval($oCostos->costo) * 2;
          break;
          case 'Mensual':
          $fCostoPeriodo = floatval($oCostos->costo);
          break;
          case 'Bimestral':
          $fCostoPeriodo = floatval($oCostos->costo) / 2;
          break;
          case 'Trimestral':
          $fCostoPeriodo = floatval($oCostos->costo) / 3;
          break;
          case 'Cuatrimestral':
          $fCostoPeriodo = floatval($oCostos->costo) / 4;
          break;
          case 'Semestral':
          $fCostoPeriodo = floatval($oCostos->costo) / 6;
          break;
          case 'Anual':
          $fCostoPeriodo = floatval($oCostos->costo) / 12;
          break;
          default:
          $fCostoPeriodo = 0;
          break;
        }
        $oCostos->costo_mensual = $fCostoPeriodo;
        $fCostoFijoMensual += $fCostoPeriodo;
      }

      return [
        'estatus' => 1,
        'mensaje' => 'Consulta exitosa',
        'resultado' => $fCostoFijoMensual,
        'oCostosFijos' => $oCostosFijos
      ];

    } catch (\Exception $e) {
      return [
        'estatus' => 0,
        'mensaje' => 'Error al consultar costos fijos.',
        'resultado' => $e->getMessage()
      ];
    }
  }

}
