<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TiposAsesorias;
use App\Models\Licencias;
use App\Models\Empresas;
use App\Models\Solicitudes;
use App\Models\Tickets;
use Illuminate\Support\Facades\Mail;
use App\Mail\Mensaje;
use Redirect;
use Illuminate\Support\Facades\Session;

class SoporteServiciosController extends Controller
{
  public function index()
  {
    return view('soporteServicios.index',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Soporte y servicios']],
      'sActivePage' => 'soporte_servicios',
      'sTitulo' => 'SOPORTE Y SERVICIOS',
      'sDescripcion' => 'Seleccione el tipo de servicio.'
    ]);
  }

  public function asesoramiento_index()
  {
    $oTiposAsesorias = TiposAsesorias::where('estado','!=',0)->get();
    $oLastSolicitud = Solicitudes::where('id_usuario_solicitante',\Auth::User()->id)->where('estado','!=',0)->where('tipo',2)->orderBy('fecha_registro', 'DESC')->first();

    return view('soporteServicios.asesoramiento.index',[
      'aBreadCrumb' => [['link'=> url('soporte_servicios'), 'label'=> 'Soporte y servicios'],['link'=> 'active', 'label'=> 'Asesoramiento']],
      'sActivePage' => 'soporte_servicios',
      'sTitulo' => 'ASESORAMIENTO',
      'sDescripcion' => 'Solicita una asesoría.',
      'oTiposAsesorias' => $oTiposAsesorias,
      'oLastSolicitud' => $oLastSolicitud
    ]);
  }

  public function solicitudes_asesoramiento_index()
  {
    $oSolicitudes = Solicitudes::select('solicitudes.*','tipos_asesorias.nombre as nombre_asesoria','tipos_asesorias.descripcion as descripcion_asesoria')
    ->where('solicitudes.id_usuario_solicitante',\Auth::User()->id)
    ->where('solicitudes.estado','!=',0)
    ->where('solicitudes.tipo',2)
    ->leftJoin('tipos_asesorias','tipos_asesorias.id','solicitudes.id_asesoria')
    ->orderBy('solicitudes.fecha_registro', 'DESC')
    ->get();

    return view('soporteServicios.asesoramiento.solicitudes.index',[
      'aBreadCrumb' => [['link'=> url('soporte_servicios'), 'label'=> 'Soporte y servicios'],['link'=> url('soporte_servicios/asesoramiento'), 'label'=> 'Asesoramiento'],['link'=> 'active', 'label'=> 'Solicitudes']],
      'sActivePage' => 'soporte_servicios',
      'sTitulo' => 'SOLICITUDES DE ASESORAMIENTO',
      'sDescripcion' => 'Verificar las solicitudes de asesorías que has enviado.',
      'oSolicitudes' => $oSolicitudes
    ]);
  }

  public function solicitud_asesoramiento_store(Request $request)
  {
    $aInput = $request->all();
    $aInput['tipo'] = 2;
    $oSolicitud = Solicitudes::creaRegistro( $aInput );
    $aDatos = [
      'sAsunto' => 'Solicitud de asesoría',
      'body_mensaje' =>
        '<p style="text-align:center">Se ha recibido una nueva <strong>Solicitud de asesoría</strong> ingresa al CRM para ver el detalle de la solicitud</p>' .
        '<br><a href="'. env('URLCRM') .'" target="_blank" style="text-align:center">Ir a CRM...</a><br>',
      'email' => env('MAIL_USERNAME')
    ];
    Mail::to($aDatos['email'])->send(new Mensaje( $aDatos ));

    Session::flash('tituloMsg','Solicitud enviada');
    Session::flash('mensaje',"Se ha enviado la solicitud.");
    Session::flash('tipoMsg','success');
    return back()->withInput();
  }

  //Licencias
  public function licenciamiento_index()
  {
    $oEmpresa = \Auth::User()->empresa();
    $oLicenciaUsuario = Licencias::find( $oEmpresa->id_licencia );
    $oLicencias = Licencias::where('estado','!=',0)->get();
    $oLastSolicitud = Solicitudes::where('id_usuario_solicitante',\Auth::User()->id)->where('estado','!=',0)->where('tipo',1)->orderBy('fecha_registro', 'DESC')->first();

    return view('soporteServicios.licenciamiento.index',[
      'aBreadCrumb' => [['link'=> url('soporte_servicios'), 'label'=> 'Soporte y servicios'],['link'=> 'active', 'label'=> 'Licenciamiento']],
      'sActivePage' => 'soporte_servicios',
      'sTitulo' => 'LICENCIAMIENTO',
      'sDescripcion' => 'Verificación del servicio actual y licencias disponibles.',
      'sTipoVista' => 'index',
      'oLicenciaUsuario' => $oLicenciaUsuario,
      'oLicencias' => $oLicencias,
      'oLastSolicitud' => $oLastSolicitud,
      'iIdUsuario' => \Auth::User()->id,
      'boolAuth' => true,
      'oEmpresa' => $oEmpresa,
      'boolSubscriptionStripe' => app("App\Http\Controllers\StripeController")->get_validacion_suscripcion()
    ]);
  }

  public function suscripcion($iIdLicencia)
  {
    $oEmpresa = \Auth::User()->empresa();
    //dd( $oEmpresa->subscription('main') );
    $oLicencia = Licencias::find( $iIdLicencia );
    $oLicenciaUsuario = Licencias::find( $oEmpresa->id_licencia );
    $oDefaultPaymentMethod = app('App\Http\Controllers\StripeController')->getDefaultPaymentMethod();
    $intent = app('App\Http\Controllers\StripeController')->getIntentSecretClient();
    return view('soporteServicios.licenciamiento.suscripcion',[
      'aBreadCrumb' => [['link'=> url('soporte_servicios'), 'label'=> 'Soporte y servicios'],['link'=> url('soporte_servicios/licenciamiento'), 'label'=> 'Licenciamiento'],['link'=> 'active', 'label'=> 'Suscripción']],
      'sActivePage' => 'soporte_servicios',
      'sTitulo' => 'SUSCRIPCIÓN',
      'sDescripcion' => 'Suscripción a una licencia de FBM.',
      'oEmpresa' => $oEmpresa,
      'oLicencia' => $oLicencia,
      'oLicenciaUsuario' => $oLicenciaUsuario,
      'oDefaultPaymentMethod' => $oDefaultPaymentMethod,
      'intent' => $intent,
      'boolSubscriptionStripe' => app("App\Http\Controllers\StripeController")->get_validacion_suscripcion()
    ]);
  }

  public function suscribirse(Request $request)
  {
    $aInput = $request->all();
    $oLicencia = Licencias::find( $aInput['id_licencia'] );
    $aDatos = ['price' => $oLicencia->stripe_price ];
    $aResultado = app('App\Http\Controllers\StripeController')->store_suscripcion($aDatos);
    if ( $aResultado['estatus'] == 1 ) {

      $oEmpresa = \Auth::User()->empresa();
      $oEmpresa->tipo = 1;//Empresa con licencia
      $oEmpresa->id_licencia = $oLicencia->id;
      $oEmpresa->vencimiento_licencia = date('Y-m-d',strtotime('+'.$oLicencia->duracion.' months',strtotime(date('Y-m-d'))));
      $oEmpresa->save();

      Session::flash('tituloMsg','Suscripción exitosa');
      Session::flash('mensaje',"Te has suscrito a " .$oLicencia->nombre." con éxito.");
      Session::flash('tipoMsg','success');
    }else {
      Session::flash('tituloMsg','Alerta');
      Session::flash('mensaje',$aResultado['mensaje']);
      Session::flash('tipoMsg','warning');
    }
    return back();
  }

  public function cancelar_suscripcion(Request $request)
  {
    $aInput = $request->all();
    $oEmpresa = \Auth::User()->empresa();
    $aResultado = app('App\Http\Controllers\StripeController')->cancel_suscripcion($oEmpresa);
    if ( $aResultado['estatus'] == 1 ) {
      //$oEmpresa->id_licencia = $oLicencia->id;
      $oEmpresa->vencimiento_licencia = date('Y-m-d',strtotime('-1 days',strtotime(date('Y-m-d'))));
      $oEmpresa->save();

      Session::flash('tituloMsg','Cancelación exitosa');
      Session::flash('mensaje',"Has cancelado tu suscripción actual exitosamente.");
      Session::flash('tipoMsg','success');
    }else {
      Session::flash('tituloMsg','Alerta');
      Session::flash('mensaje',$aResultado['mensaje']);
      Session::flash('tipoMsg','warning');
    }
    return back();
  }

  public function solicitud_licencia_store(Request $request)
  {
    $aInput = $request->all();
    $aInput['tipo'] = 1;
    $oSolicitud = Solicitudes::creaRegistro( $aInput );
    $aDatos = [
      'sAsunto' => 'Solicitud de licencia',
      'body_mensaje' =>
        '<p style="text-align:center">Se ha recibido una nueva <strong>Solicitud de Licencia</strong> ingresa al CRM para ver el detalle de la solicitud</p>' .
        '<br><a href="'. env('URLCRM') .'" target="_blank" style="text-align:center">Ir a CRM...</a><br>',
      'email' => env('MAIL_USERNAME')
    ];
    Mail::to($aDatos['email'])->send(new Mensaje( $aDatos ));

    Session::flash('tituloMsg','Solicitud enviada');
    Session::flash('mensaje',"Se ha enviado la solicitud.");
    Session::flash('tipoMsg','success');
    return back()->withInput();
  }
  //End Licencias

  public function preguntas_frecuentes_index()
  {
    $aPreguntas = [
      'Usuarios' => [
        '¿Donde puedo modificar mi información?' =>
          'Modifica tu información después de iniciar sesión en la parte superior derecha da clic en el icono de usuario junto a tu correo electrónico en seguida '.
          'se desplegará una lista de opciones, da clic en "Mi perfil" para que aparezca el formulario para modificar tu información.',
        '¿Como modificar mi contraseña?' =>
          'Modifica tu información y contraseña después de iniciar sesión en la parte superior derecha da clic en el icono de usuario junto a tu correo electrónico en seguida '.
          'se desplegará una lista de opciones, da clic en "Mi perfil" para que aparezca el formulario para modificar tu información. Para modificar tu contraseña basta con llenar '.
          'el campo de contraseña y su confirmación.',
        '¿Como cerrar sesión?' =>
          'En la parte superior derecha da clic en el icono de usuario junto a tu correo electrónico en seguida '.
          'se desplegará un menú de opciones, da clic en "Cerrar sesión".',
      ],
      'Empresas' => [
        '¿Donde puedo modificar la información de mi empresa?' =>
          'Modifica la información después de iniciar sesión en la parte superior derecha da clic en el icono de usuario junto a tu correo electrónico en seguida '.
          'se desplegará una lista de opciones, da clic en "Mi empresa" para que aparezca el formulario para modificar la información.',
        '¿Como subir el logo de mi empresa?' =>
          'En la parte superior derecha da clic en el icono de usuario junto a tu correo electrónico en seguida '.
          'se desplegará un menú de opciones da clic en "Mi empresa" ahí visualizarás la imagen predeterminada de FBM o '.
          'la última actualización de tu logo aunado a un botón para seleccionar una nueva imagen, después de seleccionarla da clic en guadar.',

        '¿Como obtengo ayuda o asesoramiento financiero para mi empresa?' =>
          'Para obtener ayuda con tu sistema FBM o requerir asesoramiento financiero comunícate directamente al correo electrónico ' . env('MAIL_USERNAME') . ' o solicita ' .
          'asesoramiento en el módulo de "Soporte y servicios" en la sección de asesoramiento de tu sistema FBM.'
      ],
      'Licencia' => [
        '¿Como puedo ver mi tipo de licencia?' => 'Dirigeté a la opción de "Soporte y servicios" en el menú lateral de FBM, en el apartado de licenciamiento.',
        '¿Como puedo conseguir una licencia?' =>
          'Dirigeté a la opción de "Soporte y servicios" en el menú lateral de FBM, da clic en el apartado de licenciamiento donde aparecerán las distintas '.
          'opciones disponbles, solicita una opción para empezar a usarla.',
        '¿Como cancelar mi licencia?' => 'Comunícate directamente al correo electrónico ' . env('MAIL_USERNAME') . '.'
      ],
      'Productos' => [
        '¿Como subir mis productos?' =>
          'Para subir adecuadamente tus productos, sigue los siguientes pasos: <br>'.
          '<ol>' .
          '<li>Primero ingresa la materia prima necesaria en el módulo "Materia prima" (si tu operación no requiere esta acción, puedes omitirla).</li>' .
          '<li>Ingresa los costos indirectos de tus productos en el módulo "Costos indirectos" (si tu operación no requiere esta acción, puedes omitirla).</li>' .
          '<li>Dirigete al módulo de productos para crear tus productos, selecciona los costos por producto para poder forma una lista de requerimientos del mismo.</li>' .
          '</ol>'
      ],
      'Mano de obra' => [
        '¿Como puedo ingresar a mi organización?' => 'Puedes ingresar a tus trabajadores por grupo de trabajadores en el módulo de "Mano de obra".'
      ],
      'Ventas' => [
        '¿Como puedo ingresar mis ventas?' => 'Puedes ingresar las ventas en el módulo de "Ventas" por día.'
      ],
    ];
    return view('soporteServicios.preguntasFrecuentes.index',[
      'aBreadCrumb' => [['link'=> url('soporte_servicios'), 'label'=> 'Soporte y servicios'],['link'=> 'active', 'label'=> 'Preguntas frecuentes']],
      'sActivePage' => 'soporte_servicios',
      'sTitulo' => 'PREGUNTAS FRECUENTES',
      'sDescripcion' => 'Consulta las preguntas frecuentes de FBM.',
      'aPreguntas' => $aPreguntas
    ]);
  }

  public function tickets_index()
  {
    $oTickets = Tickets::where('estado','!=',0)
    ->where('id_empresa',\Auth::User()->id_empresa)
    ->orderBy('id','DESC')
    ->get();

    $aTipos = [
      'problema' => 'Problema',
      'error' => 'Error',
      'ayuda' => 'Ayuda',
      'pregunta' => 'Pregunta'
    ];

    $aPrioridades = [
      0 => 'Baja',
      1 => 'Media',
      2 => 'Alta'
    ];

    return view('soporteServicios.tickets.index',[
      'aBreadCrumb' => [['link'=> url('soporte_servicios'), 'label'=> 'Soporte y servicios'],['link'=> 'active', 'label'=> 'Tickets']],
      'sActivePage' => 'soporte_servicios',
      'sTitulo' => 'TICKETS',
      'sDescripcion' => 'Crea tickets para reportar de problemas, errores o simplemente solicitar ayuda de un tema en especifico y espera la respuesta un asesor.',
      'oTickets' => $oTickets,
      'aTipos' => $aTipos,
      'aPrioridades' => $aPrioridades
    ]);
  }

  public function tickets_store(Request $request)
  {
    $aInput = $request->all();
    $aInput['id_usuario'] = \Auth::User()->id;
    $aInput['id_empresa'] = \Auth::User()->id_empresa;
    $oTicket = Tickets::creaRegistro($aInput);

    Session::flash('tituloMsg','Ticket generado con éxito');
    Session::flash('mensaje',"Se ha generado el ticket, espera la respuesta de un asesor de FBM.");
    Session::flash('tipoMsg','success');
    return back()->withInput();
  }

  public function tickets_update(Request $request)
  {
    $aInput = $request->all();

    if ( $aInput['estado_nuevo'] == 1 ) { //respuesta a bitacora
      $aDatos = [
        'id' => $aInput['id'],
        'bitacora' => \Auth::User()->name . ' ' . \Auth::User()->apellido_paterno . ': ' . $aInput['bitacora']
      ];
      Session::flash('tituloMsg','Respuesta guardada en bitácora');
      Session::flash('mensaje',"Se ha guardado la respuesta en la bitácora del ticket.");
    }else {
      $aDatos = [
        'id' => $aInput['id'],
        'estado' => $aInput['estado_nuevo'],
        'bitacora' => 'El usuario ha ' . ($aInput['estado_nuevo'] == 0 ? 'eliminado' : ( $aInput['estado_nuevo'] == 3 ? 'cancelado' : '*')) . ' el ticket.'
      ];
      Session::flash('tituloMsg','Ticket ' . ($aInput['estado_nuevo'] == 0 ? 'eliminado' : ( $aInput['estado_nuevo'] == 3 ? 'cancelado' : '')));
      Session::flash('mensaje',($aInput['estado_nuevo'] == 0 ? 'Se ha eliminado el ticket.' : ( $aInput['estado_nuevo'] == 3 ? 'Se ha cancelado el ticket.' : '')));
    }
    Session::flash('tipoMsg','success');
    $oTicket = Tickets::actualizaRegistro($aDatos);
    return back();
  }
}
