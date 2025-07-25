<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
  protected $table = 'subscriptions';
  protected $primaryKey = 'id';
  protected $fillable = [
    'id',
    'empresas_id',
    'name',
    'stripe_id',
    'stripe_plan',
    'stripe_estatus',
    'quantity',
    'trial_ends_at',
    'ends_at',
    'created_at',
    'updated_at'
  ];
  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';


}
