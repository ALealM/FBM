<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Charge;
use Redirect;
use Illuminate\Support\Facades\Session;

class StripeController extends Controller
{
  //Comprobar si tiene licencia vigente: $user->subscription('main')
  public function __construct(){
    Stripe::setApiKey(config('services.stripe.secret'));
  }

  public function pago()
  {
    return view('stripe.pago',[
      //"" => ,
    ]);
  }

  public function store_cargo(Request $request)
  {
    try {
      $aInput = $request->all();

      Stripe::setApiKey(config('services.stripe.secret'));
      $oCustomer = Customer::create(array(
        'email' => $aInput['stripeEmail'],
        'source'  => $aInput['stripeToken']
      ));
      $oCharge = Charge::create(array(
        'customer' => $oCustomer->id,
        'amount'   => 1000,//centavos
        'currency' => 'mxn'//'usd'
      ));

      Session::flash('tituloMsg','Pago realizado con éxito');
      Session::flash('mensaje',"Se ha realizado el cargo exitosamente.");
      Session::flash('tipoMsg','success');
      return Redirect::to('/home');

    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() );
    }
  }

  public function update_metodo_pago()
  {
    //Obtener informacion de la cuenta de la empresa (cliente en stripe)
    $oEmpresa = \Auth::User()->empresa();
    return view('stripe.metodosPagos',[
      'intent' => $oEmpresa->createSetupIntent()
    ]);
  }

  public function suscripcion()
  {
    //Obtener informacion de la cuenta de la empresa (cliente en stripe)
    //$oEmpresa = \Auth::User()->empresa()->leftJoin('usuarios','usuarios.id','empresas.id_usuario')->select('empresas.*','usuarios.email')->first();
    //Verificar si ya existe como cliente o crearlo en stripe
    //$oCustumer = $oEmpresa->createOrGetStripeCustomer();

    //$paymentMethods = $oEmpresa->paymentMethods();
    //dd( $paymentMethods ,$oEmpresa->toArray() );
    return view('stripe.suscripcion',[
      //'intent' => $oEmpresa->createSetupIntent()
    ]);
  }

  public function store_suscripcion($aDatos)
  {
    try {
      $oEmpresa = \Auth::User()->empresa();
      $stripe = Stripe::setApiKey(config('services.stripe.secret'));
      if ($oEmpresa->stripe_id == null) {
        $oCustomer = Customer::create(array(
          'email' => $oEmpresa->email,
          //'source'  => $aInput['stripeToken']
        ));
        $oEmpresa->stripe_id = $oCustomer->id;
        $oEmpresa->save();
      }
      //dd($oEmpresa->toArray() , $oEmpresa->subscription('main') );

      if ( $this->get_validacion_suscripcion() ) {//Verificar si ya tiene una licencia vigente
        $aResultado['estatus'] = 0;
        $aResultado['mensaje'] = "Actualmente se cuenta con una suscripción vigente, para suscribirse requieres cancelar la actual licencia.";
        $aResultado['resultado'] = null;
        return $aResultado;
      }

      //Metdodo de pago
      $paymentMethod = $oEmpresa->defaultPaymentMethod();

      if ($paymentMethod) {
        //$oEmpresa->newSubscription('main','price_1IHXbzIAM9Cgd0eyLOQyGnY2')->create($paymentMethod->id);
        $oEmpresa->newSubscription('main',$aDatos['price'])->noProrate()->create($paymentMethod->id);//Para no prorrogaeteo (Tal vez aqui no sea necesario)
      }else {
        $aResultado['estatus'] = 0;
        $aResultado['mensaje'] = "Se debe de agregar una forma de pago para continuar.";
        $aResultado['resultado'] = null;
        return $aResultado;
      }
    } catch (\Exception $e) {
      $aResultado['estatus'] = 0;
      $aResultado['mensaje'] = $e->getMessage();
      $aResultado['resultado'] = null;
      return $aResultado;
    }
    $aResultado['estatus'] = 1;
    $aResultado['mensaje'] = "Suscripción exitosa.";
    $aResultado['resultado'] = null;
    return $aResultado;
  }

  public function cancel_suscripcion($oEmpresa)
  {
    try {
      $stripe = Stripe::setApiKey(config('services.stripe.secret'));
      //$oEmpresa->subscription('main')->cancel(); cancelar con periodo de gracia
      $oEmpresa->subscription('main')->noProrate()->cancelNow();//Para no prorrogateo

    } catch (\Exception $e) {
      $aResultado['estatus'] = 0;
      $aResultado['mensaje'] = $e->getMessage();
      $aResultado['resultado'] = null;
      return $aResultado;
    }
    $aResultado['estatus'] = 1;
    $aResultado['mensaje'] = "Cancelación exitosa.";
    $aResultado['resultado'] = null;
    return $aResultado;
  }

  public function getPaymentMethods()
  {
    $oEmpresa = \Auth::User()->empresa();
    return $oEmpresa->paymentMethods();

  }

  public function getDefaultPaymentMethod()
  {
    $oEmpresa = \Auth::User()->empresa();
    return $oEmpresa->defaultPaymentMethod();
  }

  public function getIntentSecretClient()
  {
    $oEmpresa = \Auth::User()->empresa();
    //'intent' => $oEmpresa->createSetupIntent()
    return $oEmpresa->createSetupIntent();
  }

  public function get_validacion_suscripcion()
  {
    $oEmpresa = \Auth::User()->empresa();
    if ($oEmpresa->subscription('main') && @$oEmpresa->subscription('main')->stripe_status == 'active' ) {
      return true;
    }else {
      return false;
    }
  }

  public function update_default_payment_method(Request $request)
  {
    $aInput = $request->all();
    $oEmpresa = \Auth::User()->empresa();

    $oEmpresa->updateDefaultPaymentMethod( $aInput['setupIntent']['payment_method']);

    return response()->json([
      'estatus' => 1,
      'mensaje' => "Actualización de forma de pago exitosa.",
      'resultado' => null
    ]);

    /*Session::flash('tituloMsg','Suscripción con éxito');
    Session::flash('mensaje',"Se ha realizado el cargo exitosamente.");
    Session::flash('tipoMsg','success');
    return redirect()->back();*/
  }
}
