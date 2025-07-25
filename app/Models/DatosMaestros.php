<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatosMaestros extends Model
{
  protected $table = 'datos_maestros';
  public $timestamps = false;
  protected $fillable = [
    'id',
    'art_106_1_lss',
    'art_25_lss',
    'art_25_ls_pencionados',
    'art_106_2_lss',
    'art_71_211_lss',
    'art_147_lss',
    'art_168_1_71_211_lss',
    'art_29_2_infonavit'
  ];

}
