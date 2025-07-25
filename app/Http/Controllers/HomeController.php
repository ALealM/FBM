<?php

namespace App\Http\Controllers;
use App\Models\Ventas;
use App\Models\Productos;
use Illuminate\Http\Request;
use App\Models\Pagos;
use App\Models\Cobros;

class HomeController extends Controller
{
  public function index()
  {
    $oEmpresa = \Auth::User()->empresa();

    //Sistema de productos (1)
    if ($oEmpresa->tipo_sistema == 1) {
      $aDatos = [ 'sTemporalidad' => 'semanal', 'sTipo' => 'consulta' ];
      $request = new Request($aDatos);
      $aResultado = $this->generar_calculo_por_ventas($request);
    }else {
      //Sistema de proyectos (2)
      $aResultado = [];
      $aResultado['oProyectos'] = app('App\Http\Controllers\ProyectosController')->get_proyectos();
      $aResultado['oIngresos'] = $this->get_ingresos_sistema_proyectos();
      $aResultado['oEgresos'] = $this->get_egresos_sistema_proyectos();

      $aResultado['fIngresos'] = $aResultado['oIngresos']->sum('monto');
      $aResultado['fEgresos'] = $aResultado['oEgresos']->sum('monto');
      $aResultado['oCostosFijos'] = app('App\Http\Controllers\CostosFijosController')->get_costos_fijos();
      $aResultado['aImpuestosContra'] = app('App\Http\Controllers\CobrosController')->get_impuestos();
      $aResultado['aImpuestosFavor'] = app('App\Http\Controllers\PagosController')->get_impuestos();
    }

    $aResultado['oEmpresa'] = $oEmpresa;
    $aResultado['aBreadCrumb'] = [['link'=> 'active', 'label'=> 'Bienvenido']];
    $aResultado['sActivePage'] = 'inicio';
    $aResultado['sTitulo'] = 'BIENVENIDO';
    $aResultado['sDescripcion'] = 'Bienvenido a su sistema FBM.';
    return view('inicio.index',$aResultado);
  }

  public function get_ingresos_sistema_proyectos()
  {
    //Proyectos
    $oCobrosProyectos = Cobros::select('cobros.*','movimientos_cuentas.monto')
    ->where('cobros.tipo',1)//Proyectos
    ->where('proyectos.estado',1)
    ->where('proyectos.id_empresa',\Auth::User()->id_empresa)
    ->leftJoin('proyectos','proyectos.id','cobros.id_elemento')
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id','cobros.id_movimiento')
    ->get();

    return $oCobrosProyectos;
  }

  public function get_egresos_sistema_proyectos()
  {
    //Pagos de mano de obra
    $oPagosManoObra = Pagos::select('pagos.*','movimientos_cuentas.monto')
    ->where('pagos.tipo',1)//Nomina
    ->where('pagos.estado',1)
    ->where('mano_obra_d.estado',1)
    ->where('mano_obra_d.id_empresa',\Auth::User()->id_empresa)
    ->leftJoin('mano_obra_d','mano_obra_d.id','pagos.id_elemento')
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id','pagos.id_movimiento')
    ->get();

    //Pagos costos fijos
    $oPagosCostosFijos = Pagos::select('pagos.*','movimientos_cuentas.monto')
    ->where('pagos.tipo',2)//costos fijos
    ->where('pagos.estado',1)
    ->where('costos_fijos.estado',1)
    ->where('costos_fijos.id_empresa',\Auth::User()->id_empresa)
    ->leftJoin('costos_fijos','costos_fijos.id','pagos.id_elemento')
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id','pagos.id_movimiento')
    ->get();

    return $oPagosManoObra->merge($oPagosCostosFijos);
  }


