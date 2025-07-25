<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Solicitudes;
use App\Models\Empresas;
use App\Models\Licencias;
use App\User;
use Redirect;

class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  protected $redirectTo = '/home';

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct(Request $request)
  {
    $sUrl = $request->getPathInfo();
    //dd( strpos($request->getPathInfo(),'/login_consultor') !== false );
    if ( strpos($sUrl,'/login_consultor') !== false ) {
      //dd( $this->guard()->logout() );
      $this->guard()->logout();
      return Redirect::to($sUrl);
    }else {
      $this->middleware('guest')->except('logout');
    }
  }

  public function login_consultor($iIdEmpresa)
  {
    $this->guard()->logout();
    $oEmpresa = Empresas::find($iIdEmpresa);
    return view('auth.loginConsultor',[
      'oEmpresa' => $oEmpresa,
      'iIdEmpresa' => $iIdEmpresa
    ]);
  }

  /*protected function redirectTo($request)
  {
    dd($request->all());
    if (! $request->expectsJson()) {
      return route('login');
    }
  }*/

  protected function authenticated(Request $request, $user)
  {
    $aDatos = $request->all();

    $user = $user->select('usuarios.*','empresas.estado as estado_empresa','empresas.vencimiento_licencia','empresas.id_licencia')
    ->where('usuarios.id',$user->id)
    ->leftJoin('empresas','empresas.id','usuarios.id_empresa')
    ->first();

    $oEmpresa = Empresas::where('empresas.id',$user->id_empresa)->leftJoin('usuarios','usuarios.id','empresas.id_usuario')->select('empresas.*','usuarios.email')->first();
    $boolSubscriptionStripe = ($oEmpresa->subscription('main') && @$oEmpresa->subscription('main')->stripe_status == 'active');

    if ( @$aDatos['login_consultor'] == 1 ) {
      if ( $user->id_empresa == 0 ) {
        $oUsuarioEmpresa = User::select('usuarios.*')
        ->where('usuarios.id',$aDatos['id_usuario'])
        ->leftJoin('empresas','empresas.id','usuarios.id_empresa')
        ->first();

        $request->session()->put('login_consultor', true);
        $request->session()->put('oUsuarioConsultor', $user);

        \Auth::login($oUsuarioEmpresa);
        //dd( \Auth::user()->toArray() , session('oUsuarioConsultor')->toArray() );

      }else {
        $errors = [$this->username() => trans('auth.autorizacion')];
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->back()->withInput($request->only($this->username(), 'remember'))
        ->withErrors($errors);
      }
    }elseif( $user->estado_empresa != 1 ){

      $errors = [$this->username() => trans('auth.estado')];
      $this->guard()->logout();
      $request->session()->invalidate();
      return redirect()->back()->withInput($request->only($this->username(), 'remember'))
      ->withErrors($errors);

    }elseif ( strtotime($user->vencimiento_licencia) < strtotime(date('Y-m-d')) || ($boolSubscriptionStripe != true && $user->id_licencia != null) ) {
      //Licencia vencida
      return Redirect::to('/soporte_servicios/licenciamiento');

      /*$errors = [$this->username() => trans('auth.licencia')];
      $this->guard()->logout();
      $request->session()->invalidate();
      $oLicenciaUsuario = Licencias::find( $oEmpresa->id_licencia );
      $oLicencias = Licencias::where('estado','!=',0)->get();
      $oLastSolicitud = Solicitudes::where('id_usuario_solicitante',$user->id)->where('estado','!=',0)->where('tipo',1)->orderBy('fecha_registro', 'DESC')->first();

      return view('soporteServicios.licenciamiento.index',[
        'aBreadCrumb' => [['link'=> url('soporte_servicios'), 'label'=> 'Soporte y servicios'],['link'=> 'active', 'label'=> 'Licenciamiento']],
        'sActivePage' => 'soporte_servicios',
        'sTitulo' => 'LICENCIAMIENTO',
        'sDescripcion' => 'Verificación del servicio actual y licencias disponibles.',
        'oLicenciaUsuario' => $oLicenciaUsuario,
        'oLicencias' => $oLicencias,
        'oLastSolicitud' => $oLastSolicitud,
        'iIdUsuario' => $user->id,
        'boolLicenciaVendida' => true
      ]);*/

    }else {
      return Redirect::to('/home');
      //$accion = 'Inicio de sesión.';
      //Bitacora::creaRegistro($accion);
    }
  }

  /**
  * Where to redirect users after login.
  *
  * @var string
  */

}
