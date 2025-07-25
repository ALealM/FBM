<?php
namespace App\Http\Controllers;
use App\Models\Pagos;
use App\Models\Cfdis;
use Illuminate\Http\Request;
use Response;
use App\Models\Empresas;
use Illuminate\Support\Facades\Session;
use File;
class FacturamaController extends Controller
{
  public $aUnitCodes = [
    'H87' => 'Pieza',
    'EA' => 'Elemento',
    'E48' => 'Unidad de Servicio',
    'ACT'	=> 'Actividad',
    'KGM'	=> 'Kilogramo',
    'E51'	=> 'Trabajo',
    'A9'	=> 'Tarifa',
    'MTR'	=> 'Metro',
    'AB'	=> 'Paquete a granel',
    'BB'	=> 'Caja base',
    'KT'	=> 'Kit',
    'SET'	=> 'Conjunto',
    'LTR'	=> 'Litro',
    'XBX'	=> 'Caja',
    'MON'	=> 'Mes',
    'HUR'	=> 'Hora',
    'MTK'	=> 'Metro cuadrado',
    '11'	=> 'Equipos',
    'MGM'	=> 'Miligramo',
    'XPK'	=> 'Paquete',
    'XKI'	=> 'Kit (Conjunto de piezas)',
    'AS'	=> 'Variedad',
    'GRM'	=> 'Gramo',
    'PR'	=> 'Par',
    'DPC'	=> 'Docenas de piezas',
    'xun'	=> 'Unidad',
    'DAY'	=> 'Día',
    'XLT' => 'Lote',
    '10' => 'Grupos',
    'MLT'	=> 'Mililitro',
    'E54'	=> 'Viaje'
  ];

  public function get_facturama()
  {
    $oFacturama = new \Facturama\Client( \Auth::User()->empresa()->facturama_user,  \Auth::User()->empresa()->facturama_pass );
    $oFacturama->setApiUrl('https://apisandbox.facturama.mx');
    return $oFacturama;
  }

