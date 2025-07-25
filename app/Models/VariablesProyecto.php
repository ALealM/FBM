<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class VariablesProyecto extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'variables_proyecto';
  public $timestamps = false;
  protected $fillable = [
    'id_usuario',
    'fecha_alta',
    'id_proyecto',
    'tipo_contrato',
    'precio_contrato',
    'duracion_contrato',
    'concepto_directa',
    'unidad_directa',
    'volumen_directa',
    'precio_directa',
    'concepto_indirecta',
    'unidad_indirecta',
    'volumen_indirecta',
    'precio_indirecta',
    'concepto_otros',
    'precio_otros',
    'estado'
  ];

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */

  public static function creaRegistro($data) {
    return VariablesProyecto::create([
      'id_usuario' => \Auth::User()->id,
      'fecha_alta' => date("Y-m-d H:i:s"),
      'id_proyecto' => $data['id_proyecto'],
      'tipo_contrato' => $data['tipo_contrato'],
      'precio_contrato' => $data['precio_contrato'],
      'duracion_contrato' => $data['duracion_contrato'],
      'concepto_directa' => $data['concepto_directa'],
      'unidad_directa' => $data['unidad_directa'],
      'volumen_directa' => $data['volumen_directa'],
      'precio_directa' => $data['precio_directa'],
      'concepto_indirecta' => $data['concepto_indirecta'],
      'unidad_indirecta' => $data['unidad_indirecta'],
      'volumen_indirecta' => $data['volumen_indirecta'],
      'precio_indirecta' => $data['precio_indirecta'],
      'concepto_otros' => ($data['concepto_otros']) ? $data['concepto_otros'] : '- - -',
      'precio_otros' => ($data['precio_otros']) ? $data['precio_otros'] : 0,
    ]);
  }

  public static function actualizaRegistro($data) {
    $oVariable = VariablesProyecto::find( $data['id'] );
    $oVariable->tipo_contrato = $data['tipo_contrato'];
    $oVariable->precio_contrato = $data['precio_contrato'];
    $oVariable->duracion_contrato = $data['duracion_contrato'];
    $oVariable->concepto_directa = $data['concepto_directa'];
    $oVariable->unidad_directa = $data['unidad_directa'];
    $oVariable->volumen_directa = $data['volumen_directa'];
    $oVariable->precio_directa = $data['precio_directa'];
    $oVariable->concepto_indirecta = $data['concepto_indirecta'];
    $oVariable->unidad_indirecta = $data['unidad_indirecta'];
    $oVariable->volumen_indirecta = $data['volumen_indirecta'];
    $oVariable->precio_indirecta = $data['precio_indirecta'];
    $oVariable->concepto_otros = ($data['concepto_otros']) ? $data['concepto_otros'] : '- - -';
    $oVariable->precio_otros = ($data['precio_otros']) ? $data['precio_otros'] : 0;
    $oVariable->save();

    return $oVariable;
  }

  public static function eliminarRegistro($iId) {
    $oVariable = VariablesProyecto::find( $iId );
    $oVariable->estado = 0;
    $oVariable->save();
    return $oVariable;
  }

  public function usuario(){
    return $this->belongsTo('App\User','id_usuario','id')->first();
  }

  public function unidadDir(){
    $unidad = [
      '1'=>'Pieza','2'=>'Kilo','3'=>'Litro'];
      return $unidad[$this->attributes['unidad_directa']];
    }

    public function unidadInd(){
      $unidad = [
        '1'=>'Pieza','2'=>'Kilo','3'=>'Litro'];
        return $unidad[$this->attributes['unidad_indirecta']];
      }

      public function contratacion(){
        $cont = [
          '1'=>'Honorarios','2'=>'Asimilables','3'=>'Confianza'];
          return $cont[$this->attributes['tipo_contrato']];
        }
      }
