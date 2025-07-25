<?php

namespace App\Http\Controllers;
use App\Models\Proyectos;
use App\Models\FasesProyecto;
use App\Models\ManoObra;
use App\Models\RolesFase;
use App\Models\VariablesProyecto;
use App\Models\ParticipantesProyecto;
use App\Models\CostosIndirectos;
use App\Models\CostosIndirectosPro;
use App\Models\Cfdis;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Session;
use PDF;



class ProyectosController extends Controller
{
  public function index()
  {
    $oProyectos = $this->get_proyectos();
    $aCuentas = app('App\Http\Controllers\CuentasController')->get_cuentas_disponibles()->pluck('nombre','id');
    $iPermitidos = $this->get_numero_proyectos_permitidos();
    return view('costeo.proyectos.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Listado de proyectos']],
      'sActivePage' => 'proyectos',
      'sTitulo' => 'PROYECTOS',
      'sDescripcion' => 'Administración de proyectos.',
      'oProyectos' => $oProyectos,
      'aCuentas' => $aCuentas,
      'iPermitidos' => $iPermitidos
    ]);
  }

  public function edit($id)
  {
    try {
      $oProyecto = Proyectos::where('id',$id)->where('id_empresa', \Auth::User()->id_empresa)->first();
      $oVariables = VariablesProyecto::where('id_proyecto',$id)->where('estado',1)->get();
      $aManoDeObra = $this->get_mano_obra_disponible()->pluck('nombre','id');
      $oFases = $this->get_fases_proyecto($id);
      $oCostosIndirectos = $this->get_costos_indirectos($id);
      //dd($oCostosIndirectos->toArray());

      $aPresupuesto = $oProyecto->presupuesto();
      $aCobros = $oProyecto->cobros();
      $oCobros = $aCobros['cobros'];
      $fTotalRestante = $aPresupuesto['precio_venta']-$aCobros['total_cobrado'];
      $boolPagado = ($fTotalRestante <= 0 ? true : false);
      $aCuentas = app('App\Http\Controllers\CuentasController')->get_cuentas_disponibles()->pluck('nombre','id');
      $oCfdis = Cfdis::where('id_proyecto',$id)->get();

      return view('costeo.proyectos.guardar',[
        'aBreadCrumb' => [['link'=> '/proyectos', 'label'=> 'Listado de proyectos'],['link'=> 'active', 'label'=> 'Editar proyecto']],
        'sActivePage' => 'proyectos',
        'sTitulo' => strtoupper( $oProyecto->nombre ),
        'sDescripcion' => 'Actualiza los datos del proyecto.',
        'sTipoVista' => 'editar',
        'oProyecto' => $oProyecto,
        'oVariables' => $oVariables,
        'aManoDeObra' => $aManoDeObra,
        'oFases' => $oFases,
        'oCostosIndirectos' => $oCostosIndirectos,
        'aPresupuesto' => $aPresupuesto,
        'aCobros' => $aCobros,
        'oCobros' => $oCobros,
        'fTotalRestante' => $fTotalRestante,
        'boolPagado' => $boolPagado,
        'aCuentas' => $aCuentas,
        'iIdProyecto' => $oProyecto->id,
        'oCfdis' => $oCfdis
      ]);

    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() );
    }
  }

  public function store(Request $request)
  {
    $input = $request->all();
    $iPermitidos = $this->get_numero_proyectos_permitidos();
    if ( $iPermitidos <= 0 ) {
      Session::flash('tituloMsg','Alerta');
      Session::flash('mensaje',"Se ha llegado al límite permitido de proyectos, escala tu licencia o contacta con soporte.");
      Session::flash('tipoMsg','warning');
      return back()->withInput();
    }

    Proyectos::creaRegistro($input);

    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha creado exitosamente el nuevo proyecto.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/proyectos');
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    //dd($aInput);
    Proyectos::actualizaRegistro($aInput);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha actualizado el proyecto exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/proyectos');
  }

  public function get_numero_proyectos_permitidos()
  {
    $aPermisos = \Auth::User()->permisos();
    $iMaxProyectos = $aPermisos['numero_proyectos'];
    $iNumeroProyectos = Proyectos::where('id_empresa', \Auth::User()->id_empresa)->where('estado',1)->count();
    return $iMaxProyectos - $iNumeroProyectos;
  }

  public function get_proyectos()
  {
    return Proyectos::where('id_empresa', \Auth::User()->id_empresa)->where('estado',1)->orderBy('id','DESC')->get();
  }

  public function get_fases_proyecto($id)
  {
    $oFases = FasesProyecto::where('id_proyecto',$id)->where('estado',1)->orderBy('id','ASC')->get();
    return $oFases;
  }

  public function get_fases(Request $request)
  {
    try {
      $aInput = $request->all();
      $aFases = $this->get_fases_proyecto($aInput['id_proyecto'])->toArray();
      return response()->json([
        'estatus' => 1,
        'mensaje' => 'Consulta exitosa.',
        'resultado' => $aFases
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'estatus' => 0,
        'mensaje' => 'Error al consultar fases del proyecto.',
        'resultado' => $e->getMessage()
      ]);
    }
  }

  public function get_mano_obra_disponible()
  {
    $aManoDeObra = ManoObra::where('id_empresa', \Auth::User()->id_empresa )
    ->where('estado',1)
    ->orderBy('nombre','ASC')
    ->get();
    return $aManoDeObra;
  }



  public function edit_factura(Request $request)
  {
    try {
      $aInput = $request->all();
      $oProyecto = Proyectos::where('id',$aInput['id_proyecto'])->where('id_empresa', \Auth::User()->id_empresa)->first();
      $aPresupuesto = $oProyecto->presupuesto();

      $aIva = [];
      if ($oProyecto->iva != null) {
        $aIva = [
          "Name" => "IVA",
          "Rate" => ( $oProyecto->iva > 0 ? $oProyecto->iva / 100 : 0 ),
          "Total" => $aPresupuesto['iva'],//"6.4",
          "Base" => $aPresupuesto['subtotal'],
          "IsRetention" => "false"
        ];
      }

      $aItems = [[
        "Quantity" => "1",
        "ProductCode" => $oProyecto->product_code,//"84111506",
        "UnitCode" => $oProyecto->unit_code,//"E48",
        //"Unit" => "Unidad de servicio",
        "Description" => $oProyecto->nombre,
        //"IdentificationNumber" => "23",
        "UnitPrice" => $aPresupuesto['subtotal'],//"0.50",
        "Subtotal" => $aPresupuesto['subtotal'],//"50.00",
        //"Discount" => "10",
        //"DiscountVal" => "10",
        /*"Taxes" => [[
          "Name" => "IVA",
          "Rate" => "0.16",
          "Total" => "6.4",
          "Base" => "40",
          "IsRetention" => "false"
        ]],*/
        "Taxes" => [$aIva],
        "Iva" => $aPresupuesto['iva'],
        "Total" => $aPresupuesto['precio_venta']//"46.40"
      ]];

      $aFormasPago = app('App\Http\Controllers\FacturamaController')->get_formas_pago();
      //dd($aItems);
      $aFormasPago = array_column($aFormasPago, 'Name', 'Value');

      $Html = view('facturama.facturas.edit')
      ->with('aFormasPago', $aFormasPago)
      ->with('aItems', $aItems)
      ->with('oProyecto',$oProyecto)
      ->render();
      return response()->json([
        'estatus' => 1,
        'mensaje' => 'Consulta exitosa.',
        'resultado' => $Html
      ]);
    } catch (\Exception $e) {
      return response()->json([
        'estatus' => 0,
        'mensaje' => 'Error al crear factura.',
        'resultado' => $e->getMessage()
      ]);
    }
  }

  public function duplicar($id)
  {
    //Proyecto original
    $oProyecto = Proyectos::where('id',$id)->where('id_empresa', \Auth::User()->id_empresa)->first();
    $oFases = $this->get_fases_proyecto($id);
    $oCostosIndirectos = $this->get_costos_indirectos($id);

    //Proyecto copia
    $oProyectoCopia = Proyectos::creaRegistro($oProyecto->toArray());
    foreach ($oFases as $oFase) {
      //Fases copia
      $aFase = $oFase->toArray();
      $aFase['id_proyecto'] = $oProyectoCopia->id;
      $oFaseCopia = FasesProyecto::creaRegistro($aFase);
      //Roles copia
      $oRoles = $oFase->roles();
      foreach ($oRoles as $oRol) {
        $aRol = $oRol->toArray();
        $aRol['id_fase'] = $oFaseCopia->id;
        $oRolCopia = RolesFase::creaRegistro($aRol);
        //Participantes copia
        $oParticipantes = $oRol->participantes();
        foreach ($oParticipantes as $oParticipante) {
          $aParticipante = $oParticipante->toArray();
          $aParticipante['id_rol'] = $oRolCopia->id;
          $aParticipante['id_fase'] = $oFaseCopia->id;
          $aParticipante['id_proyecto'] = $oProyectoCopia->id;
          $oParticipanteCopia = ParticipantesProyecto::creaRegistro($aParticipante);
        }
      }
    }
    //Costos indirectos copia
    foreach ($oCostosIndirectos as $oCostoIndirecto) {
      $aCostoIndirecto = $oCostoIndirecto->toArray();
      $aCostoIndirecto['id_costo'] = $aCostoIndirecto['id_costo_indirecto'];
      $aCostoIndirecto['id_proyecto'] = $oProyectoCopia->id;
      $oCostoIndirectoCopia = CostosIndirectosPro::creaRegistro($aCostoIndirecto);
    }

    Session::flash('tituloMsg','Proyecto duplicado');
    Session::flash('mensaje',"Se ha duplicado el proyecto.");
    Session::flash('tipoMsg','success');
    return back();
  }

  public function imprimir($id)
  {
      $oProyecto = Proyectos::select('proyectos.*','mano_obra_d.nombre as nombre_lider')
      ->where('proyectos.id',$id)
      ->where('proyectos.id_empresa', \Auth::User()->id_empresa)
      ->leftJoin('mano_obra_d','mano_obra_d.id','proyectos.id_lider')
      ->first();
      $oVariables = VariablesProyecto::where('id_proyecto',$id)->where('estado',1)->get();
      $aManoDeObra = $this->get_mano_obra_disponible()->pluck('nombre','id');
      $oFases = $this->get_fases_proyecto($id);
      $oCostosIndirectos = $this->get_costos_indirectos($id);
      /*$Html = view('costeo.proyectos.print')
      ->with('oProyecto', $oProyecto)
      ->with('oVariables',$oVariables)
      ->with('aManoDeObra',$aManoDeObra)
      ->with('oFases', $oFases)
      ->render();

      $pdf = App::make('dompdf.wrapper');
      $pdf = PDF::loadHTML($Html)->setPaper('a4', 'landscape');
      $pdf->loadHTML($Html);
      return $pdf->stream();

      $pdf = PDF::loadView('costeo.proyectos.print', []);
      return $pdf->download('invoice.pdf');

      $pdf = PDF::loadView('costeo.proyectos.print', [
        'oProyecto' => $oProyecto,
        'oVariables' => $oVariables,
        'aManoDeObra' => $aManoDeObra,
        'oFases' => $oFases
      ]);
      return $pdf->download('invoice.pdf');*/

      return view('costeo.proyectos.print',[
        'oProyecto' => $oProyecto,
        'oVariables' => $oVariables,
        'aManoDeObra' => $aManoDeObra,
        'oFases' => $oFases,
        'sTipoVista' => 'print',
        'sTipoCosto' => 'proyecto',
        'oCostosIndirectos' => $oCostosIndirectos
      ]);

      //return view('error')->with('sError', $e->getMessage() );
  }

  public function create()
  {
    $aManoDeObra = $this->get_mano_obra_disponible()->pluck('nombre','id');

    return view('costeo.proyectos.guardar',[
      'aBreadCrumb' => [['link'=> '/proyectos', 'label'=> 'Listado de proyectos'],['link'=> 'active', 'label'=> 'Nuevo proyecto']],
      'sActivePage' => 'proyectos',
      'sTitulo' => 'NUEVO PROYECTO',
      'sDescripcion' => 'Ingrese los datos del nuevo proyecto.',
      'sTipoVista' => 'crear',
      'aManoDeObra' => $aManoDeObra
    ]);
  }



  public function get_proyectos_facturacion()
  {
    $oProyectos = Proyectos::where('id_empresa', \Auth::User()->id_empresa)
    ->where('estado',1)
    ->orderBy('id','DESC')
    ->get();

  }

  public function destroy(Request $request)
  {
    $aInput = $request->all();
    $oProyecto = Proyectos::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado el proyecto exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/proyectos');
  }

  public function edit_costo_variable($id_proyecto, $id)
  {
    if ($id != null && $id != 0) {
      $oVariable = VariablesProyecto::find($id);
      $sTipoVista = 'editar';
    }else {
      $oVariable = [];
      $sTipoVista = 'crear';
    }
    $Html = view('costeo.proyectos.guardar_costo_variable')
    ->with('oVariable', $oVariable)
    ->with('sTipoVista',$sTipoVista)
    ->with('iIdProyecto',$id_proyecto)
    ->render();

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa.',
      'resultado' => $Html
    ]);
  }

  /*public function store_costo_variable(Request $request)
  {
    $input = $request->all();
    VariablesProyecto::creaRegistro($input);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha guardado el costo variable exitosamente.");
    Session::flash('tipoMsg','success');
    //return $this->edit($input['id_proyecto']);
    return Redirect::to('/proyectos/editar/' . $input['id_proyecto'] . '#costos_variables');
  }

  public function update_costo_variable(Request $request)
  {
    $input = $request->all();
    VariablesProyecto::actualizaRegistro($input);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha actualizado el costo variable exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/proyectos/editar/' . $input['id_proyecto'] . '#costos_variables');
  }

  public function destroy_costo_variable(Request $request)
  {
    $aInput = $request->all();
    //dd( $aInput );
    $oVariable = VariablesProyecto::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado el costo variable exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/proyectos/editar/' . $oVariable->id_proyecto . '#costos_variables');
  }*/

  public function store_fase(Request $request)
  {
    $aInput = $request->all();
    FasesProyecto::creaRegistro($aInput);
    $oFases = $this->get_fases_proyecto($aInput['id_proyecto']);
    $sHtml = view('costeo.proyectos.table_fases')->with('oFases', $oFases)->with('sTipoVista','editar')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Guardado exitoso.',
      'resultado' => $sHtml
    ]);
  }

  public function update_fase(Request $request)
  {
    $aInput = $request->all();
    FasesProyecto::actualizaRegistro($aInput);
    $oFases = $this->get_fases_proyecto($aInput['id_proyecto']);
    $sHtml = view('costeo.proyectos.table_fases')->with('oFases', $oFases)->with('sTipoVista','editar')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Guardado exitoso.',
      'resultado' => $sHtml
    ]);
  }

  public function destroy_fase(Request $request)
  {
    $aInput = $request->all();
    $oFase = FasesProyecto::eliminarRegistro( $aInput['id']);
    $oFases = $this->get_fases_proyecto($aInput['id_proyecto']);
    $sHtml = view('costeo.proyectos.table_fases')->with('oFases', $oFases)->with('sTipoVista','editar')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Fase eliminada.',
      'resultado' => $sHtml
    ]);
  }

  public function store_rol(Request $request)
  {
    $aInput = $request->all();
    RolesFase::creaRegistro($aInput);
    $oFases = $this->get_fases_proyecto($aInput['id_proyecto']);
    $sHtml = view('costeo.proyectos.table_fases')->with('oFases', $oFases)->with('sTipoVista','editar')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Guardado exitoso.',
      'resultado' => $sHtml
    ]);
  }

  public function update_rol(Request $request)
  {
    $aInput = $request->all();
    RolesFase::actualizaRegistro($aInput);
    $oFases = $this->get_fases_proyecto($aInput['id_proyecto']);
    $sHtml = view('costeo.proyectos.table_fases')->with('oFases', $oFases)->with('sTipoVista','editar')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Guardado exitoso.',
      'resultado' => $sHtml
    ]);
  }

  public function destroy_rol(Request $request)
  {
    $aInput = $request->all();
    $oRol = RolesFase::eliminarRegistro( $aInput['id']);
    $oFases = $this->get_fases_proyecto($aInput['id_proyecto']);
    $sHtml = view('costeo.proyectos.table_fases')->with('oFases', $oFases)->with('sTipoVista','editar')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Rol eliminado.',
      'resultado' => $sHtml
    ]);
  }

  public function store_participante(Request $request)
  {
    $aInput = $request->all();
    ParticipantesProyecto::creaRegistro($aInput);
    $oFases = $this->get_fases_proyecto($aInput['id_proyecto']);
    $sHtml = view('costeo.proyectos.table_fases')->with('oFases', $oFases)->with('sTipoVista','editar')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Guardado exitoso.',
      'resultado' => $sHtml
    ]);
  }

  public function update_participante(Request $request)
  {
    $aInput = $request->all();
    ParticipantesProyecto::actualizaRegistro($aInput);
    $oFases = $this->get_fases_proyecto($aInput['id_proyecto']);
    $sHtml = view('costeo.proyectos.table_fases')->with('oFases', $oFases)->with('sTipoVista','editar')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Guardado exitoso.',
      'resultado' => $sHtml
    ]);
  }

  public function destroy_participante(Request $request)
  {
    $aInput = $request->all();
    $oParticipante = ParticipantesProyecto::eliminarRegistro( $aInput['id']);
    $oFases = $this->get_fases_proyecto($aInput['id_proyecto']);
    $sHtml = view('costeo.proyectos.table_fases')->with('oFases', $oFases)->with('sTipoVista','editar')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Responsable eliminado.',
      'resultado' => $sHtml
    ]);
  }

  public function get_costos_indirectos($iId)
  {
    $oCostosIndirectos = CostosIndirectos::select('costos_indirectos.*','medidas.medida','fases_proyecto.nombre as fase_nombre')
    ->where('costos_indirectos.estado',1)
    ->where('costos_indirectos_pro.id_proyecto',$iId)
    ->leftJoin('costos_indirectos_pro','costos_indirectos_pro.id_costo_indirecto','costos_indirectos.id')
    ->leftJoin('medidas','medidas.id','costos_indirectos.id_medida')
    ->leftJoin('fases_proyecto','fases_proyecto.id','costos_indirectos_pro.id_fase')
    ->get();
    return $oCostosIndirectos;
  }

  public function edit_costo_indirecto($id)
  {
    $aCostos = [];
    $aCosto = [];
    if ($id > 0 ) {//xxx
      $aCosto = CostosIndirectos::select('costos_indirectos_pro.id','costos_indirectos.id as id_costo','costos_indirectos.unidades','costos_indirectos.concepto','costos_indirectos.costo','costos_indirectos.unidades as unidades_totales','costos_indirectos_pro.id_fase','medidas.medida')
      ->where('costos_indirectos.id',$id)
      ->leftJoin('costos_indirectos_pro','costos_indirectos_pro.id_costo_indirecto','costos_indirectos.id')
      ->leftJoin('medidas','medidas.id','costos_indirectos.id_medida')
      ->first()
      ->toArray();
      $sTipoVista = 'editar';
    }else {
      $aCostos = CostosIndirectos::select('costos_indirectos.*','medidas.medida','costos_indirectos.unidades as unidades_totales')
      ->where('costos_indirectos.id_empresa', \Auth::User()->id_empresa)
      ->where('costos_indirectos_pro.id',null)//todos los que no sean asignados a un proyecto
      ->leftJoin('medidas','medidas.id','costos_indirectos.id_medida')
      ->leftJoin('costos_indirectos_pro','costos_indirectos_pro.id_costo_indirecto','costos_indirectos.id')
      ->get()
      ->toArray();
      $sTipoVista = 'crear';
    }
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa.',
      'resultado' => [
        'aCostos' => $aCostos,
        'aCosto' => $aCosto,
        'sTipoVista' => $sTipoVista
      ]
    ]);
  }

  /*
  public function edit_costo_indirecto($id)
  {
    $aCostos = [];
    $aCosto = [];

    if ($id > 0 ) {
      $aCosto = CostosIndirectosPro::select('costos_indirectos_pro.id','costos_indirectos_pro.id_costo_indirecto as id_costo','costos_indirectos_pro.unidades','costos_indirectos.concepto','costos_indirectos.costo','costos_indirectos.unidades as unidades_totales','medidas.medida')
      ->where('costos_indirectos_pro.id',$id)
      ->leftJoin('costos_indirectos','costos_indirectos.id','costos_indirectos_pro.id_costo_indirecto')
      ->leftJoin('medidas','medidas.id','costos_indirectos.id_medida')
      ->first()->toArray();
      $sTipoVista = 'editar';
    }else {
      $aCostos = CostosIndirectos::select('costos_indirectos.*','medidas.medida','costos_indirectos.unidades as unidades_totales')
      ->where('costos_indirectos.id_empresa', \Auth::User()->id_empresa)
      //->where('costos_indirectos_pro.id',null)//todos los que no sean asignados a un proyecto
      ->leftJoin('medidas','medidas.id','costos_indirectos.id_medida')
      //->leftJoin('costos_indirectos_pro','costos_indirectos_pro.id_costo_indirecto','costos_indirectos.id')
      ->get()
      ->toArray();
      $sTipoVista = 'crear';
    }
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa.',
      'resultado' => [
        'aCostos' => $aCostos,
        'aCosto' => $aCosto,
        'sTipoVista' => $sTipoVista
      ]
    ]);
  }
  */

  public function store_costo_indirecto(Request $request)
  {
    $aInput = $request->all();
    $oCostoIndirecto = CostosIndirectosPro::creaRegistro([
      'id_costo' => $aInput['id_costo'],
      'id_proyecto' => $aInput['id_proyecto'],
      'id_fase' => @$aInput['id_fase'],
      'unidades' => 1
    ]);

    $oCostosIndirectos = $this->get_costos_indirectos( $aInput['id_proyecto'] );
    $html = view('costeo.proyectos.table_costos_indirectos')
    ->with('oCostosIndirectos', $oCostosIndirectos)
    ->with('sTipoVista','editar')
    ->with('sTipoCosto','proyecto')
    ->with('iIdProyecto',$aInput['id_proyecto'])
    ->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Se ha guardado el costo indirecto exitosamente.',
      'resultado' => $html
    ]);
  }

  public function update_costo_indirecto(Request $request)
  {
    $aInput = $request->all();
    //dd($aInput);
    $oCostoIndirecto = CostosIndirectosPro::actualizaRegistro([
      'id_costo_producto' => $aInput['id_costo_proyecto'],
      'id_proyecto' => $aInput['id_proyecto'],
      'id_fase' => @$aInput['id_fase'],
    ]);

    $oCostosIndirectos = $this->get_costos_indirectos( $aInput['id_proyecto'] );
    $html = view('costeo.proyectos.table_costos_indirectos')
    ->with('oCostosIndirectos', $oCostosIndirectos)
    ->with('sTipoVista','editar')
    ->with('sTipoCosto','proyecto')
    ->with('iIdProyecto',$aInput['id_proyecto'])
    ->render();

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Se ha guardado el costo indirecto exitosamente.',
      'resultado' => $html
    ]);
  }

  public function marcar_comprado_costo_indirecto(Request $request)//solo en proyectos se puede comprar
  {
    $aInput = $request->all();
    /*$oCostoIndirecto = CostosIndirectosPro::find($aInput['id_costo_proyecto']);
    $oCostoIndirecto->comprado = $aInput['comprado']*1;
    $oCostoIndirecto->save();*/
    //$oCostoIndirectoProyecto = CostosIndirectosPro::find($aInput['id_costo_proyecto']);
    $oCostoIndirecto = CostosIndirectos::find($aInput['id_costo_indirecto']);
    $oCostoIndirecto->comprado = $aInput['comprado']*1;
    $oCostoIndirecto->save();
    $oCostosIndirectos = $this->get_costos_indirectos( $aInput['id_proyecto'] );
    $html = view('costeo.proyectos.table_costos_indirectos')
    ->with('oCostosIndirectos', $oCostosIndirectos)
    ->with('sTipoVista','editar')
    ->with('sTipoCosto','proyecto')
    ->with('iIdProyecto',$aInput['id_proyecto'])
    ->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Se ha marcado el costo indirecto exitosamente.',
      'resultado' => $html
    ]);
  }

  public function destroy_costo_indirecto(Request $request)
  {
    $aInput = $request->all();
    $oCostoIndirecto = CostosIndirectosPro::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado el costo indirecto exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/proyectos/editar/' . $oCostoIndirecto->id_proyecto . '#costos_indirectos');
  }
}
