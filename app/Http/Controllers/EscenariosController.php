<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Productos;

class EscenariosController extends Controller
{
  public function index()
  {
    return view('escenarios.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Escenarios']],
      'sActivePage' => 'escenarios',
      'sTitulo' => 'ESCENARIOS',
      'sDescripcion' => 'Selecciona un creador de escenarios.'
    ]);
  }

  public function calculo_temporalidad()
  {
    $oProductos = Productos::where('id_empresa', \Auth::User()->id_empresa)
    ->where('estado',1)
    ->orderBy('id','DESC')
    ->get();
    $aMeses = app('App\Http\Controllers\ProyeccionesController')->get_meses();
    //dd($aMeses);
    return view('escenarios.escenarioTemporalidad.index',[
      'aBreadCrumb' => [['link'=> '/escenarios', 'label'=> 'Escenarios'],['link'=> 'active', 'label'=> 'Escenario de temporalidad']],
      'sActivePage' => 'escenarios',
      'sTitulo' => 'ESCENARIO DE TEMPORALIDAD',
      'sDescripcion' => 'Crea el escenario de temporalidad.',
      'aMeses' => $aMeses,
      'oProductos' => $oProductos,
    ]);
  }

  public function generar_temporalidad(Request $request)
  {
    $aInput = $request->all();
    //dd($aInput);
    $aMeses = app('App\Http\Controllers\ProyeccionesController')->get_meses(date('Y-m-d'));
    $aUnidades = [];

    foreach ($aMeses as $keyMes => $sMes) {
      foreach ($aInput['unidades_promedio'] as $key => $fUnidades) {

        $aUnidades[$sMes . '_' . $key] = $fUnidades;
        if ( @$aInput['mes_alto_' . $keyMes] == '1') {
          $aUnidades[$sMes . '_' . $key] += ($fUnidades * $aInput['procentaje_variacion'])/100;
        }elseif ( @$aInput['mes_bajo_' . $keyMes] == '1' ) {
          $aUnidades[$sMes . '_' . $key] -= ((($fUnidades * $aInput['procentaje_variacion'])/100) <= $fUnidades ? (($fUnidades * $aInput['procentaje_variacion'])/100) : 0);
        }elseif ( @$aInput['mes_zero_' . $keyMes] == '1' ) {
          $aUnidades[$sMes . '_' . $key] = 0;
        }
      }
    }
    return app('App\Http\Controllers\ProyeccionesController')->generar_anual( new Request ([
      'aPagina' => [
        'aBreadCrumb' => [['link'=> '/escenarios', 'label'=> 'Escenarios'],['link'=> '/escenarios/calculo_temporalidad', 'label'=> 'Escenario de temporalidad'],['link'=> 'active', 'label'=> 'Resultado escenario de temporalidad']],
        'sActivePage' => 'escenarios',
        'sTitulo' => 'ESCENARIO DE TEMPORALIDAD',
        'sDescripcion' => 'Resultado de escenario'
      ],
      'unidades' => $aUnidades,
      'porcentaje_alza_materia_prima' => $aInput['porcentaje_alza_materia_prima'],
      'mes_alza_materia_prima' => $aInput['mes_alza_materia_prima']
    ]));
  }

  public function calculo_continuo()
  {
    $oProductos = Productos::where('id_empresa', \Auth::User()->id_empresa)
    ->where('estado',1)
    ->orderBy('id','DESC')
    ->get();
    $aMeses = app('App\Http\Controllers\ProyeccionesController')->get_meses();

    return view('escenarios.escenarioContinuo.index',[
      'aBreadCrumb' => [['link'=> '/escenarios', 'label'=> 'Escenarios'],['link'=> 'active', 'label'=> 'Escenario continuo']],
      'sActivePage' => 'escenarios',
      'sTitulo' => 'ESCENARIO CONTINUO',
      'sDescripcion' => 'Crea el escenario continuo.',
      'aMeses' => $aMeses,
      'oProductos' => $oProductos,
    ]);
  }

  public function generar_continuo(Request $request)
  {
    $aInput = $request->all();
    //dd($aInput);
    $aMeses = app('App\Http\Controllers\ProyeccionesController')->get_meses(date('Y-m-d'));
    $aUnidades = [];

    foreach ($aMeses as $keyMes => $sMes) {
      foreach ($aInput['unidades_promedio'] as $key => $fUnidades) {
        $aUnidades[$sMes . '_' . $key] = $fUnidades;
      }
    }
    return app('App\Http\Controllers\ProyeccionesController')->generar_anual( new Request ([
      'aPagina' => [
        'aBreadCrumb' => [['link'=> '/escenarios', 'label'=> 'Escenarios'],['link'=> '/escenarios/calculo_continuo', 'label'=> 'Escenario continuo'],['link'=> 'active', 'label'=> 'Resultado escenario continuo']],
        'sActivePage' => 'escenarios',
        'sTitulo' => 'ESCENARIO CONTINUO',
        'sDescripcion' => 'Resultado de escenario'
      ],
      'unidades' => $aUnidades,
      'porcentaje_alza_materia_prima' => $aInput['porcentaje_alza_materia_prima'],
      'mes_alza_materia_prima' => $aInput['mes_alza_materia_prima']
    ]));
  }
}
