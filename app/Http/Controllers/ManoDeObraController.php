<?php

namespace App\Http\Controllers;

use App\Models\ManoObra;
use App\Models\ManoObraGrupos;
use App\Models\DatosMaestros;
use App\Models\Periodos;
use App\Models\Pagos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;

class ManoDeObraController extends Controller
{
  public $aTiposContratacion = ['1'=>'Contrato indefinido','2'=>'Contrato temporal'];
  public $aContratacion = ['1'=>'Asalariado','2'=>'Asimilado','3'=>'Honorarios','4'=>'Prácticas'];

  public function index()
  {
    $oManoDeObra = ManoObra::select('mano_obra_d.*','mano_obra_grupos.nombre as nombre_grupo','periodos.periodo as nombre_periodo','periodos.dias')
    ->where('mano_obra_d.id_empresa', \Auth::User()->id_empresa )
    ->where('mano_obra_d.estado',1)
    ->leftJoin('mano_obra_grupos','mano_obra_grupos.id','mano_obra_d.id_grupo')
    ->leftJoin('periodos','periodos.id','mano_obra_d.periodo')
    ->orderBy('mano_obra_d.id','DESC')
    ->get();
    $iPermitidos = $this->get_numero_mano_obra_permitidos();
    $aCuentas = app('App\Http\Controllers\CuentasController')->get_cuentas_disponibles()->pluck('nombre','id');
    return view('catalogos.manoDeObra.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Listado de mano de obra']],
      'sActivePage' => 'mano_de_obra',
      'sTitulo' => 'PLANTILLA',
      'sDescripcion' => 'Administración de plantilla de trabajo.',
      'oManoDeObra' => $oManoDeObra,
      'iPermitidos' => $iPermitidos,
      'aCuentas' => $aCuentas,
      'aContratacion' => $this->aContratacion,

    ]);
  }

  public function get_numero_mano_obra_permitidos()
  {
    $aPermisos = \Auth::User()->permisos();
    $iMaxManoObra = $aPermisos['numero_mano_obra'];
    $iNumeroManoObra = ManoObra::where('id_empresa', \Auth::User()->id_empresa)->where('estado',1)->count();
    return $iMaxManoObra - $iNumeroManoObra;
  }

  public function create()
  {
    $periodos = $this->obtener_periodos('mano_obra');
    $oManoObraGrupos = ManoObraGrupos::where('id_empresa', \Auth::User()->id_empresa )->where('estado',1)->pluck('nombre','id');

    return view('catalogos.manoDeObra.guardar',[
      'aBreadCrumb' => [['link'=> '/mano_de_obra', 'label'=> 'Listado de mano de obra'],['link'=> 'active', 'label'=> 'Nueva mano de obra']],
      'sActivePage' => 'mano_de_obra',
      'sTitulo' => 'NUEVO REGISTRO DE PLANTILLA',
      'sDescripcion' => 'Ingresa los datos correspondientes.',
      'sTipoVista' => 'crear',
      'periodos' => $periodos,
      'oManoObraGrupos' => $oManoObraGrupos,
      'aContratacion' => $this->aContratacion,
      'aTiposContratacion' => $this->aTiposContratacion
    ]);
  }

  public function store(Request $request)
  {
    $input = $request->all();
    $iPermitidos = $this->get_numero_mano_obra_permitidos();
    if ( $iPermitidos <= 0 ) {
      Session::flash('tituloMsg','Alerta');
      Session::flash('mensaje',"Se ha llegado al límite de registros en mano de obra, escala tu licencia o contacta con soporte.");
      Session::flash('tipoMsg','warning');
      return back()->withInput();
    }

    ManoObra::creaRegistro($input);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha creado exitosamente la mano de obra.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/mano_de_obra');
  }

  public function edit( $iId )
  {
    try {
      $oManoDeObra = ManoObra::select('mano_obra_d.*','periodos.periodo as nombre_periodo','periodos.dias')
      ->where('mano_obra_d.id', $iId )
      ->where('mano_obra_d.id_empresa', \Auth::User()->id_empresa )
      ->leftJoin('periodos','periodos.id','mano_obra_d.periodo')
      ->first();

      $aPagos = $oManoDeObra->pagos();
      $oPagos = $aPagos['pagos'];


      $aResultadoImpuestos = $this->calcular_impuestos($oManoDeObra);
      //dd($aResultadoImpuestos);
      if ($aResultadoImpuestos['estatus'] == 0) {
        return view('error')->with('sError', $e->getMessage() );
      }
      $aImpuestos = $aResultadoImpuestos['resultado'];
      $oManoObraGrupos = ManoObraGrupos::where('id_empresa', \Auth::User()->id_empresa )->where('estado',1)->pluck('nombre','id');
      $periodos = $this->obtener_periodos('mano_obra');
      $aCuentas = app('App\Http\Controllers\CuentasController')->get_cuentas_disponibles()->pluck('nombre','id');

      return view('catalogos.manoDeObra.guardar',[
        'aBreadCrumb' => [['link'=> '/mano_de_obra', 'label'=> 'Listado de mano de obra'],['link'=> 'active', 'label'=> 'Editar mano de obra']],
        'sActivePage' => 'mano_de_obra',
        'sTitulo' => mb_strtoupper( $oManoDeObra->concepto ),
        'sDescripcion' => 'Actualiza los datos del registro de plantilla.',
        'sTipoVista' => 'editar',
        'oManoDeObra' => $oManoDeObra,
        'periodos' => $periodos,
        'oManoObraGrupos' => $oManoObraGrupos,
        'aImpuestos' => $aImpuestos,
        'aCuentas' => $aCuentas,
        'aPagos' => $oManoDeObra->pagos(),
        'oPagos' => $oPagos,
        'aContratacion' => $this->aContratacion,
        'aTiposContratacion' => $this->aTiposContratacion
      ]);
    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() );
    }
  }

  public function calcular_impuestos($oManoDeObra)
  {
    $aImpuestosMeses[1] = ['sMes' => 'Enero', 'iDias' => 31, 'fImpuestos' => 0];
    $aImpuestosMeses[2] = ['sMes' => 'Febrero', 'iDias' => 28, 'fImpuestos' => 0, 'fImpuestosBisiesto' => 0];
    $aImpuestosMeses[3] = ['sMes' => 'Marzo', 'iDias' => 31, 'fImpuestos' => 0];
    $aImpuestosMeses[4] = ['sMes' => 'Abril', 'iDias' => 30, 'fImpuestos' => 0];
    $aImpuestosMeses[5] = ['sMes' => 'Mayo', 'iDias' => 31, 'fImpuestos' => 0];
    $aImpuestosMeses[6] = ['sMes' => 'Junio', 'iDias' => 30, 'fImpuestos' => 0];
    $aImpuestosMeses[7] = ['sMes' => 'Julio', 'iDias' => 31, 'fImpuestos' => 0];
    $aImpuestosMeses[8] = ['sMes' => 'Agosto', 'iDias' => 31, 'fImpuestos' => 0];
    $aImpuestosMeses[9] = ['sMes' => 'Septiembre', 'iDias' => 30, 'fImpuestos' => 0];
    $aImpuestosMeses[10] = ['sMes' => 'Octubre', 'iDias' => 30, 'fImpuestos' => 0];
    $aImpuestosMeses[11] = ['sMes' => 'Noviembre', 'iDias' => 30, 'fImpuestos' => 0];
    $aImpuestosMeses[12] = ['sMes' => 'Diciembre', 'iDias' => 31, 'fImpuestos' => 0];

    //FACTOR
    $dateFechaInicio = strtotime($oManoDeObra->inicio);
    $dateFechaActual = strtotime(date('Y-m-d'));
    $iDiasDiferencia = ($dateFechaInicio-$dateFechaActual)/86400;
    $iDiasDiferencia = abs($iDiasDiferencia);
    $iDiasDiferencia = floor($iDiasDiferencia);
    $iDiasVacaciones = 0;
    $sAntiguedad = 'menos de un año';
    if ($iDiasDiferencia > 365) {
      $sAntiguedad = 'más de un año';
    }
    if ($iDiasDiferencia < (365*2)) {
      $iDiasVacaciones = 6;
    }elseif ((365*2) <= $iDiasDiferencia && $iDiasDiferencia < (365*3)) {
      $sAntiguedad = 'más de dos años';
      $iDiasVacaciones = 8;
    }elseif ((365*3) <= $iDiasDiferencia && $iDiasDiferencia < (365*4)) {
      $sAntiguedad = 'más de tres años';
      $iDiasVacaciones = 10;
    }elseif ((365*4) <= $iDiasDiferencia && $iDiasDiferencia < (365*5)) {
      $sAntiguedad = 'más de cuatro años';
      $iDiasVacaciones = 12;
    }elseif ((365*5) <= $iDiasDiferencia && $iDiasDiferencia < (365*10)) {
      $sAntiguedad = 'más de cinco años';
      $iDiasVacaciones = 14;
    }elseif ((365*10) <= $iDiasDiferencia && $iDiasDiferencia < (365*15)) {
      $sAntiguedad = 'más de 10 años';
      $iDiasVacaciones = 16;
    }elseif ((365*15) <= $iDiasDiferencia && $iDiasDiferencia < (365*20)) {
      $sAntiguedad = 'más de 15 años';
      $iDiasVacaciones = 18;
    }elseif ((365*20) <= $iDiasDiferencia && $iDiasDiferencia < (365*25)) {
      $sAntiguedad = 'más de 20 años';
      $iDiasVacaciones = 20;
    }elseif ((365*25) <= $iDiasDiferencia) {
      $sAntiguedad = 'más de 25 años';
      $iDiasVacaciones = 22;
    }

    $aResultadoCostoDiario = $this->get_costo_mano_obra_diario($oManoDeObra->id);
    if ($aResultadoCostoDiario['estatus'] != 1) {
      return $aResultadoCostoDiario;
    }
    $fSueldoDiario = $aResultadoCostoDiario['resultado'];

    $aFactor['fAguinaldo'] = $oManoDeObra->dias_aguinaldo / 365;
    $aFactor['fPrimaVacacional'] = ($iDiasVacaciones * $oManoDeObra->prima_vacacional / 100) / 365;
    $aFactor['fFactor'] = $aFactor['fAguinaldo'] + $aFactor['fPrimaVacacional'] + 1;
    $aFactor['fSBC'] = $aFactor['fFactor'] * $fSueldoDiario;
    //dd($aFactor['fSBC']);

    //SEGURO DE ENFERMEDADES Y MATERNIDAD
    $oDatosMaestros = DatosMaestros::first();
    $fUMA = floatval($oDatosMaestros->uma);
    $fArt_106_1_LSS = floatval($oDatosMaestros->art_106_1_lss) / 100;
    $fArt_25_LSS_pencionados = floatval($oDatosMaestros->art_25_lss_pencionados) / 100;
    $fArt_25_LSS = floatval($oDatosMaestros->art_25_lss) / 100;
    $fArt_106_2_LSS = floatval($oDatosMaestros->art_106_2_lss) / 100;
    $fArt_71_211_LSS = floatval($oDatosMaestros->art_71_211_lss) / 100;
    $fArt_147_LSS = floatval($oDatosMaestros->art_147_lss) / 100;
    $fArt_168_1_71_211_LSS = floatval($oDatosMaestros->art_168_1_71_211_lss) / 100;
    $fArt_168_2_71_211_LSS = floatval($oDatosMaestros->art_168_2_71_211_lss) / 100;
    $fArt_29_2_INFONAVIT = floatval($oDatosMaestros->art_29_2_infonavit) / 100;

    foreach ($aImpuestosMeses as $key => $aMes) {
      //EN ESPECIE  CUOTA FIJA
      $aImpuestosMeses[$key]['fImpuestos'] += $aMes['iDias'] * $fUMA * $fArt_106_1_LSS;
      //EN ESPECIE  PARA PENSIONADOS
      $aImpuestosMeses[$key]['fImpuestos'] += $aMes['iDias'] * $aFactor['fSBC'] * $fArt_25_LSS_pencionados;
      //EN DINERO PARA ENFERMEDAD Y MATERNIDAD
      $aImpuestosMeses[$key]['fImpuestos'] += $aMes['iDias'] * $aFactor['fSBC'] * $fArt_25_LSS;
      //SI EL SALARIO DIARIO ES MAYOR A 3 UMA , SE LE AGREGA ESTA CUARTA CANTIDAD
      $aImpuestosMeses[$key]['fImpuestos'] += $aMes['iDias'] * ($aFactor['fSBC'] - $fUMA*3) * $fArt_106_2_LSS;
      //Cálculo de las cuotas obrero patronales por lo seguros de riesgos de trabajo
      $aImpuestosMeses[$key]['fImpuestos'] += $aMes['iDias'] * $aFactor['fSBC'] * $fArt_71_211_LSS;
      //Determinación de las cuotas obrero-patronales para el seguro de invalidez y vida
      $aImpuestosMeses[$key]['fImpuestos'] += $aMes['iDias'] * $aFactor['fSBC'] * $fArt_147_LSS;



      //BIMESTRALES
      if ($key == 2 || $key == 4 || $key == 6 || $key == 8 || $key == 10 || $key == 12) {
        //Determinación de la cuota patronal en el ramo del retiro
        $aImpuestosMeses[$key]['fImpuestos'] += ($aImpuestosMeses[$key-1]['iDias'] + $aImpuestosMeses[$key]['iDias']) * $aFactor['fSBC'] * $fArt_168_1_71_211_LSS;
        //Determinación de las cuotas obrero-patronales en los ramos de cesantía en edad avanzaday vejez
        $aImpuestosMeses[$key]['fImpuestos'] += ($aImpuestosMeses[$key-1]['iDias'] + $aImpuestosMeses[$key]['iDias']) * $aFactor['fSBC'] * $fArt_168_2_71_211_LSS;
        //Determinación de la aportación al Infonavit
        $aImpuestosMeses[$key]['fImpuestos'] += ($aImpuestosMeses[$key-1]['iDias'] + $aImpuestosMeses[$key]['iDias']) * $aFactor['fSBC'] * $fArt_29_2_INFONAVIT;

      }

      if ($key == 2) {//Febrero bisiesto
        $aImpuestosMeses[$key]['fImpuestosBisiesto'] += 29 * $fUMA * $fArt_106_1_LSS;
        $aImpuestosMeses[$key]['fImpuestosBisiesto'] += 29 * $aFactor['fSBC'] * $fArt_25_LSS_pencionados;
        $aImpuestosMeses[$key]['fImpuestosBisiesto'] += 29 * $aFactor['fSBC'] * $fArt_25_LSS;
        $aImpuestosMeses[$key]['fImpuestosBisiesto'] += 29 * ($aFactor['fSBC'] - $fUMA*3) * $fArt_106_2_LSS;
        $aImpuestosMeses[$key]['fImpuestosBisiesto'] += 29 * $aFactor['fSBC'] * $fArt_71_211_LSS;
        $aImpuestosMeses[$key]['fImpuestosBisiesto'] += 29 * $aFactor['fSBC'] * $fArt_147_LSS;
        $aImpuestosMeses[$key]['fImpuestosBisiesto'] += ($aImpuestosMeses[$key-1]['iDias'] + 29) * $aFactor['fSBC'] * $fArt_168_1_71_211_LSS;
        $aImpuestosMeses[$key]['fImpuestosBisiesto'] += ($aImpuestosMeses[$key-1]['iDias'] + 29) * $aFactor['fSBC'] * $fArt_168_2_71_211_LSS;
        $aImpuestosMeses[$key]['fImpuestosBisiesto'] += ($aImpuestosMeses[$key-1]['iDias'] + 29) * $aFactor['fSBC'] * $fArt_29_2_INFONAVIT;
      }
    }



    $aResultado['estatus'] = 1;
    $aResultado['mensaje'] = 'Consulta exitosa';
    $aResultado['resultado'] = [
      'aImpuestosMeses' => $aImpuestosMeses,
      'iDiasVacaciones' => $iDiasVacaciones,
      'fSueldoDiario' => $fSueldoDiario,
      'aFactor' => $aFactor,
      'oDatosMaestros' => $oDatosMaestros,
      'sAntiguedad' => $sAntiguedad
    ];

    return $aResultado;
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    ManoObra::actualizaRegistro($aInput);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha actualizado la mano de obra exitosamente.");
    Session::flash('tipoMsg','success');
    //return Redirect::to('/mano_de_obra');
    return back();
  }

  public function get_costo_mano_obra_diario($iIdManoDeObra = 0)
  {
    try {
      if ( $iIdManoDeObra == 0 ) {
        $oManoObra = ManoObra::select('mano_obra_d.*','periodos.periodo')
        ->where('mano_obra_d.id_empresa', \Auth::User()->id_empresa)
        ->where('mano_obra_d.estado',1)
        ->leftJoin('periodos','periodos.id','mano_obra_d.periodo')
        ->orderBy('mano_obra_d.id','DESC')->get();
      }else {
        $oManoObra = ManoObra::where('mano_obra_d.id',$iIdManoDeObra)->select('mano_obra_d.*','periodos.periodo')
        ->where('mano_obra_d.id_empresa', \Auth::User()->id_empresa)
        ->where('mano_obra_d.estado',1)
        ->leftJoin('periodos','periodos.id','mano_obra_d.periodo')
        ->orderBy('mano_obra_d.id','DESC')->get();
      }

      $fCostoManoObraDiario = 0;
      foreach ($oManoObra as $oCosto) {
        //Calculo por mes
        switch ($oCosto->periodo) {
          case 'Diario':
          $fCostoPeriodo = floatval($oCosto->costo);
          break;
          case 'Semanal':
          $fCostoPeriodo = floatval($oCosto->costo) / 7;
          break;
          case 'Quincenal':
          $fCostoPeriodo = floatval($oCosto->costo) / 15;
          break;
          case 'Mensual':
          $fCostoPeriodo = floatval($oCosto->costo) / 30;
          break;
          case 'Bimestral':
          $fCostoPeriodo = floatval($oCosto->costo) / 60;
          break;
          case 'Trimestral':
          $fCostoPeriodo = floatval($oCosto->costo) / 90;
          break;
          case 'Cuatrimestral':
          $fCostoPeriodo = floatval($oCosto->costo) / 120;
          break;
          case 'Semestral':
          $fCostoPeriodo = floatval($oCosto->costo) / 180;
          break;
          case 'Anual':
          $fCostoPeriodo = floatval($oCosto->costo) / 360;
          break;
          default:
          $fCostoPeriodo = 0;
          break;
        }
        $fCostoManoObraDiario += $fCostoPeriodo;
      }
      return [
        'estatus' => 1,
        'mensaje' => 'Consulta exitosa',
        'resultado' => $fCostoManoObraDiario
      ];
    } catch (\Exception $e) {
      return [
        'estatus' => 0,
        'mensaje' => 'Error al consultar costos de mano de obra.',
        'resultado' => $e->getMessage()
      ];
    }
  }

  public function get_grupos()
  {
    $oManoObraGrupos = ManoObraGrupos::where('id_empresa', \Auth::User()->id_empresa )->where('estado',1)->get();
    $Html = view('catalogos.manoDeObra.editGrupos')->with('oManoObraGrupos',$oManoObraGrupos)->render();

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa.',
      'resultado' => $Html
    ]);
  }

  public function store_update_grupos(Request $request)
  {
    $input = $request->all();
    if ($input['id'] > 0) {
      ManoObraGrupos::actualizaRegistro($input);
    }else {
      ManoObraGrupos::creaRegistro($input);
    }
    $oManoObraGrupos = ManoObraGrupos::where('id_empresa', \Auth::User()->id_empresa )->where('estado',1)->get();
    $Html = view('catalogos.manoDeObra.editGrupos')->with('oManoObraGrupos',$oManoObraGrupos)->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Se han guardado los datos exitosamente.',
      'resultado' => $Html
    ]);
  }

  public function destroy_grupos(Request $request)
  {
    $aInput = $request->all();

    $iContadorEnUso = ManoObra::where('id_grupo', $aInput['id'] )->count();

    if ( $iContadorEnUso > 0) {
      return response()->json([
        'estatus' => 0,
        'mensaje' => 'El grupo ya esta asignado a trabajadores, modifica su grupo para poder eliminarlo.',
        'resultado' => null
      ]);
    }else {
      $oManoDeObra = ManoObraGrupos::eliminarRegistro( $aInput['id']);
      $oManoObraGrupos = ManoObraGrupos::where('id_empresa', \Auth::User()->id_empresa )->where('estado',1)->get();
      $Html = view('catalogos.manoDeObra.editGrupos')->with('oManoObraGrupos',$oManoObraGrupos)->render();
      return response()->json([
        'estatus' => 1,
        'mensaje' => 'Se han guardado los datos exitosamente.',
        'resultado' => $Html
      ]);
    }
  }

  public function obtener_periodos($sTipo = '')
  {
    $periodos = [];

    if ($sTipo == 'mano_obra' ) {
      $periodos_ = Periodos::whereIn('id',[3,4,5])->get();
    }else {
      $periodos_ = Periodos::all();
    }

    foreach ($periodos_ as $periodo){
      $periodos[$periodo->id] = ($periodo->dias == 1) ? $periodo->periodoDias.' día' : $periodo->periodoDias.' días';
    }
    return $periodos;
  }

  public function destroy(Request $request)
  {
    $aInput = $request->all();
    $oManoDeObra = ManoObra::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado la mano de obra exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/mano_de_obra');
  }
}
