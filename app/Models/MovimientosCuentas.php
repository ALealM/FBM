<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MovimientosCuentas extends Model
{
  protected $table = 'movimientos_cuentas';
  protected $fillable = [
    'id',
    'concepto',
    'tipo',// 0 = egreso ,1 = ingreso
    'tipo_elemento', //1 = pago , 2 = cobro
    'id_elemento',//id_pago , id_cobro
    'monto',
    'id_cuenta',
    'fecha',
    'estado',
    'fecha_registro',
    'fecha_modificacion',
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    return MovimientosCuentas::create([
      'concepto' => $data['concepto'],
      'tipo' => $data['tipo'],
      'tipo_elemento' => @$data['tipo_elemento'],
      'id_elemento' => @$data['id_elemento'],
      'monto' => $data['monto'],
      'id_cuenta' => $data['id_cuenta'],
      'fecha' => $data['fecha'],
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oMovimiento = MovimientosCuentas::find($data['id_movimiento']);
    $oMovimiento->concepto = $data['concepto'];
    $oMovimiento->monto = $data['monto'];
    $oMovimiento->id_cuenta = $data['id_cuenta'];
    $oMovimiento->fecha = $data['fecha'];
    $oMovimiento->save();
    return $oMovimiento;
  }

  public static function totalAbonos($iId)
  {
    $fAbonos = floatval(MovimientosCuentas::where('id_cuenta',$iId)->where('tipo',1)->where('estado',1)->sum('monto'));

    return $fAbonos;
  }

  public static function totalCargos($iId)
  {
    $fCargos = floatval(MovimientosCuentas::where('id_cuenta',$iId)->where('tipo',0)->where('estado',1)->sum('monto'));

    return $fCargos;
  }

  public static function getTotal($iId)
  {
    $fAbonos = floatval(MovimientosCuentas::where('id_cuenta',$iId)->where('tipo',1)->where('estado',1)->sum('monto'));
    $fCargos = floatval(MovimientosCuentas::where('id_cuenta',$iId)->where('tipo',0)->where('estado',1)->sum('monto'));
    return ($fAbonos - $fCargos);
  }

  public function get_descripcion()
  {
    $sDescripcion = "";
    if ( $this->tipo_elemento == 1 ) {//Pago
      $oPago = Pagos::where('id',$this->id_elemento)->first();
      if ($oPago->tipo == 1) {// 1 = pago nomina , 2 = costo fijo, 3 = pago costo indirecto (proyectos)
        $oManoObra = ManoObra::where('id',$oPago->id_elemento)->first();
        $sDescripcion = $sDescripcion . ' ' . $oManoObra->nombre . '<br>' . $oManoObra->concepto;
      }elseif ($oPago->tipo == 2) {
        $oCostoFijo = CostosFijos::where('id',$oPago->id_elemento)->first();
        $sDescripcion = $sDescripcion . ' ' . $oCostoFijo->concepto;
      }elseif ($oPago->tipo == 3) {
        $oCostoIndirecto = CostosIndirectos::where('id',$oPago->id_elemento)->first();
        $sDescripcion = $sDescripcion . ' ' . $oCostoIndirecto->concepto;
      }
    }elseif ( $this->tipo_elemento == 2 ) {//Cobro
      $oCobro = Cobros::where('id',$this->id_elemento)->first();
      if ($oCobro->tipo == 1) {// 1 = proyectos
        $oProyecto = Proyectos::where('id',$oCobro->id_elemento)->first();
        $sDescripcion = $sDescripcion . ' ' . $oProyecto->nombre;
      }
    }
    return $sDescripcion;
  }

  public static function eliminarRegistro($data)
  {
    $oMovimiento = MovimientosCuentas::find($data['id_movimiento']);
    $oMovimiento->estado = 0;
    $oMovimiento->save();
    return $oMovimiento;
  }
}
