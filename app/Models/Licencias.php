<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Licencias extends Model
{
  protected $table = 'licencias';
  protected $primaryKey = 'id';
  public $timestamps = false;
  protected $fillable =
  [
    'id',
    'nombre',
    'descripcion',
    'estado',//0 = eliminada, 1 = activa
    'tipo',
    'duracion',//meses
    'costo',
    'm_costos_fijos',
    'm_mano_obra',
    'm_materia_prima',
    'm_costos_indirectos',
    'm_productos',
    'm_proyectos',
    'm_ventas',
    'm_proyecciones',
    'm_escenarios',
    'm_estado_cuenta',
    'numero_productos',
    'numero_usuarios',
    'numero_mano_obra',
    'numero_proyectos',
    'stripe_price'
  ];
}
