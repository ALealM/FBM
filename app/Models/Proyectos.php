<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Models\Cobros;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Proyectos extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'proyectos';
  public $timestamps = false;
  protected $fillable = [
    'nombre',
    'alcance',
    'sub_proyecto',
    'id_lider',
    'fecha_inicio',
    'fecha_fin',
    'iva',
    //'categoria',
    //'duracion',
    //'precio_venta',
    //'recurrencia',
    'margen_error',
    'margen',
    'estado',
    'id_empresa',
    'id_usuario',
    'fecha_registro',
    'fecha_modificacion',
    //facturama
    'product_code',
    'unit_code'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';
  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */

  public static function creaRegistro($data)
  {
    return Proyectos::create([
      'nombre' => $data['nombre'],
      'alcance' => $data['alcance'],
      'sub_proyecto' => $data['sub_proyecto'],
      'id_lider' => @$data['id_lider'],
      'fecha_inicio' => $data['fecha_inicio'],
      'fecha_fin' => $data['fecha_fin'],
      'margen_error' => $data['margen_error'],
      'margen' => $data['margen'],
      'iva' => $data['iva'],
      'product_code' => @$data['product_code'],
      'unit_code' => @$data['unit_code'],
      'id_empresa' => \Auth::User()->id_empresa,
      'id_usuario' => \Auth::User()->id
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oProyecto = Proyectos::find($data['id']);
    $oProyecto->nombre = $data['nombre'];
    $oProyecto->alcance = $data['alcance'];
    $oProyecto->sub_proyecto = $data['sub_proyecto'];
    $oProyecto->id_lider = @$data['id_lider'];
    $oProyecto->margen_error = $data['margen_error'];
    $oProyecto->margen = $data['margen'];
    $oProyecto->iva = $data['iva'];
    $oProyecto->product_code = @$data['product_code'];
    $oProyecto->unit_code = @$data['unit_code'];
    $oProyecto->fecha_inicio = $data['fecha_inicio'];
    $oProyecto->fecha_fin = $data['fecha_fin'];
    $oProyecto->save();
    return $oProyecto;
  }

  public static function eliminarRegistro($iId)
  {
    $oProyecto = Proyectos::find( $iId );
    $oProyecto->estado = 0;
    $oProyecto->save();
    return $oProyecto;
  }

  public function usuario()
  {
    return $this->belongsTo('App\User','id_usuario','id')->first();
  }

  public function categoria()
  {
    $categoria = ['1'=>'Categoría 1','2'=>'Categoría 2','3'=>'Categoría 3'];
    return $categoria[$this->attributes['categoria']];
  }

  public function presupuesto()
  {
    $oFases = app('App\Http\Controllers\ProyectosController')->get_fases_proyecto($this->id);
    $oCostosIndirectos = app('App\Http\Controllers\ProyectosController')->get_costos_indirectos($this->id);
    $fTotalFases = 0;
    $fTotalIndirectos = 0;

    foreach($oFases as $key => $oFase)
    {
      $oRoles = $oFase->roles();
      foreach ($oRoles as $oRol)
      {
        $oParticipantes = $oRol->participantes();
        foreach ($oParticipantes as $oParticipante)
        {
          $fTotalFases += $oParticipante->valor;
        }
      }
    }

    foreach($oCostosIndirectos as $oCostoIndirecto)
    {
      //$fTotalIndirectos += ($oCostoIndirecto->unidades / $oCostoIndirecto->indirecto_unidades) * $oCostoIndirecto->costo;
      $fTotalIndirectos += $oCostoIndirecto->costo;
    }

    $fMargen = ($fTotalFases+$fTotalIndirectos) * $this->margen / 100;
    $fMargenError = ($fTotalFases+$fTotalIndirectos) * $this->margen_error / 100;
    $fTotal = $fTotalFases + $fTotalIndirectos + $fMargen + $fMargenError;
    $fIva = ($this->iva > 0 ? ($fTotal * $this->iva / 100) : 0);

    return [
      'total_fases' => number_format($fTotalFases,2,'.',''),
      'total_costos_indirectos' => number_format($fTotalIndirectos,2,'.','')*1,
      'total_margen' => number_format($fMargen,2,'','')*1,
      'total_margen_error' => number_format($fMargenError,2,'.','')*1,
      'subtotal' => number_format($fTotal,2,'.','')*1,
      'iva' => number_format($fIva,2,'.','')*1,
      'precio_venta' => number_format($fTotal + $fIva,2,'.','')*1
    ];
  }

  public function cobros()
  {
    $oCobros = Cobros::select('cobros.*','movimientos_cuentas.concepto','movimientos_cuentas.monto','cuentas.nombre as nombre_cuenta')
    ->where('cobros.id_elemento',$this->id)
    ->where('cobros.tipo',1)
    ->leftJoin('movimientos_cuentas','movimientos_cuentas.id','cobros.id_movimiento')
    ->leftJoin('cuentas','cuentas.id','movimientos_cuentas.id_cuenta')
    ->get();

    $fTotalCobrado = $oCobros->sum('monto');

    return [
      'numero_cobros' => $oCobros->count(),
      'cobros' => $oCobros,
      'total_cobrado' => $fTotalCobrado
    ];
  }
}
