<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogosController extends Controller
{
  public function index()
  {
    return view('catalogos.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Catálogos']],
      'sActivePage' => 'catalogos',
      'sTitulo' => 'CATÁLOGOS',
      'sDescripcion' => 'Seleccione el tipo de catálogo.'
    ]);
  }
}
