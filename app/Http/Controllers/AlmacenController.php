<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use App\Models\Movimientos;
use App\Models\Proveedores;

use App\Models\Almacen;
use Redirect;
use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

class AlmacenController extends Controller
{
  public function index()
  {
    $oAlmacen = Almacen::select('almacen.*','materia_prima.concepto','medidas.medida')
    ->where('almacen.id_empresa', \Auth::User()->id_empresa )
    ->leftJoin('materia_prima','materia_prima.id','almacen.id_materia')
    ->leftJoin('medidas','medidas.id','materia_prima.id_medida')
    ->orderBy('id','DESC')
    ->get();


    return view('almacen.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Almacén']],
      'sActivePage' => 'almacen',
      'sTitulo' => 'ALMACÉN',
      'sDescripcion' => 'Ingrese los datos del nuevo registro de almacén.',
      'oAlmacen' => $oAlmacen
    ]);
  }

  public function edit(Request $request)
  {
    $aInput = $request->all();

    $oMateriaPrima = [];
    $oMateriasPrima = MateriaPrima::where('id_empresa', \Auth::User()->id_empresa )->where('estado',1)->pluck('concepto','id');
    $oProveedores = Proveedores::where('id_empresa', \Auth::User()->id_empresa )->where('estado',1)->pluck('nombre','id');
    if ( $aInput['iId'] > 0 ) {
      $oMateriaPrima = MateriaPrima::select('materia_prima.*','almacen.cantidad','almacen.precio','medidas.medida')
      ->where('materia_prima.id',$aInput['iId'])
      ->where('materia_prima.id_empresa', \Auth::User()->id_empresa )
      ->leftJoin('almacen','almacen.id_materia','materia_prima.id')
      ->leftJoin('medidas','medidas.id','materia_prima.id_medida')
      ->first();
    }
    $Html = view('almacen.guardar')
    ->with('oMateriaPrima', $oMateriaPrima)
    ->with('oMateriasPrima',$oMateriasPrima)
    ->with('iId',$aInput['iId'])
    ->with('sTipo',$aInput['sTipo'])
    ->with('oProveedores', $oProveedores)
    ->render();

    return response()->json([
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa.',
      'resultado' => $Html
    ]);
  }

  public function store(Request $request)
  {
    $aInput = $request->all();

    $oAlmacen = $this->verificar_existencias( $aInput['id_materia'] );
    $iIdAlmacen = @$oAlmacen->id;

    if ( $iIdAlmacen > 0 ) {
      $aInput['id'] = $iIdAlmacen;
      if ( $aInput['tipo'] == 'salida' && $aInput['cantidad'] > $oAlmacen->cantidad ) {
        Session::flash('tituloMsg','Alerta');
        Session::flash('mensaje',"La cantidad de salida no puede ser mayor a la disponible");
        Session::flash('tipoMsg','error');
        return Redirect::to('/almacen');
      }else {
        $iCantidadMovimiento = $aInput['cantidad'];
        $iCantidadNueva = ($aInput['tipo'] == 'entrada' ? ($oAlmacen->cantidad + $iCantidadMovimiento ) : ($oAlmacen->cantidad - $iCantidadMovimiento) );
        $aInput['cantidad'] = $iCantidadNueva;
        //dd($aInput);
        Almacen::actualizaRegistro($aInput);
        $aInput['cantidad'] = $iCantidadMovimiento;
      }
    }else {
      if ( $aInput['tipo'] == 'salida' ) {
        Session::flash('tituloMsg','Alerta');
        Session::flash('mensaje',"No hay disponibles");
        Session::flash('tipoMsg','error');
        return Redirect::to('/almacen');
      }else {
        Almacen::creaRegistro($aInput);
      }
    }
    Movimientos::creaRegistro($aInput);

    Session::flash('tituloMsg','Guardado exitoso!');
    Session::flash('mensaje',"Se ha realizado el movimiento de almacen");
    Session::flash('tipoMsg','success');
    return Redirect::to('/almacen');
  }

  public function verificar_existencias( $iId_materia )
  {
    $oAlmacen = Almacen::where('id_materia', $iId_materia )->where('id_empresa',\Auth::User()->id_empresa)->first();
    return $oAlmacen;
  }

  public function getMedida(Request $request)
  {
    $idMat = $request->get('id_mat');
    $med = MateriaPrima::find($idMat)->medida()->medida;
    return $med;
  }

  public function historial_movimientos(Request $request)
  {
    try {
      $aInput = $request->all();

      $oAlmacen = Almacen::where('id_materia',$aInput['iId'])
      ->where('id_empresa', \Auth::User()->id_empresa )
      ->first();

      $oMovimientos = Movimientos::select('movimientos.*','proveedores.nombre as nombre_proveedor')
      ->where('movimientos.id_materia', $aInput['iId'])
      ->where('movimientos.id_empresa', \Auth::User()->id_empresa )
      ->leftJoin('proveedores','proveedores.id','movimientos.id_proveedor')
      ->get();

      $htmlRows = '';
      foreach ($oMovimientos as $key => $oMovimiento) {
        $htmlRows = $htmlRows . '<tr><td>'. date("d/m/Y", strtotime( $oMovimiento->fecha )) . '</td>' .
        '<td class="text-center">' . ($oMovimiento->tipo ? 'Entrada' : 'Salida') . '</td>' .
        '<td class="text-left">' . ( @$oMovimiento->nombre_proveedor != null ? $oMovimiento->nombre_proveedor : 'Sin proveedor'). '</td>' .
        '<td class="text-right">$' . number_format($oMovimiento->precio,2,".",",") . '</td>' .
        '<td class="text-left"><small>' . ( $oMovimiento->observaciones != '' ?  '"' . $oMovimiento->observaciones . '"' : '"Sin observaciones"' ) . '</small></td>' .
        '<td class="text-right"><a class="text-' . ($oMovimiento->tipo ? 'success' : 'danger') . '"><strong>' . ($oMovimiento->tipo ? '+' : '-') . $oMovimiento->cantidad . '</strong></a></td></tr>';
      }

      $Html =
        '<table class="table table-condensed table-striped table-hover dataTable" role="grid" id="data-table">' .
          '<thead>' .
            '<th class="text-center" width="10">Fecha</th>' .
            '<th class="text-center">Tipo</th>' .
            '<th class="text-center">Proveedor</th>' .
            '<th class="text-center">Costo</th>' .
            '<th class="text-center">Observaciones</th>' .
            '<th class="text-center"></th>' .
          '</thead>' .
          '<tbody>' .
            $htmlRows .
          '</tbody>' .
          '<tfoot>' .
            '<td colspan="4"></td>' .
            '<td class="text-right"><strong>TOTAL DE EXISTENCIAS</strong></td>' .
            '<td class="text-right"><strong>' . $oAlmacen->cantidad . '</strong></td>' .
          '</tfoot>' .
        '</table>'.
        '<div class="card-footer text-center">' .
          '<a href="javascript:;" class="btn btn-secondary btn-sm" data-dismiss="modal" aria-label="Close"><i class="fa fa-rotate-left mr-2"></i>Regresar</a>' .
        '</div>';

      return response()->json([
        'estatus' => 1,
        'mensaje' => 'Consulta exitosa.',
        'resultado' => $Html
      ]);
    } catch (\Exception $e) {
      dd( $e->getMessage() );
      return view('error')->with('sError', $e->getMessage() );
    }
  }
}
