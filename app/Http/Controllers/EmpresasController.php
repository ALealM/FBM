<?php

namespace App\Http\Controllers;

use App\Models\Empresas;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Session;

class EmpresasController extends Controller
{
  public function index()
  {
    $oEmpresa = Empresas::find( \Auth::User()->id_empresa );
    return view('empresa.edit',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Editar información de tu empresa']],
      'sActivePage' => 'empresa',
      'sTitulo' => 'EDITAR EMPRESA',
      'sDescripcion' => 'Actualiza información de tu empresa.',
      'sTipoVista' => 'editar',
      'oEmpresa' => $oEmpresa
    ]);
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    if (isset($aInput['imagen'])) {
      $oFile = $request->file('imagen');
      $aInput['imagen_url'] = 'empresa_' . $aInput['id'] . '_' . date("Y_m_d") . '.' . $oFile->getClientOriginalExtension();
      $sPath = public_path() . '/images/empresas';
      $oSubir = $oFile->move($sPath, $aInput['imagen_url'] );
    }
    //dd($aInput);
    $oEmpresa = Empresas::actualizaRegistro($aInput);

    Session::flash('tituloMsg','Guardado');
    Session::flash('mensaje',"Los datos de la empresa se han  actualizado");
    Session::flash('tipoMsg','success');
    return Redirect::to('/empresa');
  }
}
