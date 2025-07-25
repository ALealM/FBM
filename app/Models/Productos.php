<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

class Productos extends Authenticatable
{
  use Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $table = 'productos';
  protected $fillable = [
    'producto',
    'descripcion',
    'tipo',
    'precio_venta',
    'costo',
    'iva',
    'id_medida',
    'unidades',
    'imagen',
    'estado',
    'id_empresa',
    'id_usuario',
    //SAT
    'product_code',
    'unit_code',
    'fecha_registro',
    'fecha_modificacion'
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
    return Productos::create([
      'id_usuario' => \Auth::User()->id,
      'producto' => $data['producto'],
      'descripcion' => @$data['descripcion'],
      'tipo' => $data['tipo'],
      'precio_venta' => $data['precio_venta'],
      'costo' => $data['costo'],
      'iva' => $data['iva'],
      'id_medida' => $data['id_medida'],
      'unidades' => @$data['unidades'],
      'imagen' => ( isset($data['imagen_url']) ? $data['imagen_url'] : NULL),
      'product_code' => @$data['product_code'],
      'unit_code' => @$data['unit_code'],
      'id_empresa' => \Auth::User()->id_empresa
    ]);
  }

  public static function actualizaRegistro($data)
  {
    $oProducto = Productos::find($data['id']);
    $oProducto->producto = $data['producto'];
    $oProducto->descripcion = @$data['descripcion'];
    $oProducto->tipo = $data['tipo'];
    $oProducto->precio_venta = $data['precio_venta'];
    $oProducto->costo = $data['costo'];
    $oProducto->iva = $data['iva'];
    $oProducto->id_medida = $data['id_medida'];
    $oProducto->unidades = @$data['unidades'];
    $oProducto->product_code = @$data['product_code'];
    $oProducto->unit_code = @$data['unit_code'];
    if ( isset($data['imagen_url']) ) { $oProducto->imagen = $data['imagen_url']; }
    $oProducto->save();
    return $oProducto;
  }

  public static function eliminarRegistro( $iId ) {
    $oProducto = Productos::find( $iId );
    $oProducto->estado = 0;
    $oProducto->save();
    return $oProducto;
  }

  public function usuario(){
    return $this->belongsTo('App\User','id_usuario','id')->first();
  }

  public function venta(){
    return $this->belongsTo('App\Models\Ventas','id','id_producto')->first();
  }

  public function unidades($fi,$ff){
    return Ventas::whereBetween('fecha_venta', [$fi, $ff])->where('id_producto',$this->attributes['id'])->sum('unidades_vendidas')*1;
  }

  public function costos($id){
    $costos = CosteoProducto::where('id_producto',$id)->get();
    foreach ($costos as $costo){
      $costo->total = $costo->almacen()->precio/$costo->mp()->unidades*$costo->cantidad;
    }
    return $costos;
  }

  public function categoria(){
    $categoria = [
      '1'=>'Categoría 1','2'=>'Categoría 2','3'=>'Categoría 3'];
      return $categoria[$this->attributes['categoria']];
    }

    public function material($id){
      $total = 0;
      $mats = CosteoProducto::where('id_producto',$id)->get();
      foreach ($mats as $mat){
        $unidad = $mat->mp()->unidades;
        $precio = $mat->almacen()->precio;
        $total += $precio/$unidad*$mat->cantidad;

      }
      return $total;
    }

    public function indirectos($id){
      $costos = CostosIndirectosPro::where('id_producto',$id)->pluck('id_costo_indirecto');
      return CostosIndirectos::whereIn('id',$costos)->sum('costo');
    }
  }
