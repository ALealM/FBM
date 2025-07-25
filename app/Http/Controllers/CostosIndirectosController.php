<?php

namespace App\Http\Controllers;

use App\Models\Medidas;
use App\Models\CostosIndirectos;
use App\Models\CostosIndirectosPro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;

class CostosIndirectosController extends Controller
{
  public $aTipos = ['1' => 'Activo' , '2' => 'Consumible'];
  public $aEstadoIncial = [0 => 'Cotizado', 1 => 'Facturado'];

  public function index()
  {
    $oEmpresa = \Auth::User()->empresa();
    $oCostosIndirectos = CostosIndirectos::where('id_empresa', \Auth::User()->id_empresa)->where('estado',1)->orderBy('id','DESC')->get();

    return view('catalogos.' . ( $oEmpresa->tipo_sistema == 2 ? 'costosIndirectosProyectos' : 'costosIndirectos') . '.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Listado de costos indirectos']],
      'sActivePage' => 'costos_indirectos',
      'sTitulo' => 'COSTOS INDIRECTOS',
      'sDescripcion' => 'Administración de los costos indirectos.',
      'oCostosIndirectos' => $oCostosIndirectos
    ]);
  }

  public function create()
  {
    $oEmpresa = \Auth::User()->empresa();
    $oMedidas = Medidas::pluck('medida','id');
    if ( $oEmpresa->tipo_sistema == 2 ) { //proyectos
      $aProyectos = app('App\Http\Controllers\ProyectosController')->get_proyectos()->pluck('nombre','id');
      return view('catalogos.costosIndirectosProyectos.guardar',[
        'aBreadCrumb' => [['link'=> '/costos_indirectos', 'label'=> 'Listado de costos indirectos'],['link'=> 'active', 'label'=> 'Nuevo costo indirecto']],
        'sActivePage' => 'costos_indirectos',
        'sTitulo' => 'NUEVO COSTO INDIRECTO',
        'sDescripcion' => 'Datos del costo indirecto.',
        'sTipoVista' => 'crear',
        'oMedidas' => $oMedidas,
        'aTipos' => $this->aTipos,
        'aEstadoIncial' => $this->aEstadoIncial,
        'aProyectos' => $aProyectos
      ]);
    }else { //productos
      return view('catalogos.costosIndirectos.guardar',[
        'aBreadCrumb' => [['link'=> '/costos_indirectos', 'label'=> 'Listado de costos indirectos'],['link'=> 'active', 'label'=> 'Nuevo costo indirecto']],
        'sActivePage' => 'costos_indirectos',
        'sTitulo' => 'NUEVO COSTO INDIRECTO',
        'sDescripcion' => 'Datos del costo indirecto.',
        'sTipoVista' => 'crear',
        'oMedidas' => $oMedidas,
      ]);
    }
  }

  public function store(Request $request)
  {
    $aInput = $request->all();
    //dd($aInput);
    $oCostoIndirecto = CostosIndirectos::creaRegistro($aInput);
    if ( $aInput['id_proyecto'] > 0 ) {
      $oCostoIndirectoProyecto = CostosIndirectosPro::creaRegistro([
        'id_costo' => $oCostoIndirecto->id,
        'id_proyecto' => $aInput['id_proyecto'],
        'id_fase' => @$aInput['id_fase'],
        'unidades' => 1
      ]);
    }
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha creado exitosamente el costo indirecto.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/costos_indirectos');
  }

  public function edit( $iId )
  {
    try {
      $oEmpresa = \Auth::User()->empresa();
      $oCostoIndirecto = CostosIndirectos::where('id_empresa', \Auth::User()->id_empresa)->where('id', $iId )->first();

      if ( $oEmpresa->tipo_sistema == 2 ) { //proyectos
        $oCostoIndirectoProyecto = CostosIndirectosPro::where('id_costo_indirecto', $oCostoIndirecto->id )->first();
        $oPagos = $oCostoIndirecto->pagos()['pagos'];
        $aProyectos = app('App\Http\Controllers\ProyectosController')->get_proyectos()->pluck('nombre','id');
        $oMedidas = Medidas::pluck('medida','id');
        return view('catalogos.costosIndirectosProyectos.guardar',[
          'aBreadCrumb' => [['link'=> '/costos_indirectos', 'label'=> 'Listado de costos indirectos'],['link'=> 'active', 'label'=> 'Editar el costo indirecto']],
          'sActivePage' => 'costos_indirectos',
          'sTitulo' => mb_strtoupper( $oCostoIndirecto->concepto ),
          'sDescripcion' => 'Actualiza los datos del costo indirecto.',
          'sTipoVista' => 'editar',
          'oCostoIndirecto' => $oCostoIndirecto,
          'oCostoIndirectoProyecto' => $oCostoIndirectoProyecto,
          'oPagos' => $oPagos,
          'oMedidas' => $oMedidas,
          'aTipos' => $this->aTipos,
          'aEstadoIncial' => $this->aEstadoIncial,
          'aProyectos' => $aProyectos

        ]);
      }else {//productos
        $oMedidas = Medidas::pluck('medida','id');
        return view('catalogos.' .  ( $oEmpresa->tipo_sistema == 2 ? 'costosIndirectosProyectos' : 'costosIndirectos') . '.guardar',[
          'aBreadCrumb' => [['link'=> '/costos_indirectos', 'label'=> 'Listado de costos indirectos'],['link'=> 'active', 'label'=> 'Editar el costo indirecto']],
          'sActivePage' => 'costos_indirectos',
          'sTitulo' => mb_strtoupper( $oCostoIndirecto->concepto ),
          'sDescripcion' => 'Actualiza los datos del costo indirecto.',
          'sTipoVista' => 'editar',
          'oCostoIndirecto' => $oCostoIndirecto,
          'oMedidas' => $oMedidas
        ]);
      }
    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() );
    }
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    $oCostoIndirecto = CostosIndirectos::actualizaRegistro($aInput);
    if ( $aInput['id_costo_indirecto_proyecto'] > 0 ) {
      $oCostoIndirectoProyecto = CostosIndirectosPro::actualizaRegistro([
        'id_costo_producto' => $aInput['id_costo_indirecto_proyecto'],
        'id_proyecto' => @$aInput['id_proyecto'],
        'id_fase' => @$aInput['id_fase'],
        'unidades' => 1
      ]);
    }elseif ( $aInput['id_proyecto'] > 0 ) {
      $oCostoIndirectoProyecto = CostosIndirectosPro::creaRegistro([
        'id_costo' => $oCostoIndirecto->id,
        'id_proyecto' => $aInput['id_proyecto'],
        'id_fase' => @$aInput['id_fase'],
        'unidades' => 1
      ]);
    }
    Session::flash('tituloMsg','Actualización');
    Session::flash('mensaje',"Se ha actualizado el costo indirecto exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/costos_indirectos');
  }

  public function destroy(Request $request)
  {
    $aInput = $request->all();
    $oCostoIndirecto = CostosIndirectos::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado el costo indirecto exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/costos_indirectos');
  }
}
