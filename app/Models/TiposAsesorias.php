<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposAsesorias extends Model
{
  protected $table = 'tipos_asesorias';
  protected $primaryKey = 'id';
  public $timestamps = false;
  protected $fillable =
  [
    'id',
    'nombre',
    'descripcion',
    'costo',
    'estado',//0 = eliminada, 1 = activa
  ];
}
