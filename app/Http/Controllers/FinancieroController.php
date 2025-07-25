<?php

namespace App\Http\Controllers;
use App\User;
use App\Models\Proyectos;
use App\Models\Productos;
use App\Models\Periodos;
use App\Models\Ventas;
use App\Models\ManoObra as MO;
use App\Models\CosteoProducto as CP;
use App\Models\CostosFijos as CF;
use App\Models\MateriaPrima as MP;
use App\Models\CostosIndirectos as CI;
use App\Models\CostosIndirectosPro as CIP;
use App\Models\VariablesProyecto as VP;
use App\Models\Almacen as AL;
use App\Models\Medidas;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Session;

class FinancieroController extends Controller
{
  public function index()
  {

  }





  public function cambiaImagen(Request $request){
    $imagen = $request->get('imagen');
    $data['id'] = \Auth::User()->id;
    $data['imagen'] = $imagen;
    User::editaImagen($data);
  }

  public function cambiaColor(Request $request){
    $color = $request->get('color');
    $data['id'] = \Auth::User()->id;
    $data['color'] = $color;
    User::editaColor($data);
  }








}
