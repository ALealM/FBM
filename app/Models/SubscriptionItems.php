<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionItems extends Model
{
  protected $table = 'subscription_items';
  protected $primaryKey = 'id';
  protected $fillable = [
    'id',
    'stripe_id',
    'stripe_plan',
    'quantity',
    'subscription_id',
    'updated_at',
    'created_at'
  ];
  const CREATED_AT = 'created_at';
  const UPDATED_AT = 'updated_at';


}
