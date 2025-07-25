<?php

namespace App\Http\Middleware;
use Closure;
use Redirect;

class ValidarPermisos
{
  /**
  * Handle an incoming request.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  \Closure  $next
  * @return mixed
  */
  public function handle($request, Closure $next, $seccion)
  {
    $aPermisos = \Auth::User()->permisos();
    if ( "general" == $seccion || ($aPermisos['boolTrial'] && @$aPermisos[$seccion]) ) {//Sección general o modo prueba y con acceso a la sección
      return $next($request);
    }else {
      //Validación de permisos
      if ( @$aPermisos[$seccion] ){
        return $next($request);
      }else{
        return Redirect::to('/home');
      }
    }
  }
}
