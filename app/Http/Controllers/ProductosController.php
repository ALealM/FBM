<?php

namespace App\Http\Controllers;
use App\Models\Productos;
use App\Models\MateriaPrima;
use App\Models\CosteoProducto;
use App\Models\CostosIndirectos;
use App\Models\CostosIndirectosPro;
use App\Models\Medidas;
use Illuminate\Http\Request;
use Redirect;
use Image;
use Illuminate\Support\Facades\Session;

class ProductosController extends Controller
{
  public function index()
  {
    $oProductos = Productos::where('id_empresa', \Auth::User()->id_empresa)->where('estado',1)->orderBy('id','DESC')->get();
    $iPermitidos = $this->get_numero_productos_permitidos();
    //dd($iPermitidos);
    return view('costeo.productos.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Listado de productos']],
      'sActivePage' => 'productos',
      'sTitulo' => 'PRODUCTOS',
      'sDescripcion' => 'Administración de productos.',
      'oProductos' => $oProductos,
      'iPermitidos' => $iPermitidos
    ]);
  }

  public function get_numero_productos_permitidos()
  {
    $aPermisos = \Auth::User()->permisos();
    $iMaxProductos = $aPermisos['numero_productos'];
    $iNumeroProductos = Productos::where('id_empresa', \Auth::User()->id_empresa)->where('estado',1)->count();
    return $iMaxProductos - $iNumeroProductos;
  }

  public function buscador(Request $request)
  {
    $aInput = $request->all();
    $sBusqueda = $aInput['busqueda'];
    $oProductos = Productos::select('productos.*','medidas.medida as nombre_medida')
    ->where('productos.id_empresa', \Auth::User()->id_empresa)
    ->where('estado',1)
    ->where(function($query) use ($sBusqueda){
      $query->where('productos.producto','like','%'.$sBusqueda.'%');
      $query->orWhere('productos.descripcion','like','%'.$sBusqueda.'%');
    })->leftJoin('medidas','medidas.id','productos.id_medida')
    ->take(5)
    ->get();

    $html = view('ventas.table_resultados')->with('oProductos', $oProductos)->render();

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa.',
      'resultado' => $html
    ]);
  }

  public function edit($iId)
  {
    try {
      $oProducto = Productos::where('id_empresa', \Auth::User()->id_empresa)->where('id',$iId)->first();
      $oCostosIndirectos = $this->get_costos_indirectos($iId);
      $oCostosPorProducto = $this->get_costos_por_producto($iId);
      $oMedidas = Medidas::pluck('medida','id');
      return view('costeo.productos.guardar',[
        'aBreadCrumb' => [['link'=> '/productos', 'label'=> 'Listado de productos'],['link'=> 'active', 'label'=> 'Editar producto']],
        'sActivePage' => 'productos',
        'sTitulo' => mb_strtoupper( $oProducto->producto ),
        'sDescripcion' => 'Actualiza los datos del producto.',
        'sTipoVista' => 'editar',
        'oProducto' => $oProducto,
        'oCostosIndirectos' => $oCostosIndirectos,
        'oCostosPorProducto' => $oCostosPorProducto,
        'oMedidas' => $oMedidas
      ]);
    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() );
    }
  }

  public function get_costos_por_producto($iId){
    $oCostosPorProducto = CosteoProducto::select('costeo_producto.*','materia_prima.concepto', 'materia_prima.unidades as materia_unidades', 'materia_prima.costo', 'medidas.medida')
    ->where('id_producto',$iId)
    ->where('materia_prima.estado',1)
    ->leftJoin('materia_prima','materia_prima.id','costeo_producto.id_materia_prima')
    ->leftJoin('medidas','medidas.id','materia_prima.id_medida')
    ->get();
    return $oCostosPorProducto;
  }

  public function get_costos_indirectos($iId){
    $oCostosIndirectos = CostosIndirectosPro::select('costos_indirectos_pro.*','costos_indirectos.concepto', 'costos_indirectos.unidades as indirecto_unidades','costos_indirectos.costo', 'medidas.medida')
    ->where('id_producto',$iId)
    ->where('costos_indirectos.estado',1)
    ->leftJoin('costos_indirectos','costos_indirectos.id','costos_indirectos_pro.id_costo_indirecto')
    ->leftJoin('medidas','medidas.id','costos_indirectos.id_medida')
    ->get();
    return $oCostosIndirectos;
  }

  public function create()
  {
    $oMedidas = Medidas::pluck('medida','id');
    return view('costeo.productos.guardar',[
      'aBreadCrumb' => [['link'=> '/productos', 'label'=> 'Listado de productos'],['link'=> 'active', 'label'=> 'Nuevo producto']],
      'sActivePage' => 'productos',
      'sTitulo' => 'NUEVO PRODUCTO',
      'sDescripcion' => 'Ingrese los datos del nuevo producto.',
      'sTipoVista' => 'crear',
      'oMedidas' => $oMedidas
    ]);
  }

  public function store(Request $request)
  {
    $aInput = $request->all();
    $iPermitidos = $this->get_numero_productos_permitidos();
    if ( $iPermitidos <= 0 ) {
      Session::flash('tituloMsg','Alerta');
      Session::flash('mensaje',"Se ha llegado al límite permitido de productos, escala tu licencia o contacta con soporte.");
      Session::flash('tipoMsg','warning');
      return back()->withInput();
    }

    if (isset($aInput['imagen'])) {
      /*$oFile = $request->file('imagen');
      $aInput['imagen_url'] = 'empresa_' . $aInput['id'] . '_' . date("Y_m_d") . '.' . $oFile->getClientOriginalExtension();
      $sPath = public_path() . '/images/empresas';
      $oSubir = $oFile->move($sPath, $aInput['imagen_url'] );*/

      $image = $request->file('imagen');
      $aInput['imagen_url'] = 'empresa_' . \Auth::User()->id_empresa . '_' . date("Y_m_d H_i_s") . '.' . $image->getClientOriginalExtension();
      $image_resize = Image::make($image->getRealPath());
      $image_resize->resize(300,300);
      $image_resize->save(public_path('images/productos/' .$aInput['imagen_url']));
    }

    Productos::creaRegistro($aInput);

    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha creado exitosamente el nuevo producto.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/productos');
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    if (isset($aInput['imagen'])) {
      /*$oFile = $request->file('imagen');
      $aInput['imagen_url'] = 'empresa_' . $aInput['id'] . '_' . date("Y_m_d") . '.' . $oFile->getClientOriginalExtension();
      $sPath = public_path() . '/images/empresas';
      $oSubir = $oFile->move($sPath, $aInput['imagen_url'] );*/

      $image = $request->file('imagen');
      $aInput['imagen_url'] = 'empresa_' . \Auth::User()->id_empresa . '_' . date("Y_m_d H_i_s") . '.' . $image->getClientOriginalExtension();
      $image_resize = Image::make($image->getRealPath());
      $image_resize->resize(300,300);
      $image_resize->save(public_path('images/productos/' .$aInput['imagen_url']));
    }

    Productos::actualizaRegistro($aInput);

    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha actualizado el producto exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/productos');
  }

  public function edit_costo_indirecto($id)
  {
    $aCostos = [];
    $aCosto = [];

    if ($id != null && $id != 0) {
      $aCosto = CostosIndirectosPro::select('costos_indirectos_pro.id','costos_indirectos_pro.id_costo_indirecto as id_costo','costos_indirectos_pro.unidades','costos_indirectos.concepto','costos_indirectos.costo','costos_indirectos.unidades as unidades_totales','medidas.medida')
      ->where('costos_indirectos_pro.id',$id)
      ->leftJoin('costos_indirectos','costos_indirectos.id','costos_indirectos_pro.id_costo_indirecto')
      ->leftJoin('medidas','medidas.id','costos_indirectos.id_medida')
      ->first()->toArray();
      $sTipoVista = 'editar';
    }else {
      $aCostos = CostosIndirectos::select('costos_indirectos.*','medidas.medida','costos_indirectos.unidades as unidades_totales')
      ->where('costos_indirectos.id_empresa', \Auth::User()->id_empresa)
      ->leftJoin('medidas','medidas.id','costos_indirectos.id_medida')
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
        'id_producto' => $id,
        'sTipoVista' => $sTipoVista
      ]
    ]);
  }

  public function store_costo_indirecto(Request $request)
  {
    $aInput = $request->all();
    $oCostoIndirecto = CostosIndirectosPro::creaRegistro($aInput);
    $oCostosIndirectos = $this->get_costos_indirectos( $aInput['id_producto'] );
    $html = view('costeo.table_costos_indirectos')->with('oCostosIndirectos', $oCostosIndirectos)->with('sTipoCosto','producto')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Se ha guardado el costo indirecto exitosamente.',
      'resultado' => $html
    ]);
    //return Redirect::to('/productos/editar/' . $oCostoIndirecto->id_producto . '#costos_indirectos');
  }

  public function update_costo_indirecto(Request $request)
  {
    $aInput = $request->all();
    $oCostoIndirecto = CostosIndirectosPro::actualizaRegistro($aInput);
    $oCostosIndirectos = $this->get_costos_indirectos( $aInput['id_producto'] );
    $html = view('costeo.table_costos_indirectos')->with('oCostosIndirectos', $oCostosIndirectos)->with('sTipoCosto','producto')->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Se ha guardado el costo indirecto exitosamente.',
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
    return Redirect::to('/productos/editar/' . $oCostoIndirecto->id_producto . '#costos_indirectos');
  }

  public function edit_costo_por_producto($id)
  {
    $aCostos = [];
    $aCosto = [];

    if ($id != null && $id != 0) {
      $aCosto = CosteoProducto::select('costeo_producto.id','costeo_producto.id_producto as id_costo','costeo_producto.unidades','materia_prima.concepto','materia_prima.unidades as unidades_totales','medidas.medida')
      ->where('costeo_producto.id',$id)
      ->leftJoin('materia_prima','materia_prima.id','costeo_producto.id_materia_prima')
      ->leftJoin('medidas','medidas.id','materia_prima.id_medida')
      ->first()->toArray();
      $sTipoVista = 'editar';
    }else {
      $aCostos = MateriaPrima::select('materia_prima.*','medidas.medida', 'materia_prima.unidades as unidades_totales')
      ->where('materia_prima.id_empresa', \Auth::User()->id_empresa)
      ->leftJoin('medidas','medidas.id','materia_prima.id_medida')
      ->get()->toArray();
      $sTipoVista = 'crear';
    }

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa.',
      'resultado' => [
        'aCostos' => $aCostos,
        'aCosto' => $aCosto,
        'id_producto' => $id,
        'sTipoVista' => $sTipoVista
      ]
    ]);
  }

  public function store_costo_por_producto(Request $request)
  {
    $aInput = $request->all();
    $oCosto = CosteoProducto::creaRegistro($aInput);
    $oCostosPorProducto = $this->get_costos_por_producto( $aInput['id_producto'] );
    $html = view('costeo.productos.table_costos_por_producto')->with('oCostosPorProducto', $oCostosPorProducto)->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Se ha guardado el costo por producto exitosamente.',
      'resultado' => $html
    ]);
  }

  public function update_costo_por_producto(Request $request)
  {
    $aInput = $request->all();
    //dd($aInput);
    $oCosto = CosteoProducto::actualizaRegistro($aInput);
    $oCostosPorProducto = $this->get_costos_por_producto( $aInput['id_producto'] );
    $html = view('costeo.productos.table_costos_por_producto')->with('oCostosPorProducto', $oCostosPorProducto)->render();
    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Se ha guardado el costo por producto exitosamente.',
      'resultado' => $html
    ]);
  }

  public function destroy_costo_por_producto(Request $request)
  {
    $aInput = $request->all();
    //dd( $aInput );
    $oCosto = CosteoProducto::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado el costo por producto exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/productos/editar/' . $oCosto->id_producto . '#costos_por_producto');
  }

  public function destroy(Request $request)
  {
    $aInput = $request->all();
    $oProducto = Productos::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado el producto exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/productos');
  }
}
