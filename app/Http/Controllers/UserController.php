<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Redirect;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
  public function edit()
  {
    $oUsuario = User::find( \Auth::User()->id );
    return view('usuarios.edit',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Editar perfil de usuario']],
      'sActivePage' => 'usuarios',
      'sTitulo' => 'EDITAR PERFIL',
      'sDescripcion' => 'Cambia la información del perfil de usuario.',
      'sTipoVista' => 'editar',
      'oUsuario' => $oUsuario
    ]);
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    if ( $this->validar_correo_disponible( $aInput['email'], $aInput['id'] ) == false ) {
      Session::flash('tituloMsg','Alerta');
      Session::flash('mensaje',"El correo ingresado no esta disponible");
      Session::flash('tipoMsg','error');
      return back()->withInput();
    }
    if( $aInput['pass_1'] != '' && $aInput['pass_1'] != null ){
      if ( $aInput['pass_1'] == $aInput['pass_2']) {
        $aInput['password'] = Hash::make( $aInput['pass_1'] );
        $oUsuario = User::actualizaContrasena($aInput);
      }else {
        Session::flash('tituloMsg','Alerta');
        Session::flash('mensaje',"La contraseña ingresada, no es la misma");
        Session::flash('tipoMsg','error');
        return back()->withInput();
      }
    }
    $oUsuario = User::actualizaRegistro($aInput);

    Session::flash('tituloMsg','Guardado');
    Session::flash('mensaje',"Los datos del usuario se han  actualizado");
    Session::flash('tipoMsg','success');
    return Redirect::to('/usuarios/editar/'. $aInput['id']);
  }

  public function validar_correo_disponible($sEmail, $iIdUsuario)
  {
    $iValidacion = User::where('id','!=',$iIdUsuario)->where('email',$sEmail)->count();
    if( $iValidacion > 0 ){
      return false;
    }else {
      return true;
    }
  }
}