  public function generar_calculo_por_ventas(Request $request)
  {
    $aInput = $request->all();
    $dateInicial = date('Y-m-d');
    $dateFinal = date('Y-m-d');
    if ($aInput['sTemporalidad'] == 'fechas') {
      if ($aInput['tipo_fechas'] == 'semanal') {
        $aInput['sTemporalidad'] = 'semanal';
        $dateFinal = date('Y-m-d', strtotime("+6 days", strtotime($aInput['fecha_inicio'])) );
      }elseif ($aInput['tipo_fechas'] == 'mensual') {
        $aInput['sTemporalidad'] = 'mensual';
        $dateFinal = date('Y-m-d', strtotime("+28 days", strtotime($aInput['fecha_inicio'])) );
      }/*elseif ($aInput['tipo_fechas'] == 'semestral') {
        $aInput['sTemporalidad'] = 'semestral';
        $dateFinal = date('Y-m-d', strtotime("+6 months", strtotime($aInput['fecha_inicio'])) );
      }*/else {
        $aInput['sTemporalidad'] = 'anual';
        $dateFinal = date('Y-m-d', strtotime("+11 months", strtotime($aInput['fecha_inicio'])) );
      }
    }

    //OBTENER VENTAS DEL PERIODO
    $aUnidades = [];
    switch ($aInput['sTemporalidad']) {
      case 'semanal':
      $dateInicial = date('Y-m-d',strtotime("-6 days",strtotime( $dateFinal )));
      $oVentas = Ventas::where('id_empresa',\Auth::User()->id_empresa)->whereBetween('fecha_registro', [$dateInicial.' 00:00:00',$dateFinal.' 23:59:59'] )->get();
      foreach ($oVentas as $key => $oVenta) {
        $sFecha = date('d/m/Y',strtotime($oVenta->fecha_registro));
        $aUnidades[$sFecha.'_'.$oVenta->id_producto] = @$aUnidades[$sFecha.'_'.$oVenta->id_producto] + $oVenta->unidades_vendidas;
      }
      break;
      case 'mensual':

      $dateDiaSemana1 = date('Y-m-d', strtotime("-7 days", strtotime( $dateFinal )) );
      $dateDiaSemana2 = date('Y-m-d', strtotime("-14 days", strtotime( $dateFinal )) );
      $dateDiaSemana3 = date('Y-m-d', strtotime("-21 days", strtotime( $dateFinal )) );
      $dateDiaSemana4 = date('Y-m-d', strtotime("-28 days", strtotime( $dateFinal )) );

      $dateInicial = $dateDiaSemana4;

      $oVentasSemana1 = Ventas::where('id_empresa',\Auth::User()->id_empresa)->whereBetween('fecha_registro', [ $dateDiaSemana1.' 00:00:00' , $dateFinal.' 23:59:59' ])->get();
      $oVentasSemana2 = Ventas::where('id_empresa',\Auth::User()->id_empresa)->whereBetween('fecha_registro', [ $dateDiaSemana2.' 00:00:00' , $dateDiaSemana1.' 23:59:59' ])->get();
      $oVentasSemana3 = Ventas::where('id_empresa',\Auth::User()->id_empresa)->whereBetween('fecha_registro', [ $dateDiaSemana3.' 00:00:00' , $dateDiaSemana2.' 23:59:59' ])->get();
      $oVentasSemana4 = Ventas::where('id_empresa',\Auth::User()->id_empresa)->whereBetween('fecha_registro', [ $dateDiaSemana4.' 00:00:00' , $dateDiaSemana3.' 23:59:59' ])->get();

      foreach ($oVentasSemana4 as $key => $oVenta) {
        $aUnidades[date('d/m/Y',strtotime($dateDiaSemana4)).'_'.$oVenta->id_producto] = @$aUnidades[$dateDiaSemana4.'_'.$oVenta->id_producto] + $oVenta->unidades_vendidas;
      }
      foreach ($oVentasSemana3 as $key => $oVenta) {
        $aUnidades[date('d/m/Y',strtotime($dateDiaSemana3)).'_'.$oVenta->id_producto] = @$aUnidades[$dateDiaSemana3.'_'.$oVenta->id_producto] + $oVenta->unidades_vendidas;
      }
      foreach ($oVentasSemana2 as $key => $oVenta) {
        $aUnidades[date('d/m/Y',strtotime($dateDiaSemana2)).'_'.$oVenta->id_producto] = @$aUnidades[$dateDiaSemana2.'_'.$oVenta->id_producto] + $oVenta->unidades_vendidas;
      }
      foreach ($oVentasSemana1 as $key => $oVenta) {
        $aUnidades[date('d/m/Y',strtotime($dateDiaSemana1)).'_'.$oVenta->id_producto] = @$aUnidades[$dateDiaSemana1.'_'.$oVenta->id_producto] + $oVenta->unidades_vendidas;
      }

      break;
      case 'semestral':
      $dateInicial = date('Y-m', strtotime("-5 months", strtotime( $dateFinal ))) . '-01';
      $oVentas = Ventas::where('id_empresa',\Auth::User()->id_empresa)->whereBetween('fecha_registro', [$dateInicial.' 00:00:00',$dateFinal.' 23:59:59'] )->get();
      foreach ($oVentas as $key => $oVenta) {
        $sFecha = intval(date('m',strtotime($oVenta->fecha_registro)));
        $aUnidades[$sFecha.'_'.$oVenta->id_producto] = @$aUnidades[$sFecha.'_'.$oVenta->id_producto] + $oVenta->unidades_vendidas;
      }
      break;
      default://anual
      $dateInicial = date('Y-m', strtotime("-11 months", strtotime( $dateFinal ))) . '-01';
      $oVentas = Ventas::where('id_empresa',\Auth::User()->id_empresa)->whereBetween('fecha_registro', [ $dateInicial.' 00:00:00' , $dateFinal.' 23:59:59' ])->get();
      foreach ($oVentas as $key => $oVenta) {
        $sFecha = intval(date('m',strtotime($oVenta->fecha_registro)));
        $aUnidades[$sFecha.'_'.$oVenta->id_producto] = @$aUnidades[$sFecha.'_'.$oVenta->id_producto] + $oVenta->unidades_vendidas;
      }
      break;
    }

    $aDatos = [
      "sTipo" => "consulta",
      "sPeriodo" => $aInput['sTemporalidad'],
      'date' => $dateFinal,
      "unidades" => $aUnidades
    ];

    //GENERAR CALCULO
    $aResultado = app('App\Http\Controllers\ProyeccionesController')->generar_calculo_por_ventas($aDatos);
    $aResultado['sTemporalidad'] = $aInput['sTemporalidad'];
    $aResultado['oEmpresa'] = \Auth::User()->empresa();

    if ($aInput['sTipo'] == 'consulta') {
      return $aResultado;
    }else {
      if (count($aUnidades) == 0) {
        $sHtmlGraficaCostosFijos = view('inicio.graficaCostosFijos',$aResultado)->render();
        $sHtmlGraficaDetalleCostosVariables = view('inicio.graficaDetalleCostosVariables',$aResultado)->render();
        $sHtmlGraficaPuntoEquilibrio = view('inicio.graficaPuntoEquilibrio',$aResultado)->render();
        $sHtmlTableEstadoResultados = view('inicio.tableEstadoResultados',$aResultado)->render();

        return response()->json([
          'estatus' => (\Auth::User()->empresa()->tipo_sistema==1? 0 : 1),
          'mensaje' => 'No hay ventas en este periodo.',
          'resultado' => $aResultado,
          'sFechaInicial' => date('d/m/Y', strtotime( $dateInicial )),
          'sFechaFinal' => date('d/m/Y', strtotime( $dateFinal )),
          'sHtmlGraficaCostosFijos' => $sHtmlGraficaCostosFijos,
          'sHtmlGraficaDetalleCostosVariables' => $sHtmlGraficaDetalleCostosVariables,
          'sHtmlGraficaPuntoEquilibrio' => $sHtmlGraficaPuntoEquilibrio,
          'sHtmlTableEstadoResultados' => $sHtmlTableEstadoResultados
        ]);
      }else {
        $sHtmlGraficaVentas = view('inicio.graficaVentas',$aResultado)->render();
        $sHtmlGraficaCostosFijos = view('inicio.graficaCostosFijos',$aResultado)->render();
        $sHtmlGraficaDetalleCostosVariables = view('inicio.graficaDetalleCostosVariables',$aResultado)->render();
        $sHtmlGraficaPuntoEquilibrio = view('inicio.graficaPuntoEquilibrio',$aResultado)->render();
        $sHtmlTableDetalleProductos = view('inicio.tableDetalleProductos',$aResultado)->render();
        $sHtmlTableEstadoResultados = view('inicio.tableEstadoResultados',$aResultado)->render();

        return response()->json([
          'estatus' => 1,
          'mensaje' => 'Consulta exitosa.',
          'resultado' => $aResultado,
          'sFechaInicial' => date('d/m/Y', strtotime( $dateInicial )),
          'sFechaFinal' => date('d/m/Y', strtotime( $dateFinal )),
          'sHtmlGraficaVentas' => $sHtmlGraficaVentas,
          'sHtmlGraficaCostosFijos' => $sHtmlGraficaCostosFijos,
          'sHtmlGraficaDetalleCostosVariables' => $sHtmlGraficaDetalleCostosVariables,
          'sHtmlGraficaPuntoEquilibrio' => $sHtmlGraficaPuntoEquilibrio,
          'sHtmlTableDetalleProductos' => $sHtmlTableDetalleProductos,
          'sHtmlTableEstadoResultados' => $sHtmlTableEstadoResultados
        ]);
      }
    }
  }
}
