<?php

namespace App\Http\Controllers;

use App\Models\Medidas;
use App\Models\MateriaPrima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redirect;

class MateriaPrimaController extends Controller
{
  public function index()
  {
    $oMateriasPrima = MateriaPrima::where('id_empresa', \Auth::User()->id_empresa )->where('estado',1)->orderBy('id','DESC')->get();
    return view('catalogos.materiaPrima.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Listado de materia prima']],
      'sActivePage' => 'materia_prima',
      'sTitulo' => 'MATERIA PRIMA',
      'sDescripcion' => 'Administración de la materia prima.',
      'oMateriasPrima' => $oMateriasPrima,
    ]);
  }

  public function create()
  {
    $oMedidas = Medidas::pluck('medida','id');
    return view('catalogos.materiaPrima.guardar',[
      'aBreadCrumb' => [['link'=> '/materia_prima', 'label'=> 'Listado de materia prima'],['link'=> 'active', 'label'=> 'Nueva materia prima']],
      'sActivePage' => 'materia_prima',
      'sTitulo' => 'NUEVA MATERIA PRIMA',
      'sDescripcion' => 'Ingresa los datos de la materia prima.',
      'sTipoVista' => 'crear',
      'oMedidas' => $oMedidas
    ]);
  }

  public function store(Request $request)
  {

    $input = $request->all();
    MateriaPrima::creaRegistro($input);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha creado la materia prima exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/materia_prima');
  }

  public function edit( $iId )
  {
    try {
      $oMateriaPrima = MateriaPrima::where('id', $iId )->where('id_empresa', \Auth::User()->id_empresa)->first();
      $oMedidas = Medidas::pluck('medida','id');
      return view('catalogos.materiaPrima.guardar',[
        'aBreadCrumb' => [['link'=> '/materia_prima', 'label'=> 'Listado de materia prima'],['link'=> 'active', 'label'=> 'Editar materia prima']],
        'sActivePage' => 'materia_prima',
        'sTitulo' => mb_strtoupper( $oMateriaPrima->concepto ),
        'sDescripcion' => 'Actualiza los datos de la materia prima.',
        'sTipoVista' => 'editar',
        'oMateriaPrima' => $oMateriaPrima,
        'oMedidas' => $oMedidas
      ]);
    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() );
    }
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    MateriaPrima::actualizaRegistro($aInput);
    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha actualizado la materia prima exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/materia_prima');
  }

  public function destroy(Request $request)
  {
    $aInput = $request->all();
    $oMateriaPrima = MateriaPrima::eliminarRegistro( $aInput['id']);
    Session::flash('tituloMsg','Eliminado exitosamente!');
    Session::flash('mensaje',"Se ha eliminado la materia prima exitosamente.");
    Session::flash('tipoMsg','success');
    return Redirect::to('/materia_prima');
  }
}
