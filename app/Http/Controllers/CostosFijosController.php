<?php

namespace App\Http\Controllers;
use App\Models\Periodos;
use App\Models\CostosFijos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;



class CostosFijosController extends Controller
{
  public function index()
  {
    $oFijos = $this->get_costos_fijos();
    $aCuentas = app('App\Http\Controllers\CuentasController')->get_cuentas_disponibles()->pluck('nombre','id');
    return view('catalogos.costosFijos.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Listado de costos fijos']],
      'sActivePage' => 'costos_fijos',
      'sTitulo' => 'COSTOS FIJOS',
      'sDescripcion' => 'Administración de costos fijos.',
      'oFijos' => $oFijos,
      'aCuentas' => $aCuentas
    ]);
  }

  public function get_costos_fijos()
  {
    $oFijos = CostosFijos::select('costos_fijos.*','periodos.periodo as nombre_periodo','periodos.dias')
    ->where('costos_fijos.id_empresa', \Auth::User()->id_empresa)
    ->where('costos_fijos.estado',1)
    ->leftJoin('periodos','periodos.id','costos_fijos.periodo')
    ->orderBy('costos_fijos.id','DESC')
    ->get();
    return $oFijos;
  }

  public function create()
  {
    $periodos = $this->obtener_periodos();
    return view('catalogos.costosFijos.guardar',[
      'aBreadCrumb' => [['link'=> '/costos_fijos', 'label'=> 'Listado de costos fijos'],['link'=> 'active', 'label'=> 'Nuevo costo fijo']],
      'sActivePage' => 'costos_fijos',
      'sTitulo' => 'NUEVO COSTO FIJO',
      'sDescripcion' => 'Ingresa los datos del costo fijo.',
      'sTipoVista' => 'crear',
      'periodos' => $periodos
    ]);
  }

  public function store(Request $request)
  {
    $input = $request->all();
    CostosFijos::creaRegistro($input);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha creado exitosamente el nuevo costo fijo.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/costos_fijos');
  }

  public function edit( $iId )
  {
    try {
      $oCostoFijo = CostosFijos::select('costos_fijos.*','periodos.periodo as nombre_periodo','periodos.dias')
      ->where('costos_fijos.id_empresa', \Auth::User()->id_empresa)
      ->where('costos_fijos.id', $iId )
      ->leftJoin('periodos','periodos.id','costos_fijos.periodo')
      ->first();
      $aCuentas = app('App\Http\Controllers\CuentasController')->get_cuentas_disponibles()->pluck('nombre','id');

      $periodos = $this->obtener_periodos();
      $aPagos = $oCostoFijo->pagos();
      $oPagos = $aPagos['pagos'];

      return view('catalogos.costosFijos.guardar',[
        'aBreadCrumb' => [['link'=> '/costos_fijos', 'label'=> 'Listado de costos fijos'],['link'=> 'active', 'label'=> 'Editar costo fijo']],
        'sActivePage' => 'costos_fijos',
        'sTitulo' => mb_strtoupper( $oCostoFijo->concepto ),
        'sDescripcion' => 'Actualiza los datos del costo fijo.',
        'sTipoVista' => 'editar',
        'oCostoFijo' => $oCostoFijo,
        'periodos' => $periodos,
        'oPagos' => $oPagos,
        'aPagos' => $aPagos,
        'aCuentas' => $aCuentas
      ]);
    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() );
    }
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    CostosFijos::actualizaRegistro($aInput);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha actualizado el costo fijo exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/costos_fijos');
  }

  public function obtener_periodos()
  {
    $periodos = [];
    $periodos_ = Periodos::all();
    $i=1;
    foreach ($periodos_ as $periodo){
      $periodos[$i] = ($periodo->dias == 1) ? $periodo->periodoDias.' día' : $periodo->periodoDias.' días';
      $i++;
    }
    return $periodos;
  }

  public function destroy(Request $request)
  {
    $aInput = $request->all();
    $oCostoFijo = CostosFijos::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado el costo fijo exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/costos_fijos');
  }
}
