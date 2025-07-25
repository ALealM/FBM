<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class MateriaPrima extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'materia_prima';
  protected $fillable = [
    'concepto',
    'costo',
    'incremento_anual',
    'unidades_mayoreo',
    'descuento_mayoreo',
    'id_medida',
    'unidades',
    'estado',
    'id_usuario',
    'id_empresa',
    'fecha_registro',
    'fecha_modificacion'
  ];
  const CREATED_AT = 'fecha_registro';
  const UPDATED_AT = 'fecha_modificacion';

  public static function creaRegistro($data)
  {
    return MateriaPrima::create([
      'concepto' => $data['concepto'],
      'costo' => floatval($data['costo']),
      'incremento_anual' => floatval($data['incremento_anual']),
      'unidades_mayoreo' => floatval($data['unidades_mayoreo']),
      'descuento_mayoreo' => floatval($data['descuento_mayoreo']),
      'id_medida' => $data['id_medida'],
      'unidades' => $data['unidades'],
      'id_usuario' => \Auth::User()->id,
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oMateriaPrima = MateriaPrima::find( $data['id'] );
    $oMateriaPrima->concepto = $data['concepto'];
    $oMateriaPrima->costo = floatval($data['costo']);
    $oMateriaPrima->incremento_anual = floatval($data['incremento_anual']);
    $oMateriaPrima->unidades_mayoreo = floatval($data['unidades_mayoreo']);
    $oMateriaPrima->descuento_mayoreo = floatval($data['descuento_mayoreo']);
    $oMateriaPrima->id_medida = $data['id_medida'];
    $oMateriaPrima->unidades = $data['unidades'];
    $oMateriaPrima->save();
    return $oMateriaPrima;
  }

  public static function eliminarRegistro( $iId )
  {
    $oMateriaPrima = MateriaPrima::find( $iId );
    $oMateriaPrima->estado = 0;
    $oMateriaPrima->save();
    return $oMateriaPrima;
  }

  public function medida(){
    return $this->belongsTo('App\Models\Medidas','id_medida','id')->first();
  }
}
