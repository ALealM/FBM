<?php

namespace App\Http\Controllers;

use App\Models\Proveedores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;


class ProveedoresController extends Controller
{
  public function index()
  {
    $oProveedores = Proveedores::where('id_empresa', \Auth::User()->id_empresa )->where('estado',1)->orderBy('id','DESC')->get();
    return view('catalogos.proveedores.index',[
      'aBreadCrumb' => [['link'=> '/catalogos', 'label'=> 'Catálogos'],['link'=> 'active', 'label'=> 'Listado de proveedores']],
      'sActivePage' => 'catalogos',
      'sTitulo' => 'PROVEEDORES',
      'sDescripcion' => 'Administración de los proveedores.',
      'oProveedores' => $oProveedores
    ]);
  }

  public function create()
  {
    return view('catalogos.proveedores.guardar',[
      'aBreadCrumb' => [['link'=> '/catalogos', 'label'=> 'Catálogos'],['link'=> '/proveedores', 'label'=> 'Listado de proveedores'],['link'=> 'active', 'label'=> 'Nuevo proveedor']],
      'sActivePage' => 'catalogos',
      'sTitulo' => 'NUEVO PROVEEDOR',
      'sDescripcion' => 'Ingresa los datos del proveedor.',
      'sTipoVista' => 'crear'
    ]);
  }

  public function store(Request $request)
  {
    $input = $request->all();
    Proveedores::creaRegistro($input);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha creado exitosamente el proveedor.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/proveedores');
  }

  public function edit( $iId )
  {
    try {
      $oProveedor = Proveedores::where('id', $iId )->where('id_empresa', \Auth::User()->id_empresa )->first();
      return view('catalogos.proveedores.guardar',[
        'aBreadCrumb' => [['link'=> '/catalogos', 'label'=> 'Catálogos'],['link'=> '/proveedores', 'label'=> 'Listado de proveedores'],['link'=> 'active', 'label'=> 'Editar proveedor']],
        'sActivePage' => 'catalogos',
        'sTitulo' => mb_strtoupper( $oProveedor->nombre ),
        'sDescripcion' => 'Actualiza los datos del proveedor.',
        'sTipoVista' => 'editar',
        'oProveedor' => $oProveedor
      ]);
    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() );
    }
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    Proveedores::actualizaRegistro($aInput);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha actualizado el proveedor exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/proveedores');
  }

  public function destroy(Request $request)
  {
    $aInput = $request->all();
    $oProveedores = Proveedores::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado el proveedor exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/proveedores');
  }
}