  public function index()
  {
    try {
      $oCfdisEmitidos= $this->get_cfdis('issued')['resultado'];//Emitida
      //dd($oCfdisEmitidos);
      $oCfdisRecibidos = $this->get_cfdis('received')['resultado'];//Recibida
      $oCfdisRecibidosFbm = $this->get_cfdis_fbm()['resultado'];
      //dd($oCfdisRecibidosFbm);
      //$oCfdisRecibidos = array_merge($oCfdisEmitidos,$oCfdisRecibidosFbm); //$oCfdisRecibidos->merge($oCfdisRecibidosFbm);
      foreach ($oCfdisRecibidosFbm as $oCfdi) {
        $oCfdisRecibidos = array_merge($oCfdisRecibidos,[$oCfdi]);
      }
      return view('facturama.index',[
        'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Facturama']],
        'sActivePage' => 'facturama',
        'sTitulo' => 'FACTURAMA',
        'sDescripcion' => 'Enlace Facturama.',
        'oCfdisEmitidos' => $oCfdisEmitidos,
        'oCfdisRecibidos' => $oCfdisRecibidos

      ]);
    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() . (@$e->getPrevious() != null ? $e->getPrevious()->getMessage() : '' ))
      ->with('sInfo','Posiblemente Facturama esté experimentando problemas. Intenta de nuevo o busca ayuda en Facturama.');
    }
  }

  public function edit()
  {
    $oEmpresa = \Auth::User()->empresa();
    return view('facturama.edit',[
      'aBreadCrumb' => [['link'=> 'active', 'label'=> 'Enlace Facturama']],
      'sActivePage' => 'facturama',
      'sTitulo' => 'ENLACE FACTURAMA',
      'sDescripcion' => 'Enlaza tu cuenta de Facturama con FBM.',
      'oEmpresa' => $oEmpresa
    ]);
  }

  public function update(Request $request)
  {
    $aInput = $request->all();
    $aInput['id'] = \Auth::User()->id_empresa;
    $oEmpresa = Empresas::actualizaFacturama($aInput);

    Session::flash('tituloMsg','Enlace');
    Session::flash('mensaje',"Se han guardado las credenciales de enlace a Facturama.");
    Session::flash('tipoMsg','success');
    return redirect()->back();
  }

  public function remover_enlace()
  {
    $aInput = [
      'id' => \Auth::User()->id_empresa,
      'facturama_user' => null,
      'facturama_pass' => null
    ];
    $oEmpresa = Empresas::actualizaFacturama($aInput);

    return response()->json([
      "estatus" => 1,
      "mensaje" => "Se han removido las credenciales de enlace.",
      "resultado" => null
    ]);
  }

  public function get_usos_cfdi(Request $request)
  {
    $aInput = $request->all();
    //$oFacturama->get('catalogs/CfdiUses', ['keyword' => 'POAJ870619123'] ),//CfdiUse
    $oFacturama = $this->get_facturama();
    $aUsos = $oFacturama->get('catalogs/CfdiUses', ['keyword' => $aInput['rfc']] );
    /*if ( is_array(@$aUsos) ){
      $aUsos = array_column($aUsos,'Name','Value');
    }*/
    return response()->json([
      "estatus" => 1,
      "mensaje" => "Consulta exitosa.",
      "resultado" => $aUsos
    ]);
  }

  public function get_formas_pago()
  {
    $oFacturama = $this->get_facturama();
    return $oFacturama->get('catalogs/PaymentForms');//PaymentForm
  }

  public function get_product_code(Request $request)
  {
    $aInput = $request->all();
    $oFacturama = $this->get_facturama();
    // $lstNameIds = $facturama->get('catalogs/ProductsOrServices', ['keyword' => 'desarrollo'] );
    return response()->json([
      "estatus" => 1,
      "mensaje" => "Consulta exitosa.",
      "resultado" => $oFacturama->get('catalogs/ProductsOrServices',['keyword'=>$aInput['palabra']])
    ]);
  }

  public function store_cfdi(Request $request)
  {
    try {
      $aCfdi = $request->all();
      $aCfdi['items'] = json_decode($aCfdi['items']);
      //dd($aCfdi);
      $aDatos = [
        "Receiver" => [
          "Name" => $aCfdi['name_receiver'],//"Entidad receptora",
          "CfdiUse" => $aCfdi['cfdiUse_receiver'],//"P01",
          "Rfc" => $aCfdi['rfc_receiver']//"XAXX010101000"
        ],
        "CfdiType" => "I",//I|E|T|N|P (ingreso, egreso, traslado, nota de credito, pago)
        "NameId" => "1",
        "ExpeditionPlace" => $aCfdi['expedition_place'],//"78216",
        "PaymentForm" => $aCfdi['payment_method'],//"03",
        "PaymentMethod" => "PUE",//PUE = Una sola exhibición, PPD = Parcialidades),
        "Decimals" => "2",
        "Currency" => "MXN",
        "Date" => date('Y-m-d H:i:s'),//"2021-02-18T09:51:39",
        "Items" => $aCfdi['items']
        /*[[
          "Quantity" => "100",
          "ProductCode" => "84111506",
          "UnitCode" => "E48",
          "Unit" => "Unidad de servicio",
          "Description" => " API folios adicionales",
          "IdentificationNumber" => "23",
          "UnitPrice" => "0.50",
          "Subtotal" => "50.00",
          "Discount" => "10",
          "DiscountVal" => "10",
          "Taxes" => [[
            "Name" => "IVA",
            "Rate" => "0.16",
            "Total" => "6.4",
            "Base" => "40",
            "IsRetention" => "false"
          ]],
          "Total" => "46.40"
        ],[
          "Quantity" => "1",
          "ProductCode" => "84111506",
          "UnitCode" => "E48",
          "Unit" => "Unidad de servicio",
          "Description" => " API Implementación ",
          "IdentificationNumber" => "21",
          "UnitPrice" => "6000.00",
          "Subtotal" => "6000.00",
          "Taxes" => [[
            "Name" => "IVA",
            "Rate" => "0.16",
            "Total" => "960",
            "Base" => "6000",
            "IsRetention" => "false"
          ]],
          "Total" => "6960.00"
        ]]*/
      ];


      $oFacturama = $this->get_facturama();
      $oCfdi = $oFacturama->post('2/cfdis',$aDatos);
      /*
      RESPUESTA

      CertNumber: "30001000000400002321"
      CfdiType: "ingreso"
      Complement: {TaxStamp: {Uuid: "a2fa819d-343d-4807-9aa6-649800929016", Date: "2021-03-12T13:06:38",…}}
      Currency: "MXN - Peso Mexicano"
      Date: "2021-03-12T13:06:37"
      Discount: 0
      ExchangeRate: 0
      ExpeditionPlace: "78000 "
      Folio: "15"
      Id: "S4JiyIWdZ2JHZcCKfGkQ9A2"
      Issuer: {FiscalRegime: "605 - Sueldos y Salarios e Ingresos Asimilados a Salarios", Rfc: "FUNK671228PH6",…}
      Items: [{ProductCode: "72151802", UnitCode: "E48", Discount: 0, Quantity: 1, Unit: "E48 - ",…}]
      Observations: ""
      OriginalString: "||1.1|a2fa819d-343d-4807-9aa6-649800929016|2021-03-12T13:06:38|SPR190613I52|T/tWrc/aeeEC8iDYv06xAUH0gfcPh147JaJkppxrx4znz5z1u7AdGy7bY1/plrmqHCP8xV+p6a/lQwRcf961K2MR513dUy/gaXn4FiVrN5w7wMXL8KXFAHMDRndjtP4K3suuPIkCQnGu5wp2ekajeCGFauklNsMhfxnRY28Tt7ibV81xtuXsGzwCb84mCUYPlGVz8qstMWsc9e6TwuYHlYmG4ZRqv6Vfm5F0IrltfzKRpHBk9fMq0jIXfECCjF20bGRFNJlkUDUdOc0A4Z4YPulHUPIoZ5NfP/Fs1QVfMDm0L22hajiRsQpJWwsDh7/1fsrSEYgarHCVKwnPen9G6Q==|30001000000400002495||"
      PaymentAccountNumber: ""
      PaymentBankName: ""
      PaymentMethod: "PUE - Pago en una sola exhibición"
      PaymentTerms: "01 - Efectivo"
      Receiver: {Rfc: "XAXX010101000", Name: "Prueba", Email: ""}
      Serie: ""
      Status: "active"
      Subtotal: 4951.9
      Taxes: [{Total: 792.3, Name: "IVA", Rate: 16, Type: "transferred"}]
      Total: 5744.2
      Type: "I - Ingreso"
      */
      if (@$aCfdi['id_proyecto'] != null) {
        $oCfdiProyecto = Cfdis::creaRegistro([
          'cfdi' => $oCfdi->Id,
          'folio' => $oCfdi->Folio,
          'receptor' => $oCfdi->Receiver->Rfc,
          'total' => $oCfdi->Total,
          'id_proyecto' => $aCfdi['id_proyecto']
        ]);
      }
      return response()->json([
        "estatus" => 1,
        "mensaje" => "Se ha creado la factura.",
        "resultado" => $oCfdi
      ]);
    } catch (\Exception $e) {
      /*
      Facturama\Exception\RequestException {#1340 ▼
          #message: "La solicitud no es válida."
          #code: 0
          #file: "/Users/oscar/Sites/financiero/vendor/facturama/facturama-php-sdk/src/Client.php"
          #line: 201
          -previous: Facturama\Exception\ModelException {#1330 ▼
            #message: "Metódo de pago inválido; Total incorrecto Base por Rate debe ser igual al Total; La tasa del impuesto no es válida."
            #code: 400
            #file: "/Users/oscar/Sites/financiero/vendor/facturama/facturama-php-sdk/src/Client.php"
            #line: 198
            trace: {▶}
          }
          trace: {▶}
        }
      */
      return response()->json([
        "estatus" => 0,
        "mensaje" => "Error al crear factura.",
        "resultado" => $e->getMessage() . '.' .(@$e->getPrevious() != null ? $e->getPrevious()->getMessage() : '' )
      ]);
    }
  }

  public function get_cfdis( $sType = 'issued')
  {
    $oFacturama = $this->get_facturama();
    $aDatos = [
        'type' => $sType,
        //'keyword' => 'Expresion en Software',
        //'status' => 'all',
        //'rfc' => //RFC del receptor o una parte del mismo
        //'dateStart' => '01/01/2020',
        //'dateStart' => '31/12/2021'
    ];
    return [
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa.',
      'resultado' => $oFacturama->get('/Cfdi', $aDatos)
    ];
  }



  public function get_cfdis_fbm()
  {
    $oPagosCostosFijos = Pagos::select('pagos.Id','costos_fijos.concepto as TaxName','pagos.fecha as Date','movimientos_cuentas.id as id_movimiento','movimientos_cuentas.monto as Total','pagos.pdf','pagos.xml')
    ->where('pagos.id_empresa',\Auth::User()->id_empresa)
    ->where('pagos.estado',1)
    ->where('pagos.tipo',2)
    ->where(function($query){
      $query->where('pagos.pdf','!=',null);
      $query->orWhere('pagos.xml','!=',null);
    })->leftJoin('costos_fijos','costos_fijos.id','pagos.id_elemento')
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id_elemento','pagos.id')
    ->where('movimientos_cuentas.tipo_elemento',1)
    ->get();

    $oPagosCostosIndirectosProyectos = Pagos::select('pagos.Id','costos_indirectos.concepto as TaxName','pagos.fecha as Date','movimientos_cuentas.id as id_movimiento','movimientos_cuentas.monto as Total','pagos.pdf','pagos.xml')
    ->where('pagos.id_empresa',\Auth::User()->id_empresa)
    ->where('pagos.estado',1)
    ->where('pagos.tipo',3)
    ->where(function($query){
      $query->where('pagos.pdf','!=',null);
      $query->orWhere('pagos.xml','!=',null);
    })->leftJoin('costos_indirectos','costos_indirectos.id','pagos.id_elemento')
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id_elemento','pagos.id')
    ->where('movimientos_cuentas.tipo_elemento',1)
    ->get();

    return [
      'estatus' => 1,
      'mensaje' => 'Consulta exitosa.',
      'resultado' => $oPagosCostosFijos->merge($oPagosCostosIndirectosProyectos)
    ];
  }

  public function get_cfdi($sId,$sTipo='pdf')
  {
    /*De prueba
    $facturama = new \Facturama\Client('pruebas', 'pruebas2011');
    $document = 'pdf'; //variable que define el tipo de archivo a descargar(pdf,Xml,html)
    $type = 'IssuedLite';
    $id = 'OwMgofF7ZDEM60gerUXudw2';
    $params = [];
    $result = $facturama->get('cfdi/'.$document.'/'.$type.'/'.$id, $params);
    //dd($result);
    $myfile = fopen('factura'.$id.'.'.$document, 'a+');
    fwrite($myfile, base64_decode(end($result)));
    fclose($myfile);
    return response()->download('factura'.$id.'.'.$document,'factura_'.$id.'.'.$document);*/
    try {
      $oFacturama = $this->get_facturama();
      $sType = 'Issued';
      $aDatos = [];
      $aResultado = $oFacturama->get('cfdi/'.$sTipo.'/'.$sType.'/'.$sId, $aDatos);
      $sCarpeta = public_path() . "/images/empresas/" . (\Auth::User()->id_empresa) . "/facturas/";
      File::makeDirectory($sCarpeta, $mode = 0777, true, true);

      $sRutaFile = $sCarpeta . $sId.".".$sTipo;
      $oFile = fopen($sRutaFile,'a+');
      fwrite($oFile, base64_decode(end($aResultado)));
      fclose($oFile);
      return response()->download($sRutaFile,'factura_'.$sId.'.'.$sTipo);
    } catch (\Exception $e) {
      return view('error')->with('sError', $e->getMessage() . (@$e->getPrevious() != null ? $e->getPrevious()->getMessage() : '' ))
      ->with('sInfo','Posiblemente Facturama esté experimentando problemas. Intenta de nuevo o busca ayuda en Facturama.');
      //dd($e->getMessage(),@$e->getPrevious()->getMessage());
    }
  }

  public function delete_cfdi(Request $request)
  {
    try {

      $oFacturama = $this->get_facturama();
      $aCfdi = $request->all();
      $aDatos = [
          'type' => $aCfdi['type']
      ];

      $aResultado = $oFacturama->delete('Cfdi/'.$aCfdi['cfdi_id'], $aDatos);
      Session::flash('tituloMsg','Facturama');
      Session::flash('mensaje',$aResultado->Message);
      Session::flash('tipoMsg','info');
      return redirect()->back();
    } catch (\Exception $e) {
      Session::flash('tituloMsg','Error Facturama');
      Session::flash('mensaje',$e->getMessage());
      Session::flash('tipoMsg','error');
      return redirect()->back();
    }
  }
}
